<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'price',
        'image',
    ];

    public function barbers()
    {
        return $this->belongsToMany(User::class, 'barbers_services', 'service_id', 'user_id');
    }
}
