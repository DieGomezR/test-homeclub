<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'incidence_id',
        'title',
        'description',
        'status',
        'price',
        'assumed_by',
        'notes',
    ];

    public function incidence()
    {
        return $this->belongsTo(Incidence::class);
    }
}
