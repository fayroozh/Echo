<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;



class User extends Authenticatable
{
    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'bio',
        'birthdate',
        'is_admin', // إضافة هذا
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean', // إضافة هذا
        ];
    }
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
    public function follower()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
    }
    public function getProfileImageAttribute()
    {
        return $this->avatar;
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

    public function isBlockedBy($userId)
    {
        return $this->blockedBy()->where('blocker_id', $userId)->exists();
    }

    public function hasBlocked($userId)
    {
        return $this->blockedUsers()->where('blocked_id', $userId)->exists();
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
    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }





    // Add these methods to your User model

    /**
     * Check if the user is currently online
     *
     * @return bool
     */
    public function isOnline()
    {
        return Cache::has('user-online-' . $this->id);
    }

    /**
     * Get all conversations this user is part of
     */
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_participants');
    }

    /**
     * Get the user's profile image URL
     *
     * @return string
     */
    // Add this method to your User model
    public function getProfileImageUrlAttribute()
    {
        // لو فيه صورة أفتار محفوظة
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // لو فيه صورة profile_image محفوظة
        if ($this->profile_image) {
            return Storage::url($this->profile_image);
        }

        // لو ما في أي صورة، رجع صورة افتراضية مع اسم المستخدم من خدمة ui-avatars
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }
}
