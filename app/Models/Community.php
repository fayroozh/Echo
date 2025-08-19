<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CommunityPost;
use Illuminate\Support\Facades\Auth;
use App\Models\CommunityMember;  // Add this line


class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'idea',
        'image',
        'is_private',
        'status',
        'category_id',
        'owner_id', // هذا هو مالك المجتمع
        'approved_at',
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'approved_at' => 'datetime',
    ];

    // القيم الافتراضية
    protected $attributes = [
        'status' => 'pending',
    ];

    /**
     * علاقة مالك المجتمع
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * علاقة التصنيف
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * علاقة الأعضاء
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'community_members')
            ->withPivot('role', 'status')
            ->withTimestamps();
    }

    /**
     * علاقة المنشورات
     */
    public function posts()
    {
        return $this->hasMany(CommunityPost::class);
    }

    /**
     * علاقة المنشورات المعلقة
     */
    public function postsPending()
    {
        return $this->hasMany(CommunityPost::class)->where('status', 'pending');
    }

    // نطاق المجتمعات الموافق عليها
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // نطاق المجتمعات المعلقة
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // نطاق المجتمعات المرفوضة
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
    public function followers()
    {
        return $this->belongsToMany(User::class, 'community_followers')
            ->withTimestamps();
    }
    public function ratings()
    {
        return $this->hasMany(CommunityRating::class);
    }

    /**
     * Check if the given user is the owner of this community
     */
    public function isOwner($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }
        return $user ? $this->owner_id === $user->id : false;
    }

    /**
     * Check if the given user is a member of this community
     */
    public function isMember($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }
        return $user ? $this->members()->where('user_id', $user->id)->exists() : false;
    }

    /**
     * Check if the given user is following this community
     */
    public function isFollower($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }
        return $user ? $this->followers()->where('user_id', $user->id)->exists() : false;
    }
    public function joinRequests()
    {
        // Change to use CommunityMember model with pending status
        return $this->hasMany(CommunityMember::class)
            ->where('status', 'pending');
    }

    public function hasPendingJoinRequest()
    {
        $userId = Auth::id();

        if (!$userId) {
            return false;
        }

        return $this->members()
            ->where('user_id', $userId)
            ->wherePivot('status', 'pending')
            ->exists();
    }

    public function followRequests()
    {
        return $this->belongsToMany(User::class, 'community_followers')
            ->wherePivot('status', 'pending')
            ->withTimestamps();
    }

    public function hasPendingFollowRequest()
    {
        $userId = Auth::id();

        if (!$userId) {
            return false;
        }

        return $this->followers()
            ->where('user_id', $userId)
            ->wherePivot('status', 'pending')
            ->exists();
    }
    public function hasRatedBy($user)
    {
        if (!$user) {
            return false;
        }

        return $this->ratings()
            ->where('user_id', $user->id)
            ->exists();
    }



    // تم إزالة creator() لأننا نستخدم owner()
}
