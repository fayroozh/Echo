<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // معالجة صورة الملف الشخصي
        if ($request->hasFile('profile_image')) {
            // حذف الصورة القديمة إذا وجدت
            if ($request->user()->profile_image) {
                Storage::disk('public')->delete($request->user()->profile_image);
            }

            // تخزين الصورة الجديدة
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $request->user()->profile_image = $path;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function updateCover(Request $request)
    {
        $request->validate([
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('cover_image')) {
            // احذف الصورة القديمة إذا وجدت
            if (auth()->user()->cover_image) {
                Storage::disk('public')->delete(auth()->user()->cover_image);
            }

            // احفظ الصورة الجديدة
            $path = $request->file('cover_image')->store('cover-images', 'public');

            auth()->user()->update([
                'cover_image' => $path
            ]);
        }

        return back()->with('status', 'تم تحديث صورة الغلاف بنجاح');
    }
}
