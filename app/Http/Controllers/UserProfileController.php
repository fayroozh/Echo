<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    /**
     * عرض ملف المستخدم
     */


    public function show(User $user)
    {
        $currentUser = Auth::user();

        $isFollowing = $currentUser ? $currentUser->isFollowing($user) : false;

        // عدد المتابعين وعدد المتابعين الذين يتابعهم المستخدم
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        // اقتباسات المستخدم
        $quotes = $user->quotes()->with(['likes', 'reactions', 'comments'])->latest()->paginate(10);

        // مجتمعات المستخدم
        $communities = $user->communities()->with(['pivot', 'members'])->latest()->take(5)->get();
        
        // إضافة عدد الأعضاء لكل مجتمع
        foreach ($communities as $community) {
            $community->members_count = $community->members()->count();
        }

        // Pass the user ID to the view so it can be used in route generation
        return view('users.show', compact('user', 'isFollowing', 'followersCount', 'followingCount', 'quotes', 'communities'));
    }

    /**
     * عرض ملف المستخدم الحالي
     */
    public function showCurrent()
    {
        $user = Auth::user();
        return $this->show($user);
    }
}
