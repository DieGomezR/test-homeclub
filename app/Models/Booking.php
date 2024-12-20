<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'name_property',
        'name_client',
        'date_start',
        'date_end'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }


}
