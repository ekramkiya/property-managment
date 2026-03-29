<?php

namespace App\Filament\Resources\ElectricityBills\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use App\Models\ElectricityBill;
use Filament\Actions\Action;
use Torgodly\Html2Media\Actions\Html2MediaAction;

class ElectricityBillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label('مشتری')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('previous_reading')
                    ->label('شماره قبلی')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('current_reading')
                    ->label('شماره فعلی')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reading_date')
                    ->label('تاریخ قرائت')
                    ->jalaliDate()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label(' مبلغ قابل پرداخت')
                    ->money('AFN') // or 'IRR' for Iranian Rial, adjust as needed
                    ->sortable(),
                IconColumn::make('status')
                    ->label('وضعیت')
                    ->icon(fn(string $state): string => match ($state) {
                        'paid' => 'heroicon-o-check-circle',
                        'unpaid' => 'heroicon-o-x-circle',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                    })
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
                // Add filters if needed
            ])
            ->recordActions([
                EditAction::make(),
                Html2MediaAction::make('invoice')
                    ->label('رسید پرداخت')
                    ->icon('heroicon-o-document-arrow-down')
                    ->content(fn($record) => view('pdf.electricity-bill', ['bill' => $record]))
                    ->filename(fn($record) => 'invoice_' . $record->id . '.pdf')
                    ->savePdf()
                    ->preview()
                    ->orientation('portrait')
                    ->format('a4')
                    ->visible(fn($record) => $record->status === 'paid'),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}