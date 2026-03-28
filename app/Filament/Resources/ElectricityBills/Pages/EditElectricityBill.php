<?php

namespace App\Filament\Resources\ElectricityBills\Pages;

use App\Filament\Resources\ElectricityBills\ElectricityBillResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditElectricityBill extends EditRecord
{
    protected static string $resource = ElectricityBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
