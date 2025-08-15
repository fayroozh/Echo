<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quote;

class UserQuoteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $quotes = $user->quotes()->latest()->paginate(10);
        
        return view('quotes.user-quotes', compact('quotes'));
    }
}