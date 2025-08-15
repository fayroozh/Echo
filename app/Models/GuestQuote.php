<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestQuote extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'feeling',
        'status',
        'ip_address',
        
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function comments()
    {
        return $this->hasMany(GuestQuoteComment::class)->with('user')->latest();
    }
}
