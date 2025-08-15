<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Quote;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'users_count' => User::count(),
            'communities_count' => Community::count(),
            'quotes_count' => Quote::count(),
            'pending_communities' => Community::where('status', 'pending')->count(),
            'reports_count' => Report::where('status', 'pending')->count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentCommunities = Community::latest()->take(5)->get();
        $recentReports = Report::latest()->take(5)->get();  // <--- هنا جلبت آخر 5 تقارير

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentCommunities', 'recentReports'));
    }


    /**
     * Display a listing of reports.
     */
    public function reports()
    {
        $reports = Report::with('reporter', 'reportable')->latest()->paginate(15);
        return view('admin.reports', compact('reports'));
    }

    /**
     * Ban the specified user.
     */
    public function banUser(User $user)
    {
        // Prevent banning yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حظر نفسك');
        }

        $user->update(['is_banned' => true]);

        return back()->with('success', 'تم حظر المستخدم بنجاح');
    }

    /**
     * Delete the specified quote.
     */
    public function deleteQuote(Quote $quote)
    {
        $quote->delete();

        return back()->with('success', 'تم حذف الاقتباس بنجاح');
    }
}
