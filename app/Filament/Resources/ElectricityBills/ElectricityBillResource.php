<?php

namespace App\Filament\Resources\ElectricityBills;

use App\Filament\Resources\ElectricityBills\Pages\CreateElectricityBill;
use App\Filament\Resources\ElectricityBills\Pages\EditElectricityBill;
use App\Filament\Resources\ElectricityBills\Pages\ListElectricityBills;
use App\Filament\Resources\ElectricityBills\Schemas\ElectricityBillForm;
use App\Filament\Resources\ElectricityBills\Tables\ElectricityBillsTable;
use App\Models\ElectricityBill;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ElectricityBillResource extends Resource
{
    protected static ?string $model = ElectricityBill::class;

    // Persian labels
    protected static ?string $navigationLabel = 'صورت‌حساب برق';
    protected static ?string $modelLabel = 'صورت‌حساب برق';
    protected static ?string $pluralModelLabel = 'صورت‌حساب‌های برق';

    // Set which attribute to use when displaying a record in global search, etc.
    protected static ?string $recordTitleAttribute = 'id';

    // Use a relevant icon
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Bolt;

    public static function form(Schema $schema): Schema
    {
        return ElectricityBillForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ElectricityBillsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListElectricityBills::route('/'),
            'create' => CreateElectricityBill::route('/create'),
            'edit' => EditElectricityBill::route('/{record}/edit'),
        ];
    }
}