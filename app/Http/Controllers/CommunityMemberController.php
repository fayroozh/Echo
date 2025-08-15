<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityMemberController extends Controller
{
    public function showRequests($communityId)
    {
        $community = Community::with([
            'members' => function ($q) {
                $q->where('status', 'pending')->with('user');
            }
        ])->findOrFail($communityId);

        // تأكد أن المستخدم هو المالك أو مشرف
        if (auth()->id() !== $community->owner_id /* أو تحقق صلاحيات المشرفين */) {
            abort(403, 'ليس لديك صلاحية الدخول');
        }

        return view('communities.requests', compact('community'));
    }

    public function approveRequest($communityId, $memberId)
    {
        $member = CommunityMember::where('community_id', $communityId)
            ->where('id', $memberId)
            ->firstOrFail();

        // صلاحية المالك أو مشرف
        $community = Community::findOrFail($communityId);
        if (auth()->id() !== $community->owner_id /* أو تحقق صلاحيات المشرفين */) {
            abort(403);
        }

        $member->status = 'approved';
        $member->save();

        return redirect()->back()->with('success', 'تم قبول طلب الانضمام');
    }

    public function rejectRequest($communityId, $memberId)
    {
        $member = CommunityMember::where('community_id', $communityId)
            ->where('id', $memberId)
            ->firstOrFail();

        $community = Community::findOrFail($communityId);
        if (auth()->id() !== $community->owner_id) {
            abort(403);
        }

        $member->status = 'rejected';
        $member->save();

        return redirect()->back()->with('success', 'تم رفض طلب الانضمام');
    }


}

