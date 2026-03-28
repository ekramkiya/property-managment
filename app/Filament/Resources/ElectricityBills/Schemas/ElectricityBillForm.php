<?php

namespace App\Filament\Resources\ElectricityBills\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Models\Customer;

class ElectricityBillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->label('مشتری')
                    ->relationship('customer', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('previous_reading')
                    ->label('شماره قبلی')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->helperText('عدد قبلی میتر برق'),
                TextInput::make('current_reading')
                    ->label('شماره فعلی')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->helperText('عدد فعلی میتر برق (بیشتر از قبلی)'),
                DatePicker::make('reading_date')
                    ->label('تاریخ قرائت')
                    ->required()
                    ->default(now())
                    ->jalali(weekdaysShort: true)->hasToday(),
                TextInput::make('amount')
                    ->label('مبلغ')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('به صورت خودکار محاسبه می‌شود: (فعلی - قبلی) × ۱۶'),
                Textarea::make('note')
                    ->label('یادداشت')
                    ->columnSpanFull(),
            ]);
    }
}