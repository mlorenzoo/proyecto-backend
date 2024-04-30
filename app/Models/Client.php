<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
    ];

    /**
     * Get the user associated with the client.
     */
    // Relación con el usuario cliente
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con las suscripciones del cliente
    public function subscriptions()
    {
        return $this->hasMany(CustomerSubscription::class);
    }

    // Relación con las citas del cliente
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
