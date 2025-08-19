<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * متابعة أو إلغاء متابعة مستخدم
     */
    public function toggleFollow(User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->isFollowing($user)) {
            $currentUser->following()->detach($user->id);
            return back()->with('success', 'تم إلغاء المتابعة بنجاح');
        }

        $currentUser->following()->attach($user->id);
        return back()->with('success', 'تمت المتابعة بنجاح');
    }
    public function toggle(User $user)
    {
        $authUser = Auth::user();

        if ($authUser->isFollowing($user)) {
            // إذا المتابع، الغي المتابعة
            $authUser->following()->detach($user->id);
            $message = 'تم إلغاء المتابعة بنجاح';
        } else {
            // إذا غير متابع، تابع
            $authUser->following()->attach($user->id);

            // إرسال إشعار للمستخدم المتابع (إذا كان موجود)
            try {
                if (class_exists('\App\Notifications\NewFollower')) {
                    $user->notify(new \App\Notifications\NewFollower($authUser));
                }
            } catch (\Exception $e) {
                // تجاهل الأخطاء في الإشعارات
            }

            $message = 'تمت المتابعة بنجاح';
        }

        return back()->with('success', $message);
    }

    /**
     * عرض قائمة المتابِعين
     */
    public function followers($userId = null)
    {
        $user = $userId ? User::findOrFail($userId) : Auth::user();
        $followers = $user->followers()->with('follower')->latest()->paginate(20);

        return view('follows.followers', compact('user', 'followers'));
    }

    /**
     * عرض قائمة المتابَعين
     */
    public function following($userId = null)
    {
        $user = $userId ? User::findOrFail($userId) : Auth::user();
        $following = $user->following()->with('following')->latest()->paginate(20);

        return view('follows.following', compact('user', 'following'));
    }

    /**
     * عرض قائمة المتابعين للمستخدم الحالي
     */
    public function myFollowers()
    {
        $user = Auth::user();
        $followers = $user->followers()->latest()->paginate(20);

        return view('follows.followers', compact('user', 'followers'));
    }

    /**
     * عرض قائمة المتابَعين للمستخدم الحالي
     */
    public function myFollowing()
    {
        $user = Auth::user();
        $following = $user->following()->latest()->paginate(20);

        return view('follows.following', compact('user', 'following'));
    }
}