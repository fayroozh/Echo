<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::paginate(10); // 10 تقارير لكل صفحة
        return view('admin.reports', compact('reports'));
    }


    public function reportConversation(Request $request, $conversationId)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $conversation = Conversation::findOrFail($conversationId);

        // التأكد من أن المستخدم جزء من المحادثة
        if (!in_array(Auth::id(), [$conversation->user_one_id, $conversation->user_two_id])) {
            abort(403);
        }

        // إنشاء تقرير جديد
        Report::create([
            'user_id' => Auth::id(),
            'reportable_id' => $conversationId,
            'reportable_type' => Conversation::class,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'تم إرسال البلاغ بنجاح وسيتم مراجعته من قبل المشرفين');
    }

    public function reportMessage(Request $request, $messageId)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $message = Message::findOrFail($messageId);

        // التأكد من أن المستخدم جزء من المحادثة
        $conversation = $message->conversation;
        if (!in_array(Auth::id(), [$conversation->user_one_id, $conversation->user_two_id])) {
            abort(403);
        }

        // إنشاء تقرير جديد
        Report::create([
            'user_id' => Auth::id(),
            'reportable_id' => $messageId,
            'reportable_type' => Message::class,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'تم إرسال البلاغ بنجاح وسيتم مراجعته من قبل المشرفين');
    }
}