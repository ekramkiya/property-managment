<?php

namespace App\Filament\Resources\ElectricityBills\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Models\ElectricityBill;
use Filament\Forms\Components\ToggleButtons;

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
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Only auto-fill when creating a new record
                        if (!request()->routeIs('filament.resources.electricity-bills.edit') && $state) {
                            // Get the most recently PAID bill (by updated_at = when it was paid)
                            $lastPaidBill = ElectricityBill::where('customer_id', $state)
                                ->where('status', 'paid')
                                ->orderBy('updated_at', 'desc')  // ← key change: use updated_at
                                ->first();

                            $previousReading = $lastPaidBill?->current_reading ?? 0;
                            $set('previous_reading', $previousReading);
                        }
                    }),
                TextInput::make('previous_reading')
                    ->label('شماره قبلی')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->dehydrated(true)
                    ->helperText('از آخرین قبض پرداخت شده (بر اساس زمان پرداخت) پر می‌شود'),
                TextInput::make('current_reading')
                    ->label('شماره فعلی')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->helperText('عدد فعلی میتر برق (بیشتر از قبلی)')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $previous = $get('previous_reading');
                        if (is_numeric($previous) && is_numeric($state) && $state > $previous) {
                            $amount = ($state - $previous) * 16;
                            $set('amount', number_format($amount, 0) . ' AFN');
                        } else {
                            $set('amount', 'مقدار نامعتبر');
                        }
                    }),
                DatePicker::make('reading_date')
                    ->label('تاریخ قرائت')
                    ->required()
                    ->default(now())
                    ->jalali(weekdaysShort: true)
                    ->hasToday(),
                TextInput::make('amount')
                    ->label('مبلغ')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('به صورت خودکار محاسبه می‌شود: (فعلی - قبلی) × ۱۶'),
                Textarea::make('note')
                    ->label('یادداشت')
                    ->columnSpanFull(),

                ToggleButtons::make('status')
                    ->label('وضعیت پرداخت')
                    ->options([
                        'unpaid' => 'پرداخت نشده',
                        'paid' => 'پرداخت شده',
                    ])
                    ->colors([
                        'unpaid' => 'danger',
                        'paid' => 'success',
                    ])
                    ->icons([
                        'unpaid' => 'heroicon-o-x-circle',
                        'paid' => 'heroicon-o-check-circle',
                    ])
                    ->inline()
                    ->default('unpaid')
                    ->required(),
            ]);
    }
}