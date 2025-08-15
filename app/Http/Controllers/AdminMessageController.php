<?php

namespace App\Http\Controllers;

use App\Models\AdminMessage;
use App\Models\User;
use Illuminate\Http\Request;

class AdminMessageController extends Controller
{
    /**
     * Display a listing of admin messages.
     */
    public function index()
    {
        $messages = AdminMessage::latest()->paginate(15);
        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Show the form for creating a new admin message.
     */
    public function create()
    {
        $users = User::all();
        return view('admin.messages.create', compact('users'));
    }

    /**
     * Store a newly created admin message in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*' => 'exists:users,id',
        ]);

        foreach ($request->recipients as $userId) {
            AdminMessage::create([
                'user_id' => $userId,
                'subject' => $request->subject,
                'message' => $request->message,
                'is_read' => false,
            ]);
        }

        return redirect()->route('admin.messages.index')
            ->with('success', 'تم إرسال الرسالة بنجاح');
    }

    /**
     * Display the specified admin message.
     */
    public function show(AdminMessage $adminMessage)
    {
        return view('admin.messages.show', compact('adminMessage'));
    }

    /**
     * Mark the specified admin message as read.
     */
    public function markAsRead(AdminMessage $adminMessage)
    {
        $adminMessage->update(['is_read' => true]);
        
        return redirect()->route('admin.messages.show', $adminMessage)
            ->with('success', 'تم تحديث حالة القراءة بنجاح');
    }
}