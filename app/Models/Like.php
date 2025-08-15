<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Like extends Model
{
    use HasFactory;

    // السماح للحقول التالية بالكتابة الجماعية
    protected $fillable = [
        'user_id',
        'quote_id',
        // أضف حقول أخرى إذا كنت تحتاجها
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }
}
