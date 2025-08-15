<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Conversation;

class InboxController extends Controller
{
    /**
     * Display the user's inbox
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $conversations = auth()->user()->conversations()
            ->with(['participants.user', 'lastMessage'])
            ->get()
            ->map(function ($conversation) {
                $conversation->otherUser = $conversation->otherUser();
                $conversation->unreadCount = $conversation->unreadMessagesCount();
                return $conversation;
            });
        
        return view('inbox.index', compact('conversations'));
    }
    
    /**
     * Start a new conversation with a user
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function startConversation(User $user)
    {
        // Make sure we're not trying to message ourselves
        if ($user->id === auth()->id()) {
            return redirect()->route('inbox.index')
                ->with('error', 'لا يمكنك بدء محادثة مع نفسك');
        }
        
        // Check if conversation already exists
        $conversation = Conversation::whereHas('participants', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->whereHas('participants', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();
        
        // If not, create a new conversation
        if (!$conversation) {
            $conversation = new Conversation();
            $conversation->save();
            
            // Add participants
            $conversation->participants()->createMany([
                ['user_id' => auth()->id()],
                ['user_id' => $user->id]
            ]);
        }
        
        return redirect()->route('chat.show', $conversation->id);
    }
}