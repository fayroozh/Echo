<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CommunityComment;
use App\Models\CommunityPost;


class CommunityCommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $post = CommunityPost::findOrFail($postId);

        // تحقق العضوية
        $userId = auth()->id();
        $community = $post->community;
        $isMember = $community->members()->where('user_id', $userId)->where('status', 'approved')->exists();

        if (!$isMember) {
            return redirect()->back()->with('error', 'يجب أن تكون عضوًا في المجتمع للتعليق.');
        }

        CommunityComment::create([
            'post_id' => $postId,
            'user_id' => $userId,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'تم إضافة التعليق بنجاح.');
    }
}

