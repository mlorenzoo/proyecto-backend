<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarbersService extends Model
{
    protected $fillable = ['user_id', 'service_id'];

    // Relación con el barbero
    public function barber()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el servicio
    public function service()
    {
        return $this->belongsTo(Service::class);
    }}
