<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quote;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{


    public function toggle(Quote $quote)
    {
        $user = Auth::user();

        if ($quote->isFavoritedBy($user)) {
            $quote->favoritedBy()->detach($user->id);
        } else {
            $quote->favoritedBy()->attach($user->id);
        }

        return back()->with('success', 'تم تحديث التفاعل بنجاح.');
    }

}
