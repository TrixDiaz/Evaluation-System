<?php

namespace App\Models;

use Database\Factories\RoomFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    /** @use HasFactory<RoomFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'is_active'
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
