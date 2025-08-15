<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteComment extends Model
{
    protected $fillable = [
        'quote_id',
        'user_id',
        'comment'
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
