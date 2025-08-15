<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Category;

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
        $validated['type'] = $request->is_private == 1 ? 'private' : 'public';
    
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
                $q->where('status', 'approved'); },
            'followers' => function ($q) {
                $q->where('status', 'approved'); },
            'posts' => function ($q) {
                $q->where('status', 'approved'); },
            'postsPending' => function ($q) {
                $q->where('status', 'pending'); },
            'ratings'
        ])->findOrFail($communityId);

        // تحقق صلاحية المالك فقط
        if (auth()->id() !== $community->owner_id) {
            abort(403);
        }

        return view('communities.dashboard', compact('community'));
    }




/**
 * حذف المجتمع المحدد
 */
public function destroy(Community $community)
{
    // التحقق من أن المستخدم هو مالك المجتمع
    if (auth()->id() !== $community->user_id) {
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

}