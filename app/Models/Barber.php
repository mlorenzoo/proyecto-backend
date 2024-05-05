<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Barbershop;

class Barber extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'bio',
        'experience',
        'specialties',
        'pics',
        'barbershop_id', // Agregar la columna barbershop_id
    ];

    /**
     * Get the user associated with the barber.
     */
    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con la peluquería
    public function barbershop()
    {
        return $this->belongsTo(Barbershop::class);
    }
}
