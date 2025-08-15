<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestQuoteComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_quote_id',
        'user_id',
        'content'
    ];

    public function guestQuote()
    {
        return $this->belongsTo(GuestQuote::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}