<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityChatController extends Controller
{
    /**
     * عرض محادثة المجتمع
     */
    public function index(Community $community)
    {
        // التحقق من أن المستخدم عضو في المجتمع
        if (!$community->isMember(Auth::user())) {
            abort(403, 'يجب أن تكون عضواً في المجتمع للوصول للمحادثة');
        }

        $messages = CommunityChat::where('community_id', $community->id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        return view('communities.chat', compact('community', 'messages'));
    }

    /**
     * إرسال رسالة جديدة
     */
    public function store(Request $request, Community $community)
    {
        // التحقق من أن المستخدم عضو في المجتمع
        if (!$community->isMember(Auth::user())) {
            abort(403, 'يجب أن تكون عضواً في المجتمع لإرسال رسائل');
        }

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $chat = CommunityChat::create([
            'community_id' => $community->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_read' => false
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $chat->load('user')
            ]);
        }

        return back()->with('success', 'تم إرسال الرسالة بنجاح');
    }

    /**
     * تحديث حالة الرسائل كمقروءة
     */
    public function markAsRead(Community $community)
    {
        if (!$community->isMember(Auth::user())) {
            abort(403);
        }

        CommunityChat::where('community_id', $community->id)
            ->where('user_id', '!=', Auth::id())
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * الحصول على الرسائل الجديدة
     */
    public function getNewMessages(Community $community, Request $request)
    {
        if (!$community->isMember(Auth::user())) {
            abort(403);
        }

        $lastMessageId = $request->get('last_message_id', 0);

        $messages = CommunityChat::where('community_id', $community->id)
            ->where('id', '>', $lastMessageId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'messages' => $messages,
            'count' => $messages->count()
        ]);
    }
}

