<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('نام')
                    ->required(),
                TextInput::make('father_name')
                    ->label('نام پدر'),
                TextInput::make('lastname')
                    ->label('نام خانوادگی'),
                DatePicker::make('start_date_of_contract')
                    ->label('تاریخ شروع قرارداد')
                    ->jalali(weekdaysShort: true)
                    ->hasToday(),

                DatePicker::make('end_date_of_contract')
                    ->label('تاریخ پایان قرارداد')
                    ->jalali(weekdaysShort: true)
                    ->hasToday(),
                TextInput::make('phone')
                    ->label('شماره تلفن')
                    ->tel()
                    ->required(),
                TextInput::make('whatsapp_number')
                    ->label('شماره واتساپ'),
                TextInput::make('telegram_chat_id')
                    ->label('شناسه تلگرام (Chat ID)')
                    ->nullable()
                    ->helperText('شناسه عددی کاربر در تلگرام – در صورت وارد کردن، می‌توانید رسید را مستقیماً برای مشتری ارسال کنید.'),
                TextInput::make('monthly_rent')
                    ->label('اجاره ماهانه')
                    ->required()
                    ->numeric(),
            ]);
    }
}