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
        $pendingCommunities = Community::where('status', 'pending')->latest()->paginate(10);
        return view('admin.community-requests.index', compact('pendingCommunities'));
    }

    /**
     * Display the specified community request.
     */
    public function show(Community $community)
    {
        return view('admin.community-requests.show', compact('community'));
    }

    /**
     * Approve the specified community request.
     */
    public function approve(Community $community)
    {
        $community->update(['status' => 'approved']);
        
        // Notify the community owner
        // You can add notification logic here
        
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