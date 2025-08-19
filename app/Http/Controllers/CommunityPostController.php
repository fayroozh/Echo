<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityPost;
use Illuminate\Http\Request;

class CommunityPostController extends Controller
{
    public function create($communityId)
    {
        $community = Community::findOrFail($communityId);

        // تحقق العضوية أو السماح فقط للطلاب أو الأعضاء فقط؟
        // مثلاً يمكن السماح فقط للأعضاء أو أي مسجل دخول حسب متطلباتك
        if (!$community->members()->where('user_id', auth()->id())->where('status', 'approved')->exists()) {
            return redirect()->back()->with('error', 'يجب أن تكون عضوًا في المجتمع لإضافة منشور.');
        }

        return view('community-posts.create', compact('community'));
    }

    public function store(Request $request, $communityId)
    {
        $community = Community::findOrFail($communityId);

        if (!$community->members()->where('user_id', auth()->id())->where('status', 'approved')->exists()) {
            return redirect()->back()->with('error', 'يجب أن تكون عضوًا في المجتمع لإضافة منشور.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:3000',
        ]);

        CommunityPost::create([
            'community_id' => $community->id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'status' => 'pending', // بانتظار المراجعة
        ]);

        return redirect()->route('communities.show', $community->id)->with('success', 'تم إرسال المنشور للمراجعة.');
    }

    public function show($communityId, $postId)
    {
        $community = Community::findOrFail($communityId);
        $post = CommunityPost::with('user')->where('community_id', $communityId)
            ->where('id', $postId)
            ->where('status', 'approved')
            ->firstOrFail();

        return view('community-posts.show', compact('community', 'post'));
    }
    public function pending($communityId)
    {
        $community = Community::with([
            'posts' => function ($q) {
                $q->where('status', 'pending')->with('user');
            }
        ])->findOrFail($communityId);

        if (auth()->id() !== $community->owner_id /* أو تحقق صلاحيات المشرفين */) {
            abort(403);
        }

        return view('community-posts.pending', compact('community'));
    }

    public function approvePost($communityId, $postId)
    {
        $post = CommunityPost::where('community_id', $communityId)
            ->where('id', $postId)
            ->firstOrFail();

        $community = Community::findOrFail($communityId);
        if (auth()->id() !== $community->owner_id) {
            abort(403);
        }

        $post->status = 'approved';
        $post->save();

        return redirect()->back()->with('success', 'تم قبول المنشور');
    }

    public function rejectPost($communityId, $postId)
    {
        $post = CommunityPost::where('community_id', $communityId)
            ->where('id', $postId)
            ->firstOrFail();

        $community = Community::findOrFail($communityId);
        if (auth()->id() !== $community->owner_id) {
            abort(403);
        }

        $post->status = 'rejected';
        $post->save();

        return redirect()->back()->with('success', 'تم رفض المنشور');
    }

}

