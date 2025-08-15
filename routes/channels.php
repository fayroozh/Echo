<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('notifications.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// قناة حالة المستخدم (متصل الآن)
// هذه قناة عامة يمكن لأي مستخدم مصادق الاستماع إليها
Broadcast::channel('user-status', function ($user) {
    return $user ? true : false;
});

// قناة المحادثة الخاصة (يكتب الآن)
// يمكن فقط للمستخدمين المشاركين في المحادثة الاستماع إليها
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = \App\Models\Conversation::find($conversationId);
    if (!$conversation) return false;
    
    return in_array($user->id, [$conversation->user_one_id, $conversation->user_two_id]);
});