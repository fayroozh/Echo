<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index()
    {
        // التحقق من أن المستخدم هو سوبر أدمن
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $pendingCommunities = Community::where('status', 'pending')->with('owner')->get();
        $activeCommunities = Community::where('status', 'approved')
            ->withCount('members')
            ->get();

        return view('admin.communities.index', compact('pendingCommunities', 'activeCommunities'));
    }

    public function approve(Community $community)
    {
        // التحقق من أن المستخدم هو سوبر أدمن
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        // تحديث حالة المجتمع إلى نشط
        $community->status = 'approved';
        $community->save();

        // إضافة المؤسس كعضو ومشرف
        $community->members()->attach($community->owner_id, ['role' => 'owner']);

        return redirect()->back()->with('success', 'تمت الموافقة على المجتمع بنجاح');
    }

    public function reject(Community $community)
    {
        // التحقق من أن المستخدم هو سوبر أدمن
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        // تحديث حالة المجتمع إلى مرفوض
        $community->status = 'rejected';
        $community->save();

        return redirect()->back()->with('success', 'تم رفض المجتمع بنجاح');
    }

    public function disable(Community $community)
    {
        // التحقق من أن المستخدم هو سوبر أدمن
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        // تحديث حالة المجتمع إلى معطل
        $community->status = 'rejected';
        $community->save();

        return redirect()->back()->with('success', 'تم تعطيل المجتمع بنجاح');
    }
}