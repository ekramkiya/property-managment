<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

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
                TextInput::make('phone')
                    ->label('شماره تلفن')
                    ->tel()
                    ->required(),
                TextInput::make('whatsapp_number')
                    ->label('شماره واتساپ'),
                TextInput::make('monthly_rent')
                    ->label('اجاره ماهانه')
                    ->required()
                    ->numeric(),
            ]);
    }
}