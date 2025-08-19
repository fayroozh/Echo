<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;

class AdminCommunityRequestController extends Controller
{
    /**
     * Display a listing of pending community requests.
     */
    public function index()
    {
        $pendingCommunities = Community::where('status', 'pending')
            ->with('owner') // استخدام owner بدلاً من creator
            ->latest()
            ->paginate(10);

        $activeCommunities = Community::where('status', 'approved')
            ->with('owner') // استخدام owner بدلاً من creator
            ->withCount('members')
            ->latest()
            ->get();

        return view('admin.community-requests.index', compact('pendingCommunities', 'activeCommunities'));
    }


    /**
     * Display the specified community request.
     */
    public function show(Community $community)
    {
        // تحميل علاقة المالك مع المجتمع
        $community->load('owner');
        
        // Fetch pending communities to pass to the view
        $pendingCommunities = Community::where('status', 'pending')
            ->with('owner')
            ->latest()
            ->paginate(10);
            
        $activeCommunities = Community::where('status', 'approved')
            ->with('owner')
            ->withCount('members')
            ->latest()
            ->get();
            
        return view('admin.community-requests.show', compact('community', 'pendingCommunities', 'activeCommunities'));
    }

    /**
     * Approve the specified community request.
     */
    public function approve(Community $community)
    {
        // تحميل العلاقة المناسبة
        $community->load('owner'); // أو 'creator' حسب ما تستخدم
        
        // تحديث حالة المجتمع
        $community->update(['status' => 'approved']);
        
        // إضافة المالك كعضو ومشرف للمجتمع إذا كان موجودًا
        if ($community->owner) { // أو $community->creator
            $community->members()->attach($community->owner->id, ['role' => 'admin', 'status' => 'approved']);
        }

        return redirect()->route('admin.community-requests.index')
            ->with('success', 'تم الموافقة على المجتمع بنجاح');
    }

    /**
     * Reject the specified community request.
     */
    public function reject(Request $request, Community $community)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $community->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Notify the community owner
        // You can add notification logic here

        return redirect()->route('admin.community-requests.index')
            ->with('success', 'تم رفض المجتمع بنجاح');
    }
}
