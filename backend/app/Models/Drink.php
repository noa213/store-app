<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drink extends Model
{
    /** @use HasFactory<\Database\Factories\DrinkFactory> */
    use HasFactory;

    
    protected $fillable = [
        'name',
        'ml',
        'user_id',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
