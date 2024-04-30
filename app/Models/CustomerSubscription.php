<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSubscription extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'sub_id', 'pay_id', 'start_date', 'end_date', 'state'];

    // Relación con el cliente
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relación con la suscripción
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'sub_id');
    }

    // Relación con el pago
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'pay_id');
    }
}
