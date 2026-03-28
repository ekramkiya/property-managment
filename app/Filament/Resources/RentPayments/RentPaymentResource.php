<?php

namespace App\Filament\Resources\RentPayments;

use App\Filament\Resources\RentPayments\Pages\CreateRentPayment;
use App\Filament\Resources\RentPayments\Pages\EditRentPayment;
use App\Filament\Resources\RentPayments\Pages\ListRentPayments;
use App\Filament\Resources\RentPayments\Schemas\RentPaymentForm;
use App\Filament\Resources\RentPayments\Tables\RentPaymentsTable;
use App\Models\RentPayment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Torgodly\Html2Media\Tables\Actions\Html2MediaAction;

class RentPaymentResource extends Resource
{
    protected static ?string $model = RentPayment::class;

    // Persian labels
    protected static ?string $navigationLabel = 'پرداخت‌های کرایه';
    protected static ?string $modelLabel = 'پرداخت کرایه';
    protected static ?string $pluralModelLabel = 'پرداخت‌های کرایه';

    // Set which attribute to display in global search, relation managers, etc.
    // For payments, it's common to show the payment ID or customer name, but we'll use ID for simplicity.
    protected static ?string $recordTitleAttribute = 'id';

    // Use a relevant icon
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;

    public static function form(Schema $schema): Schema
    {
        return RentPaymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RentPaymentsTable::configure($table);
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
            'index' => ListRentPayments::route('/'),
            'create' => CreateRentPayment::route('/create'),
            'edit' => EditRentPayment::route('/{record}/edit'),
        ];
    }
}