<?php

namespace App\Mail;

use App\Models\YIC_user;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordReissueMail extends Mailable
{
    use Queueable, SerializesModels;

    public YIC_user $user;
    public string $newPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(YIC_user $user, string $newPassword)
    {
        $this->user = $user;
        $this->newPassword = $newPassword;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('【YIC】ID・パスワード再発行のお知らせ')
            ->view('emails.password_reissue');
    }
}
