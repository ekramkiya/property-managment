<?php

namespace App\Models;

use App\Enums\AfghanMonth;

use Illuminate\Database\Eloquent\Model;

class RentPayment extends Model
{
  

    protected $fillable = ['customer_id', 'amount', 'payment_date', 'note','user_id','month'];

    protected $casts = [
        'payment_date' => 'date',
        'month' => AfghanMonth::class,
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