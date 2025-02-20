<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeanProfessorList extends Model
{
    use HasFactory;

    protected $table = 'user_user';

    public function dean(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function professor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'child_id');
    }
}
