<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
   

    protected $fillable = [
        'name', 'father_name', 
        'lastname', 
        'phone',
         'whatsapp_number', 
         'monthly_rent',
         'telegram_chat_id',
        'start_date_of_contract',   // new
        'end_date_of_contract'      // new
    ];


        protected $casts = [
        'start_date_of_contract' => 'date',
        'end_date_of_contract'   => 'date',
    ];


    public function rentPayments()
    {
        return $this->hasMany(RentPayment::class);
    }

    public function electricityBills()
    {
        return $this->hasMany(ElectricityBill::class);
    }



    // In Customer.php
public function getTotalPaidAttribute()
{
    return $this->rentPayments()->sum('amount');
}

public function getRemainingRentAttribute($currentMonth = null)
{
    // For simplicity, let's calculate remaining for the current month
    $currentMonthStart = now()->startOfMonth();
    $currentMonthEnd = now()->endOfMonth();

    $paidThisMonth = $this->rentPayments()
        ->whereBetween('payment_date', [$currentMonthStart, $currentMonthEnd])
        ->sum('amount');

    return $this->monthly_rent - $paidThisMonth;
}
}