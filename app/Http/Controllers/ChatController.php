<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    


// Add these methods to your ChatController

public function markAsRead($conversationId)
{
    $conversation = Conversation::findOrFail($conversationId);
    
    // Make sure user is part of this conversation
    if (!$conversation->participants()->where('user_id', auth()->id())->exists()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    // Mark all messages from other user as read
    $conversation->messages()
        ->where('user_id', '!=', auth()->id())
        ->where('is_read', false)
        ->update(['is_read' => true]);
    
    // Broadcast that messages have been read
    $messages = $conversation->messages()
        ->where('user_id', '!=', auth()->id())
        ->where('is_read', true)
        ->get();
    
    foreach ($messages as $message) {
        broadcast(new MessageRead($message))->toOthers();
    }
    
    return response()->json(['success' => true]);
}

public function typing(Request $request)
{
    $request->validate([
        'conversation_id' => 'required|exists:conversations,id',
    ]);
    
    $conversation = Conversation::findOrFail($request->conversation_id);
    
    // Make sure user is part of this conversation
    if (!$conversation->participants()->where('user_id', auth()->id())->exists()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    // Get the other user in the conversation
    $otherUser = $conversation->otherUser();
    
    // Broadcast typing event
    broadcast(new UserTyping(auth()->user(), $conversation->id))->toOthers();
    
    return response()->json(['success' => true]);
}

public function stoppedTyping(Request $request)
{
    $request->validate([
        'conversation_id' => 'required|exists:conversations,id',
    ]);
    
    $conversation = Conversation::findOrFail($request->conversation_id);
    
    // Make sure user is part of this conversation
    if (!$conversation->participants()->where('user_id', auth()->id())->exists()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    // Get the other user in the conversation
    $otherUser = $conversation->otherUser();
    
    // Broadcast stopped typing event
    broadcast(new UserStoppedTyping(auth()->user(), $conversation->id))->toOthers();
    
    return response()->json(['success' => true]);
}
}