<?php

namespace App\Http\Controllers;
use App\Models\Community;
use App\Models\CommunityRating;
use Illuminate\Http\Request;

class CommunityRatingController extends Controller
{
    public function store(Request $request, $communityId)
    {
        $request->validate([
            'stars' => 'required|integer|min:1|max:5',
        ]);

        $community = Community::findOrFail($communityId);

        $userId = auth()->id();

        // تحقق هل المستخدم قيم المجتمع مسبقاً
        $existing = CommunityRating::where('community_id', $communityId)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $existing->stars = $request->stars;
            $existing->save();
        } else {
            CommunityRating::create([
                'community_id' => $communityId,
                'user_id' => $userId,
                'stars' => $request->stars,
            ]);
        }

        return redirect()->back()->with('rating_success', 'تم إرسال تقييمك بنجاح.');
    }
}
