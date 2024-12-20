<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidence extends Model
{

    use HasFactory;

    protected $fillable = [
        'booking_id',
        'description',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

}
