<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['user_one_id', 'user_two_id'];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function participants()
    {
        return collect([$this->userOne, $this->userTwo])->filter();
    }

    /**
     * الحصول على المستخدم الآخر في المحادثة
     */
    public function otherUser()
    {
        $currentUserId = auth()->id();
        return $this->user_one_id == $currentUserId ? $this->userTwo : $this->userOne;
    }
}
