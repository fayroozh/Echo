<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityFollower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityFollowerController extends Controller
{
    public function follow(Request $request, $communityId)
    {
        $userId = auth()->id();
        $community = Community::findOrFail($communityId);

        $exists = CommunityFollower::where('community_id', $communityId)
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'أنت بالفعل تتابع هذا المجتمع');
        }

        if ($community->privacy === 'public') {
            CommunityFollower::create([
                'community_id' => $communityId,
                'user_id' => $userId,
            ]);

            return redirect()->back()->with('success', 'تابعت المجتمع بنجاح');
        } else {
            return redirect()->back()->with('error', 'هذا المجتمع خاص ويحتاج موافقة للمتابعة');
        }
    }
    public function approveFollower($communityId, $followerId)
    {
        $follower = CommunityFollower::where('community_id', $communityId)
            ->where('id', $followerId)
            ->firstOrFail();

        $community = Community::findOrFail($communityId);
        if (auth()->id() !== $community->owner_id) {
            abort(403);
        }

        // مثال: هنا ممكن تضيف حالة جديدة في community_followers مثل "pending" و "approved"
        // حاليا سنفترض وجود عمود status, وإذا ما موجود نحتاج تضيفه بالمايغريشن

        $follower->status = 'approved';
        $follower->save();

        return redirect()->back()->with('success', 'تم قبول طلب المتابعة');
    }

    public function rejectFollower($communityId, $followerId)
    {
        $follower = CommunityFollower::where('community_id', $communityId)
            ->where('id', $followerId)
            ->firstOrFail();

        $community = Community::findOrFail($communityId);
        if (auth()->id() !== $community->owner_id) {
            abort(403);
        }

        $follower->status = 'rejected';
        $follower->save();

        return redirect()->back()->with('success', 'تم رفض طلب المتابعة');
    }


}

