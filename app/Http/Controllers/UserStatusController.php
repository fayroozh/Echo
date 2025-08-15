<?php

namespace App\Http\Controllers;

use App\Events\UserOnlineStatus;
use App\Events\UserTypingStatus;
use App\Models\Conversation;
use App\Models\TypingStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserStatusController extends Controller
{
    public function updateOnlineStatus(Request $request)
    {
        $user = Auth::user();
        $isOnline = $request->is_online;
        
        $user->is_online = $isOnline;
        $user->last_activity = now();
        $user->save();
        
        broadcast(new UserOnlineStatus($user, $isOnline))->toOthers();
        
        return response()->json(['status' => 'success']);
    }
    
    public function updateTypingStatus(Request $request, $conversationId)
    {
        $user = Auth::user();
        $conversation = Conversation::findOrFail($conversationId);
        $isTyping = $request->is_typing;
        
        // التأكد من أن المستخدم جزء من المحادثة
        if (!in_array($user->id, [$conversation->user_one_id, $conversation->user_two_id])) {
            return response()->json(['error' => 'غير مصرح لك بالوصول إلى هذه المحادثة'], 403);
        }
        
        // تحديث حالة الكتابة
        TypingStatus::updateOrCreate(
            ['user_id' => $user->id, 'conversation_id' => $conversation->id],
            ['is_typing' => $isTyping]
        );
        
        broadcast(new UserTypingStatus($user, $conversation, $isTyping))->toOthers();
        
        return response()->json(['status' => 'success']);
    }
}