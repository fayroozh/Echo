<?php

namespace App\Http\Controllers;

use App\Models\GuestQuote;
use App\Models\GuestQuoteComment;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class GuestQuoteController extends Controller // تأكد من وجود extends Controller هنا
{
    use AuthorizesRequests;
    public function index()
    {
        $guestQuotes = GuestQuote::where('status', 'approved')
            ->with('comments.user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('guest-quotes.index', compact('guestQuotes'));
    }

    public function create()
    {
        return view('guest-quotes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'feeling' => 'nullable|string|max:50'
        ]);

        $guestQuote = new GuestQuote([
            'content' => $validated['content'],
            'feeling' => $validated['feeling'],
            'ip_address' => $request->ip(),
            'user_id' => auth()->check() ? auth()->id() : null // تخزين معرف المستخدم إذا كان مسجلاً
        ]);

        $guestQuote->save();

        return redirect()->back()->with('success', 'تم إرسال مشاعرك بنجاح وسيتم مراجعتها قريباً.');
    }

    public function addComment(Request $request, GuestQuote $guestQuote)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:500'
        ]);

        GuestQuoteComment::create([
            'guest_quote_id' => $guestQuote->id,
            'user_id' => auth()->id(),
            'content' => $validated['comment']
        ]);

        return redirect()->back()->with('success', 'تم إضافة تعليقك بنجاح!');
    }

    public function manage()
    {
        $this->authorize('manage-guest-quotes');
        $guestQuotes = GuestQuote::orderBy('created_at', 'desc')->paginate(20);
        return view('guest-quotes.manage', compact('guestQuotes'));
    }

    public function updateStatus(Request $request, GuestQuote $guestQuote)
    {
        $this->authorize('manage-guest-quotes');
        
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $guestQuote->update(['status' => $validated['status']]);

        if ($validated['status'] === 'approved') {
            // إنشاء اقتباس جديد من الاقتباس المقترح
            // لكن بدون ربطه بالمشرف، بل جعله مجهول المصدر
            Quote::create([
                'content' => $guestQuote->content,
                'feeling' => $guestQuote->feeling,
                'user_id' => $guestQuote->user_id ?? auth()->id(), // استخدام معرف المستخدم الأصلي إذا كان موجوداً
                'is_anonymous' => true // إضافة حقل جديد لتحديد أن هذا اقتباس مجهول المصدر
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث حالة الاقتباس بنجاح.');
    }
}
