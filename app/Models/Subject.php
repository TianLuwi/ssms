<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_code',
        'subject_name',
        'description',
        'units',
        'semester',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
