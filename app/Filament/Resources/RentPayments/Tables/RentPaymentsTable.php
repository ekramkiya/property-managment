<?php

namespace App\Filament\Resources\RentPayments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Torgodly\Html2Media\Actions\Html2MediaAction;
use Torgodly\Html2Media\Html2Media;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Barryvdh\DomPDF\Facade\Pdf;
class RentPaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label('مشتری')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('مبلغ')
                    ->money('AFN')
                    ->sortable(),
                TextColumn::make('payment_date')
                    ->label('تاریخ پرداخت')
                    ->jalaliDate()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('ثبت‌کننده')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->dateTime()
                    ->sortable()
                    ->jalaliDateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('تاریخ بروزرسانی')
                    ->dateTime()
                    ->sortable()
                    ->jalaliDateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // your filters
            ])
            ->headerActions([
                Html2MediaAction::make('exportPdf')
                    ->label('خروجی PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->content(function ($livewire) {
                        $query = $livewire->getFilteredTableQuery();
                        $records = $query->get();
                        return view('pdf.rent_payments_table', ['payments' => $records]);
                    })
                    ->savePdf()
                    ->preview()
                    ->filename(fn() => 'rent_payments_' . now()->format('Y-m-d') . '.pdf')
                    ->orientation('portrait')
                    ->format('a4')
            ])
            ->recordActions([
                EditAction::make(),
                Html2MediaAction::make('invoice')
                    ->label('رسید پرداخت')
                    ->icon('heroicon-o-document-arrow-down')
                    ->content(fn($record) => view('pdf.rent_invoice', ['payment' => $record]))
                    ->filename(fn($record) => 'invoice_' . $record->id . '.pdf')
                    ->savePdf()
                    ->preview()
                    ->orientation('portrait')
                    ->format('a4'),

                // WhatsApp share action
                Action::make('shareWhatsapp')
                    ->label('اشتراک در واتساپ')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->action(function ($record) {
                        // 1. Generate PDF using DomPDF
                        $pdf = Pdf::loadView('pdf.rent_invoice', ['payment' => $record->load('user')]);
                        $pdfContent = $pdf->output();

                        // 2. Save the PDF in the temporary public folder
                        $fileName = 'invoice_' . $record->id . '_' . Str::random(8) . '.pdf';
                        $path = 'temp/' . $fileName;
                        Storage::disk('public_temp')->put($path, $pdfContent);

                        // 3. Get the public URL
                        $url = Storage::disk('public_temp')->url($path);

                        // 4. Prepare the customer's WhatsApp number
                        $phone = $record->customer->whatsapp_number ?? $record->customer->phone;
                        $phone = preg_replace('/\D/', '', $phone);
                        if (!Str::startsWith($phone, '93')) { // adjust country code
                            $phone = '93' . ltrim($phone, '0');
                        }

                        // 5. Create the WhatsApp message
                        $message = "رسید پرداخت اجاره شماره {$record->id}:\n{$url}";
                        $encodedMessage = urlencode($message);

                        // 6. Redirect to WhatsApp
                        return redirect("https://wa.me/{$phone}?text={$encodedMessage}");
                    })
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                    ExportBulkAction::make()
                ]),
            ]);
    }
}