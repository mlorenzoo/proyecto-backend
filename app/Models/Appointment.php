<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = ['barber_id', 'client_id', 'date', 'hour', 'state', 'notes'];

    // Relación con el barbero
    public function barber()
    {
        return $this->belongsTo(User::class, 'barber_id');
    }

    // Relación con el cliente
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relación con los servicios de la cita
    // public function services()
    // {
    //     return $this->hasMany(Service::class, 'services_id');
    // }
}
