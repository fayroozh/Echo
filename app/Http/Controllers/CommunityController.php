<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Category;
use App\Models\Conversation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function index()
    {
        $communities = Community::with(['owner', 'category', 'members', 'followers', 'ratings'])
            ->where('status', 'approved')
            ->paginate(12);

        $categories = Category::all();




        return view('communities.index', compact('communities', 'categories'));
    }



    public function create()
    {
        $categories = Category::all();



        return view('communities.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:communities',
            'description' => 'required|string',
            'idea' => 'required|string',
            'category_id' => 'required|exists:categories,id',


            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_private' => 'required|in:0,1',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('communities', 'public');
            $validated['image'] = $path;
        }

        $validated['owner_id'] = auth()->id();
        $validated['status'] = 'pending';
        $validated['is_private'] = $request->is_private == 1;

        Community::create($validated);

        return redirect()->route('communities.index')->with('success', 'تم إرسال طلب إنشاء المجتمع بنجاح، في انتظار الموافقة.');
    }
    public function show($communityId)
    {
        $community = Community::with([
            'owner',
            'category',
            'posts' => function ($q) {
                $q->where('status', 'approved')->with(['user', 'comments.user']);
            },
            'members' => function ($q) {
                $q->where('status', 'approved');
            },
            'followers' => function ($q) {
                $q->where('status', 'approved');
            },
        ])->findOrFail($communityId);

        $userId = auth()->id();

        // تحقق حالة العضوية والمتابعة
        $isMember = $community->members->contains('user_id', $userId);
        $isFollower = $community->followers->contains('user_id', $userId);

        return view('communities.show', compact('community', 'isMember', 'isFollower'));
    }
    public function dashboard($communityId)
    {
        $community = Community::with([
            'members' => function ($q) {
                $q->where('status', 'approved');
            },
            'followers' => function ($q) {
                $q->where('status', 'approved');
            },
            'posts' => function ($q) {
                $q->where('status', 'approved');
            },
            'postsPending' => function ($q) {
                $q->where('status', 'pending');
            },
            'ratings'
        ])->findOrFail($communityId);

        // تحقق صلاحية المالك فقط
        if (auth()->id() !== $community->owner_id) {
            abort(403);
        }

        // حساب عدد المنشورات المعلقة
        $pendingPostsCount = $community->postsPending()->count();

        return view('communities.dashboard', compact('community', 'pendingPostsCount'));
    }




    /**
     * حذف المجتمع المحدد
     */
    public function destroy(Community $community)
    {
        // التحقق من أن المستخدم هو مالك المجتمع
        if (auth()->id() !== $community->owner_id) {
            return redirect()->route('communities.show', $community)
                ->with('error', 'لا يمكنك حذف هذا المجتمع لأنك لست المالك');
        }

        // حذف جميع المنشورات المرتبطة بالمجتمع
        foreach ($community->posts as $post) {
            // حذف جميع التعليقات المرتبطة بالمنشور
            $post->comments()->delete();

            // حذف جميع ردود الأفعال المرتبطة بالمنشور
            $post->reactions()->delete();

            // حذف المنشور
            $post->delete();
        }

        // حذف جميع طلبات الانضمام للمجتمع
        $community->joinRequests()->delete();

        // حذف جميع أعضاء المجتمع
        $community->members()->detach();

        // حذف المجتمع
        $community->delete();

        return redirect()->route('communities.index')
            ->with('success', 'تم حذف المجتمع بنجاح');
    }

    /**
     * متابعة أو إلغاء متابعة المجتمع
     */
    public function toggleFollow(Community $community)
    {
        $user = auth()->user();

        if ($community->isFollower($user)) {
            // إلغاء المتابعة
            $community->followers()->detach($user->id);
            $message = 'تم إلغاء متابعة المجتمع';
        } else {
            // متابعة المجتمع
            if ($community->is_private) {
                // إذا كان خاص، أضف طلب متابعة معلق
                $community->followers()->attach($user->id, ['status' => 'pending']);
                $message = 'تم إرسال طلب متابعة، في انتظار الموافقة';
            } else {
                // إذا كان عام، تابع مباشرة
                $community->followers()->attach($user->id, ['status' => 'approved']);
                $message = 'تمت متابعة المجتمع بنجاح';
            }
        }

        return back()->with('success', $message);
    }

    /**
     * مراسلة مالك المجتمع
     */
    public function messageOwner(Community $community)
    {
        $user = auth()->user();

        // البحث عن محادثة موجودة
        $conversation = Conversation::where(function ($query) use ($user, $community) {
            $query->where('user_one_id', $user->id)->where('user_two_id', $community->owner_id);
        })->orWhere(function ($query) use ($user, $community) {
            $query->where('user_one_id', $community->owner_id)->where('user_two_id', $user->id);
        })->first();

        // إنشاء محادثة جديدة إذا لم توجد
        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => $user->id,
                'user_two_id' => $community->owner_id
            ]);
        }

        return redirect()->route('conversations.show', $conversation->id);
    }

}