<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentPayment extends Model
{
  

    protected $fillable = ['customer_id', 'amount', 'payment_date', 'note','user_id'];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }



    protected static function booted()
{
    static::creating(function ($payment) {
        if (auth()->check()) {
            $payment->user_id = auth()->id();
        }
    });
}
}