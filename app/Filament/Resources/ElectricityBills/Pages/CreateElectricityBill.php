<?php

namespace App\Filament\Resources\ElectricityBills\Pages;

use App\Filament\Resources\ElectricityBills\ElectricityBillResource;
use Filament\Resources\Pages\CreateRecord;

class CreateElectricityBill extends CreateRecord
{
    protected static string $resource = ElectricityBillResource::class;
}
