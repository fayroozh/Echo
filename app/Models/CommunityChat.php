<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'community_id',
        'user_id',
        'message',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * علاقة المجتمع
     */
    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    /**
     * علاقة المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * نطاق الرسائل غير المقروءة
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * نطاق الرسائل المقروءة
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }
}

