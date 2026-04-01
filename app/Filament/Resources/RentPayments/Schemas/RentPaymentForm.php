<?php

namespace App\Filament\Resources\RentPayments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Models\Customer;
use Filament\Forms\Components\Hidden;
use App\Enums\AfghanMonth;
class RentPaymentForm
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
                TextInput::make('amount')
                    ->label('مبلغ پرداختی')
                    ->required()
                    ->numeric()
                    ->prefix('AFN'),
                // or your currency
                Select::make('month')
                    ->label('ماه')
                    ->options(AfghanMonth::options())
                    ->required()
                    ->default(AfghanMonth::current()->value), // optional, sets current month
                DatePicker::make('payment_date')
                    ->label('تاریخ پرداخت')
                    ->required()
                    ->jalali(weekdaysShort: true)->hasToday()
                    ->default(now()),
                Textarea::make('note')
                    ->label('یادداشت')
                    ->columnSpanFull(),
                Hidden::make('user_id')
                    ->default(auth()->id()),
            ]);
    }
}