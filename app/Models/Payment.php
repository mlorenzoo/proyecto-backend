<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'pay_date', 'amount', 'pay_method'];

    // Relación con el cliente que realizó el pago
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
