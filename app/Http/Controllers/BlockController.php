<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockController extends Controller
{
    public function toggleBlock(User $user)
    {
        // لا يمكن للمستخدم حظر نفسه
        if (Auth::id() === $user->id) {
            return response()->json(['error' => 'لا يمكنك حظر نفسك'], 400);
        }

        $block = Block::where('blocker_id', Auth::id())
            ->where('blocked_id', $user->id)
            ->first();

        if ($block) {
            // إلغاء الحظر
            $block->delete();
            return response()->json(['message' => 'تم إلغاء الحظر بنجاح']);
        } else {
            // إضافة حظر جديد
            Block::create([
                'blocker_id' => Auth::id(),
                'blocked_id' => $user->id
            ]);
            
            // حذف المحادثات الموجودة بين المستخدمين
            $this->removeConversations(Auth::id(), $user->id);
            
            return response()->json(['message' => 'تم حظر المستخدم بنجاح']);
        }
    }
    
    private function removeConversations($userId1, $userId2)
    {
        // البحث عن المحادثات بين المستخدمين وحذفها
        $conversations = Conversation::where(function($query) use ($userId1, $userId2) {
            $query->where('user_one_id', $userId1)
                  ->where('user_two_id', $userId2);
        })->orWhere(function($query) use ($userId1, $userId2) {
            $query->where('user_one_id', $userId2)
                  ->where('user_two_id', $userId1);
        })->get();
        
        foreach ($conversations as $conversation) {
            $conversation->delete(); // سيتم حذف الرسائل تلقائيًا بسبب onDelete('cascade')
        }
    }
}