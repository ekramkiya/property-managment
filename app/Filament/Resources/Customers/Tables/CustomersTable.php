<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام')
                    ->searchable(),
                TextColumn::make('father_name')
                    ->label('نام پدر')
                    ->searchable(),
                TextColumn::make('lastname')
                    ->label('نام خانوادگی')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('شماره تلفن')
                    ->searchable(),
                TextColumn::make('whatsapp_number')
                    ->label('شماره واتساپ')
                    ->searchable(),
                TextColumn::make('telegram_chat_id')
                    ->label('ای دی تلگرام')
                    ->searchable(),
                TextColumn::make('monthly_rent')
                    ->label('اجاره ماهانه')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('تاریخ بروزرسانی')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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