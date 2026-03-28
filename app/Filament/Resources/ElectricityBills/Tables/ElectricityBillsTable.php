<?php

namespace App\Filament\Resources\ElectricityBills\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}