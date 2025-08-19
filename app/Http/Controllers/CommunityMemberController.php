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

    /**
     * طلب الانضمام للمجتمع
     */
    public function join(Request $request, Community $community)
    {
        $user = Auth::user();

        // التحقق من أن المستخدم ليس عضواً بالفعل
        if ($community->isMember($user)) {
            return back()->with('error', 'أنت عضو في هذا المجتمع بالفعل');
        }

        // التحقق من وجود طلب انضمام معلق
        $existingRequest = CommunityMember::where('community_id', $community->id)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'لديك طلب انضمام معلق بالفعل');
        }

        // إنشاء طلب انضمام
        $status = $community->is_private ? 'pending' : 'approved';

        CommunityMember::create([
            'community_id' => $community->id,
            'user_id' => $user->id,
            'status' => $status,
            'role' => 'student'
        ]);

        $message = $status === 'approved'
            ? 'تم انضمامك للمجتمع بنجاح'
            : 'تم إرسال طلب الانضمام، في انتظار الموافقة';

        return back()->with('success', $message);
    }

    /**
     * مغادرة المجتمع
     */
    public function leave(Request $request, Community $community)
    {
        $user = Auth::user();

        // التحقق من أن المستخدم عضو في المجتمع
        if (!$community->isMember($user)) {
            return back()->with('error', 'أنت لست عضواً في هذا المجتمع');
        }

        // التحقق من أن المستخدم ليس مالك المجتمع
        if ($community->isOwner($user)) {
            return back()->with('error', 'لا يمكن لمالك المجتمع مغادرته');
        }

        // حذف العضوية
        CommunityMember::where('community_id', $community->id)
            ->where('user_id', $user->id)
            ->delete();

        return back()->with('success', 'تم مغادرة المجتمع بنجاح');
    }


}

