<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * عرض صفحة المستخدمين المتصلين
     */
    public function showOnlineUsers()
    {
        $onlineUsers = User::where('is_online', true)
            ->where('id', '!=', Auth::id())
            ->latest('last_activity')
            ->paginate(20);

        return view('users.online', compact('onlineUsers'));
    }

    /**
     * البحث عن المستخدمين
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('username', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->paginate(20);

        return view('users.search', compact('users', 'query'));
    }

    /**
     * عرض الملف الشخصي للمستخدم
     */
    public function show(User $user)
    {
        $quotes = $user->quotes()->latest()->paginate(10);
        $communities = $user->communities()->latest()->take(5)->get();

        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        return view('profile.show', compact('user', 'quotes', 'communities', 'followersCount', 'followingCount'));
    }

    /**
     * عرض صفحة تعديل الملف الشخصي
     */
    public function edit()
    {
        $user = Auth::user();
        return view('users.edit', compact('user'));
    }

    /**
     * تحديث بيانات المستخدم
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->bio = $validated['bio'] ?? $user->bio;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * عرض المستخدمين المقترحين للمتابعة
     */
    public function suggested()
    {
        $user = Auth::user();

        // الحصول على المستخدمين النشطين الذين لا يتابعهم المستخدم الحالي
        $suggestedUsers = User::whereNotIn('id', function ($query) use ($user) {
            $query->select('following_id')
                ->from('followers')
                ->where('follower_id', $user->id);
        })
            ->where('id', '!=', $user->id)
            ->where('is_online', true)
            ->inRandomOrder()
            ->take(10)
            ->get();

        return view('users.suggested', compact('suggestedUsers'));
    }

    /**
     * متابعة مستخدم
     */
    public function follow(User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->id !== $user->id && !$currentUser->following()->where('following_id', $user->id)->exists()) {
            $currentUser->following()->attach($user->id);

            // إرسال إشعار للمستخدم المتابع
            $user->notify(new \App\Notifications\NewFollower($currentUser));
        }

        return back()->with('success', 'تمت المتابعة بنجاح');
    }

    /**
     * إلغاء متابعة مستخدم
     */
    public function unfollow(User $user)
    {
        $currentUser = Auth::user();

        $currentUser->following()->detach($user->id);

        return back()->with('success', 'تم إلغاء المتابعة بنجاح');
    }

    public function report(Request $request, User $user)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        // Create report
        $report = new \App\Models\UserReport([
            'reporter_id' => auth()->id(),
            'reported_user_id' => $user->id,
            'reason' => $validated['reason'],
            'status' => 'pending'
        ]);

        $report->save();

        return back()->with('success', 'تم إرسال البلاغ بنجاح');
    }

    public function block(User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->id === $user->id) {
            return back()->with('error', 'لا يمكنك حظر نفسك');
        }

        $currentUser->blockedUsers()->attach($user->id);

        return back()->with('success', 'تم حظر المستخدم بنجاح');
    }

    public function unblock(User $user)
    {
        auth()->user()->blockedUsers()->detach($user->id);

        return back()->with('success', 'تم إلغاء حظر المستخدم بنجاح');
    }
}
