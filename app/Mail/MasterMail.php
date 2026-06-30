<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MasterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mail_subject;
    public $mail_body;

    public function __construct($mail_body)
    {
        $this->mail_body        = $mail_body;
    }

    public function build()
    {
        return $this->subject('Thông tin đăng ký tài khoản FITLIFE thành công!')
                    ->view('view_mail_register')
                    ->with([
                        'data' => $this->mail_body,
                    ]);
    }
}
