<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = [
        'content',
        'feeling',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function isFavoritedBy($user)
    {
        return $this->favoritedBy()->where('user_id', $user->id)->exists();
    }
    public function comments()
    {
        return $this->hasMany(QuoteComment::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedBy($user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function getReactionCount($type)
    {
        return $this->reactions()->where('type', $type)->count();
    }

    public function hasReaction($user, $type)
    {
        if (!$user)
            return false;
        return $this->reactions()
            ->where('user_id', $user->id)
            ->where('type', $type)
            ->exists();
    }

    public function scopeSimilarTo($query, $content, $limit = 5)
    {
        // تنظيف النص وتحويله إلى مصفوفة من الكلمات
        $words = collect(explode(' ', preg_replace('/[^\p{L}\s]/u', '', strtolower($content))))
            ->filter(function ($word) {
                return strlen($word) > 3; // تجاهل الكلمات القصيرة
            })
            ->values()
            ->toArray();

        // إنشاء شرط البحث
        $query->where(function ($query) use ($words) {
            foreach ($words as $word) {
                $query->orWhere('content', 'LIKE', '%' . $word . '%');
            }
        });

        return $query->orderBy('created_at', 'desc')
            ->limit($limit);
    }

    public function similarQuotes($limit = 5)
    {
        return self::similarTo($this->content, $limit)
            ->where('id', '!=', $this->id)
            ->get();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'quote_categories');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
    public function someMethod()
    {
        return Quote::inRandomOrder()->first();
    }

    public static function random()
    {
        return self::inRandomOrder()->first();
    }
}
