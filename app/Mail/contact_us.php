<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class contact_us extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->email    = $data['email'];
        $this->phone    = $data['phone'];
        $this->title    = $data['title'];
        $this->body     = $data['body'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Mails.contact_us')->with([
            'email' => $this->email,
            'phone' => $this->phone,
            'title' => $this->title,
            'body'  => $this->body,
        ]);
    }
}
