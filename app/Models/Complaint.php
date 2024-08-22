<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'phone', 'complaint_type', 'message', 'images','status'
    ];

    protected $casts = [
        'images' => 'array',
    ];
}
