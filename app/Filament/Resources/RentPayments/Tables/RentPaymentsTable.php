<?php

namespace App\Filament\Resources\RentPayments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Torgodly\Html2Media\Actions\Html2MediaAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

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
            ->headerActions([  // ← Add header actions here
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
                    ->savePdf()            // enable PDF download
                    ->preview()            // optional preview
                    ->orientation('portrait')
                    ->format('a4')
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                    ExportBulkAction::make()
                ]),
            ]);
    }
}