<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarberSchedule extends Model
{
    protected $fillable = [
        'barber_id', 'day_of_week', 'start_time', 'end_time'
    ];

    /**
     * Get the barber that owns the schedule.
     */
    public function barberProfile()
    {
        return $this->belongsTo(Barber::class, 'barber_id');
    }
    
    public function scopeForBarberAndDay($query, $barberId, $dayOfWeek)
    {
        return $query->where('barber_id', $barberId)
                    ->where('day_of_week', $dayOfWeek);
    }
}
