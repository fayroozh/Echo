<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    /**
     * عرض ملف المستخدم
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
    
}