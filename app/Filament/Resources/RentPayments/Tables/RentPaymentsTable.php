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
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\FileUpload\InputFile;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

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

                // Telegram share action with font configuration
                Action::make('shareTelegram')
                    ->label('ارسال به تلگرام')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->action(function ($record) {
                        // 1. Generate PDF with custom font settings
                        $pdf = Pdf::loadView('pdf.rent_invoice', ['payment' => $record->load('user')])
                            ->setOptions([
                                'fontDir' => public_path('fonts/ttf'),          // folder with TTF files
                                'fontCache' => storage_path('fonts'),           // writable cache folder
                                'defaultFont' => 'Vazirmatn',                  // font family name
                                'isHtml5ParserEnabled' => true,
                                'isRemoteEnabled' => true,
                            ]);
                        $pdfContent = $pdf->output();

                        // 2. Save to temporary file
                        $fileName = 'invoice_' . $record->id . '_' . Str::random(8) . '.pdf';
                        Storage::disk('public_temp')->put($fileName, $pdfContent);
                        $localPath = Storage::disk('public_temp')->path($fileName);

                        // 3. Get chat ID
                        $chatId = $record->customer->telegram_chat_id;
                        if (!$chatId) {
                            Notification::make()
                                ->title('شماره تلگرام مشتری ثبت نشده است')
                                ->danger()
                                ->send();
                            return;
                        }

                        // 4. Send via HTTP client with timeout (disable SSL for local dev only)
                        try {
                            $response = Http::timeout(30)
                                ->withoutVerifying()  // remove in production; ensure CA bundle is configured
                                ->attach('document', file_get_contents($localPath), $fileName)
                                ->post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendDocument", [
                                    'chat_id' => $chatId,
                                    'caption' => "رسید پرداخت اجاره شماره {$record->id}\nمبلغ: {$record->amount} AFN",
                                ]);

                            if ($response->failed()) {
                                $error = $response->json('description', 'Unknown error');
                                throw new \Exception($error);
                            }

                            // Optionally delete the temporary file after sending
                            // Storage::disk('public_temp')->delete($fileName);

                            Notification::make()
                                ->title('فایل با موفقیت ارسال شد')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('خطا در ارسال به تلگرام')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                    ExportBulkAction::make()
                ]),
            ]);
    }
}