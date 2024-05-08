<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;

class Barbershop extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ubication', 'lat', 'lon', 'gestor_id', 'barber_id'];

    // Relación con el gestor de la peluquería
    public function gestor()
    {
        return $this->belongsTo(User::class, 'gestor_id');
    }

    // Relación con los barberos de la peluquería
    public function barbers()
    {
        return $this->hasMany(Barber::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class)->cascadeDelete();
    }
}
