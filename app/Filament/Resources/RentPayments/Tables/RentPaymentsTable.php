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
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

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
                TextColumn::make('month')
                    ->label('ماه')
                    ->searchable()
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
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('از تاریخ')
                            ->jalali(),

                        DatePicker::make('created_until')
                            ->label('تا تاریخ')
                            ->jalali(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!empty($data['created_from'])) {
                            $fromDate = Carbon::parse($data['created_from'])->startOfDay();
                            $query->whereDate('created_at', '>=', $fromDate);
                        }

                        if (!empty($data['created_until'])) {
                            $toDate = Carbon::parse($data['created_until'])->endOfDay();
                            $query->whereDate('created_at', '<=', $toDate);
                        }

                        return $query;
                    }),

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



// WhatsApp share action
Action::make('shareWhatsapp')
    ->label('اشتراک در واتساپ')
    ->icon('heroicon-o-chat-bubble-left-ellipsis')
    ->color('success')
    ->action(function ($record) {
        // 1. Generate PDF using DomPDF with Persian invoice Blade
        $pdf = Pdf::loadView('pdf.farsi_invoice', ['payment' => $record->load('user')])
            ->setOptions([
                'defaultFont' => 'Vazirmatn-Regular',        // Use the Persian Vazir font
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'isPhpEnabled' => false,
            ]);

        $pdfContent = $pdf->output();

        // 2. Save directly to public/temp/ folder
        $fileName = 'farsi_invoice_' . $record->id . '_' . Str::random(8) . '.pdf';
        $relativePath = 'temp/' . $fileName;   // relative to public/
        $fullPath = public_path($relativePath);

        // Create temp folder if it doesn't exist
        if (!is_dir(public_path('temp'))) {
            mkdir(public_path('temp'), 0755, true);
        }

        file_put_contents($fullPath, $pdfContent);

        // 3. Generate public URL
        $url = asset($relativePath);  

        // 4. Prepare WhatsApp number
        $phone = $record->customer->whatsapp_number ?? $record->customer->phone;
        $phone = preg_replace('/\D/', '', $phone);
        if (!Str::startsWith($phone, '93')) {
            $phone = '93' . ltrim($phone, '0');
        }

        // 5. Create message and redirect
        $message = "رسید پرداخت کرایه {$record->id}:\n{$url}";
        return redirect("https://wa.me/{$phone}?text=" . urlencode($message));
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