<?php

namespace App\Mail;

use App\Models\Community;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommunityRequestApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $community;

    public function __construct(Community $community)
    {
        $this->community = $community;
    }

    public function build()
    {
        return $this->subject(__('تم قبول طلب إنشاء المجتمع'))
            ->markdown('emails.community-request-approved');
    }
}