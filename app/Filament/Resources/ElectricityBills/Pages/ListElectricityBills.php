<?php

namespace App\Filament\Resources\ElectricityBills\Pages;

use App\Filament\Resources\ElectricityBills\ElectricityBillResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListElectricityBills extends ListRecords
{
    protected static string $resource = ElectricityBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
