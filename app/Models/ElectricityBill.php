<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectricityBill extends Model
{
  

    protected $fillable = ['customer_id', 'previous_reading', 'current_reading', 'reading_date', 'amount', 'note'];

    protected $casts = [
        'reading_date' => 'date',
    ];

    protected static function booted()
    {
        static::saving(function ($bill) {
            $bill->amount = ($bill->current_reading - $bill->previous_reading) * 16;
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}