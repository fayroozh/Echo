<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\Reaction;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $quotes = Quote::with(['user', 'likes', 'reactions', 'comments.user']);

        // فلترة حسب الشعور أو التصنيف
        if ($request->filled('feeling')) {
            $quotes->where('feeling', $request->feeling);
        }
        if ($request->filled('category')) {
            $quotes->where('category_id', $request->category);
        }

        $quotes = $quotes->latest()->paginate(10);

        return view('quotes.index', compact('quotes'));
    }

    public function create()
    {
        return view('quotes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
            'image' => 'nullable|image|max:2048',
            'category_id' => 'nullable|integer',
            'feeling' => 'nullable|string|max:255'
        ]);

        $data = $request->only(['content', 'category_id', 'feeling']);
        $data['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('quotes', 'public');
        }

        $quote = Quote::create($data);

        // إعادة التوجيه للمكتبة مع رسالة نجاح
        return redirect()->route('quotes.index')->with('success', 'تم إضافة الاقتباس بنجاح!');
    }

    public function show(Quote $quote)
    {
        $quote->load(['user', 'likes', 'reactions', 'comments.user']);

        $isFollowing = false;

        if (auth()->check() && $quote->user && $quote->user->id !== auth()->id()) {
            $isFollowing = auth()->user()->isFollowing($quote->user);
        }

        // إضافة المتغيرات المطلوبة للواجهة
        $conversations = \App\Models\Conversation::where('user_one_id', auth()->id())
            ->orWhere('user_two_id', auth()->id())
            ->get();

        $users = \App\Models\User::where('id', '!=', auth()->id())->get();

        return view('quotes.show', compact('quote', 'isFollowing', 'conversations', 'users'));
    }


    public function toggleLike(Request $request, Quote $quote)
    {
        $user = auth()->user();

        if ($quote->likes()->where('user_id', $user->id)->exists()) {
            $quote->likes()->where('user_id', $user->id)->delete();
            $liked = false;
        } else {
            $quote->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'count' => $quote->likes()->count()
        ]);
    }

    public function toggleReaction(Request $request, Quote $quote)
    {
        $user = auth()->user();
        $type = $request->input('type');

        if (!$type) {
            return response()->json(['success' => false, 'message' => 'Reaction type required'], 422);
        }

        $quote->reactions()->updateOrCreate(
            ['user_id' => $user->id],
            ['type' => $type]
        );

        return response()->json([
            'success' => true,
            'count' => $quote->reactions()->count()
        ]);
    }

    public function addComment(Request $request, Quote $quote)
    {
        $request->validate([
            'comment' => 'required|string|max:500'
        ]);

        $comment = $quote->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $request->comment  // Changed from $request->content to $request->comment
        ]);

        return response()->json([
            'success' => true,
            'comment' => $comment->load('user')
        ]);
    }

    public function report(Request $request, Quote $quote)
    {
        // ممكن تعمل جدول reports وتخزن فيه
        // هون مؤقتاً فقط استجابة نجاح
        return response()->json(['success' => true]);
    }


    public function favorite(Request $request, Quote $quote)
    {
        auth()->user()->favorites()->toggle($quote->id);
        return response()->json([
            'success' => true,
            'favorited' => auth()->user()->favorites()->where('quote_id', $quote->id)->exists()
        ]);
    }

    public function destroy(Quote $quote)
    {
        $this->authorize('delete', $quote);
        $quote->delete();

        return response()->json(['success' => true]);
    }

    public function downloadImage(Quote $quote)
    {
        if (!$quote->image || !Storage::disk('public')->exists($quote->image)) {
            return abort(404);
        }

        return Storage::disk('public')->download($quote->image, 'quote-image.jpg');
    }

    // دالة عرض المحتوى الطويل مع "عرض المزيد"
    public function getFullContent(Request $request, Quote $quote)
    {
        return response()->json([
            'success' => true,
            'content' => $quote->content
        ]);
    }

    /**
     * عرض اقتباس عشوائي
     */
    public function random()
    {
        $quote = Quote::inRandomOrder()->first();

        if (!$quote) {
            return redirect()->route('quotes.index')->with('error', 'لا توجد اقتباسات متاحة');
        }

        return redirect()->route('quotes.show', $quote);
    }

    /**
     * عرض نموذج تعديل الاقتباس
     */
    public function edit(Quote $quote)
    {
        $this->authorize('update', $quote);

        return view('quotes.edit', compact('quote'));
    }

    /**
     * تحديث الاقتباس
     */
    public function update(Request $request, Quote $quote)
    {
        $this->authorize('update', $quote);

        $request->validate([
            'content' => 'required|string|max:2000',
            'image' => 'nullable|image|max:2048',
            'category_id' => 'nullable|integer',
            'feeling' => 'nullable|string|max:255'
        ]);

        $data = $request->only(['content', 'category_id', 'feeling']);

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا وجدت
            if ($quote->image) {
                Storage::disk('public')->delete($quote->image);
            }
            $data['image'] = $request->file('image')->store('quotes', 'public');
        }

        $quote->update($data);

        return redirect()->route('quotes.show', $quote)->with('success', 'تم تحديث الاقتباس بنجاح!');
    }
}
