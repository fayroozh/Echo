<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

class ConversationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $blockedUsers = Auth::user()->blockedUsers()->pluck('blocked_id')->toArray();
        $blockedBy = Auth::user()->blockedBy()->pluck('blocker_id')->toArray();
        $allBlocked = array_merge($blockedUsers, $blockedBy);

        $conversations = Conversation::where(function ($query) use ($userId, $allBlocked) {
            $query->where('user_one_id', $userId)
                ->whereNotIn('user_two_id', $allBlocked);
        })->orWhere(function ($query) use ($userId, $allBlocked) {
            $query->where('user_two_id', $userId)
                ->whereNotIn('user_one_id', $allBlocked);
        })
            ->with([
                'messages' => function ($q) {
                    $q->latest()->limit(1);
                },
                'userOne',
                'userTwo'
            ])
            ->latest('updated_at')
            ->get();

        return view('conversations.index', compact('conversations', 'userId'));
    }

    // إضافة دالة بدء المحادثة
    public function start($userId)
    {
        $currentUserId = Auth::id();
        $targetUser = User::findOrFail($userId);

        // التحقق من عدم محاولة المراسلة مع النفس
        if ($currentUserId == $userId) {
            return redirect()->back()->with('error', 'لا يمكنك مراسلة نفسك');
        }

        // التحقق من عدم وجود حظر
        $isBlocked = Auth::user()->blockedUsers()->where('blocked_id', $userId)->exists() ||
            Auth::user()->blockedBy()->where('blocker_id', $userId)->exists();

        if ($isBlocked) {
            return redirect()->back()->with('error', 'لا يمكنك مراسلة هذا المستخدم');
        }

        // البحث عن محادثة موجودة
        $conversation = Conversation::where(function ($query) use ($currentUserId, $userId) {
            $query->where('user_one_id', $currentUserId)->where('user_two_id', $userId);
        })->orWhere(function ($query) use ($currentUserId, $userId) {
            $query->where('user_one_id', $userId)->where('user_two_id', $currentUserId);
        })->first();

        // إنشاء محادثة جديدة إذا لم توجد
        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => $currentUserId,
                'user_two_id' => $userId
            ]);
        }

        return redirect()->route('conversations.show', $conversation->id);
    }

    public function show($id)
    {
        $conversation = Conversation::with('messages.sender')->findOrFail($id);

        // تأكد إن المستخدم داخل المحادثة
        if (!in_array(auth()->id(), [$conversation->user_one_id, $conversation->user_two_id])) {
            abort(403);
        }

        return view('conversations.show', compact('conversation'));
    }

    public function startConversation($userId)
    {
        $authUserId = auth()->id();

        if ($authUserId == $userId) {
            return redirect()->back()->with('error', 'لا يمكنك مراسلة نفسك.');
        }

        // تحقق هل توجد محادثة مسبقة بين المستخدمين
        $conversation = Conversation::whereHas('participants', function ($q) use ($authUserId) {
            $q->where('user_id', $authUserId);
        })->whereHas('participants', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->first();

        if (!$conversation) {
            // إنشاء محادثة جديدة
            $conversation = Conversation::create();

            // إضافة المشاركين (تحتاج جدول participants مثلا)
            $conversation->participants()->createMany([
                ['user_id' => $authUserId],
                ['user_id' => $userId],
            ]);
        }

        // تحويل المستخدم إلى صفحة المحادثة (تعديل حسب مسار محادثاتك)
        return redirect()->route('conversations.show', $conversation->id);
    }
}
