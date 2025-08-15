<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * عرض قائمة الإشعارات للمستخدم الحالي
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    /**
     * تحديث حالة الإشعار إلى مقروء
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        // التحقق من أن الإشعار ينتمي للمستخدم الحالي
        if ($notification->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'غير مصرح لك بتنفيذ هذه العملية');
        }
        
        $notification->read = true;
        $notification->save();
        
        return redirect()->back()->with('success', 'تم تحديث حالة الإشعار');
    }

    /**
     * تحديث حالة جميع الإشعارات إلى مقروءة
     */
    public function markAllAsRead()
    {
        Auth::user()->notifications()->update(['read' => true]);
        return redirect()->back()->with('success', 'تم تحديث حالة جميع الإشعارات');
    }

    /**
     * حذف إشعار
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        
        // التحقق من أن الإشعار ينتمي للمستخدم الحالي
        if ($notification->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'غير مصرح لك بتنفيذ هذه العملية');
        }
        
        $notification->delete();
        
        return redirect()->back()->with('success', 'تم حذف الإشعار بنجاح');
    }
    
    /**
     * إنشاء إشعار جديد
     */
    public static function createNotification($userId, $content, $type, $relatedId = null)
    {
        $notification = Notification::create([
            'user_id' => $userId,
            'content' => $content,
            'type' => $type,
            'related_id' => $relatedId,
            'read' => false
        ]);
        
        // بث الإشعار في الوقت الحقيقي
        event(new NewNotification($notification));
        
        return $notification;
    }
}