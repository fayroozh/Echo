<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Conversation;
use App\Events\MessageSent;
use App\Notifications\NewMessage;

class MessageController extends Controller
{
    public function store(Request $request, Conversation $conversation)
    {
        // تحقق من صحة البيانات
        $request->validate([
            'body' => 'required_without:attachment|string|nullable',
            'attachment' => 'nullable|file|max:5120', // حتى 5 ميجابايت
        ]);

        // تأكد من أن المحادثة موجودة
        if (!$conversation || !$conversation->exists) {
            abort(404, 'المحادثة غير موجودة');
        }

        // تحقق أن المستخدم طرف في المحادثة
        if (!in_array(auth()->id(), [$conversation->user_one_id, $conversation->user_two_id]) && !auth()->user()->is_admin) {
            abort(403, 'غير مصرح لك بالدخول لهذه المحادثة');
        }
        
        // تحديد المستقبل (الطرف الآخر)
        $receiverId = auth()->id() == $conversation->user_one_id
            ? $conversation->user_two_id
            : $conversation->user_one_id;

        $messageData = [
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $receiverId,
            'body' => $request->body,
        ];

        // إذا كان هناك مرفق، خزنه في التخزين العام
        if ($request->hasFile('attachment')) {
            $messageData['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        // إنشاء الرسالة
        $message = Message::create($messageData);

        // تحديث وقت التعديل للمحادثة لتظهر في الأعلى
        $conversation->touch();
        
        // إرسال إشعار للمستخدم الآخر
        $receiver = \App\Models\User::find($receiverId);
        if ($receiver) {
            $receiver->notify(new NewMessage($message));
        }
        
        // بث حدث الرسالة الجديدة إذا كان مدعوماً
        if (class_exists('App\Events\MessageSent')) {
            event(new MessageSent($message));
        }

        // رجع للمستخدم إلى نفس الصفحة
        return back();
    }
}
