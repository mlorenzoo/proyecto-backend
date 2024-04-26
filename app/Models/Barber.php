<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barber extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'bio',
        'experience',
        'specialties',
        'pics',
    ];

    /**
     * Get the user associated with the barber.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
