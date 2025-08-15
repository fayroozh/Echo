<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Events\UserOnlineStatus;
use Carbon\Carbon;

class TrackUserOnlineStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $wasOffline = !Cache::has('user-online-' . $user->id);
            
            // Store user's online status for 5 minutes
            Cache::put('user-online-' . $user->id, true, Carbon::now()->addMinutes(5));
            
            // If user was offline before, broadcast they're now online
            if ($wasOffline) {
                broadcast(new UserOnlineStatus($user, 'online'))->toOthers();
            }
            
            // When user logs out or session expires, mark them as offline
            if (!$request->session()->has('logout_listener_registered')) {
                $request->session()->put('logout_listener_registered', true);
                
                app('events')->listen('auth.logout', function ($event) use ($user) {
                    Cache::forget('user-online-' . $user->id);
                    broadcast(new UserOnlineStatus($user, 'offline'))->toOthers();
                });
            }
        }
        
        return $next($request);
    }
}