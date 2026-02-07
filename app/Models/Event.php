<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'starts_at',
        'capacity',
        'seats_taken',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'capacity' => 'integer',
        'seats_taken' => 'integer',
    ];

    public function hasAvailableSeats()
    {
        return $this->seats_taken < $this->capacity;
    }

    public function availableSeats()
    {
        return $this->capacity - $this->seats_taken;
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
