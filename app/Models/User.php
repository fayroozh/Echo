<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'cover_image',
        'bio',
        'birthdate',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    // ==========================
    // علاقات المستخدم
    // ==========================
    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Quote::class, 'favorites')->withTimestamps();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
    }

    public function follows()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function blockedUsers()
    {
        return $this->hasMany(Block::class, 'blocker_id');
    }

    public function blockedBy()
    {
        return $this->hasMany(Block::class, 'blocked_id');
    }

    public function communities()
    {
        return $this->belongsToMany(Community::class, 'community_members')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function posts()
    {
        return $this->hasMany(CommunityPost::class);
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_participants');
    }

    // ==========================
    // دوال مساعدة
    // ==========================
    public function isFollowing(User $user)
    {
        if (!$user || !$user->id) {
            return false;
        }

        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function isBlockedBy($userId)
    {
        return $this->blockedBy()->where('blocker_id', $userId)->exists();
    }

    public function hasBlocked($userId)
    {
        return $this->blockedUsers()->where('blocked_id', $userId)->exists();
    }

    public function isOnline()
    {
        return Cache::has('user-online-' . $this->id);
    }

    // ==========================
    // صور المستخدم
    // ==========================
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }

        return null;
    }
}
