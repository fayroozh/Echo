<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Like;
use App\Models\QuoteComment;
use App\Models\Reaction;

class ActivitiesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // الحصول على الإعجابات التي قام بها المستخدم
        $likes = $user->likes()->with('quote.user')->latest()->get() ?? collect();
        
        // الحصول على التعليقات على اقتباسات المستخدم
        $comments = QuoteComment::whereHas('quote', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->latest()->get() ?? collect();

        // الحصول على التفاعلات التي قام بها المستخدم
        $reactions = $user->reactions()->with('quote.user')->latest()->get() ?? collect();
        
        return view('activities.index', compact('likes', 'comments', 'reactions'));
    }
}