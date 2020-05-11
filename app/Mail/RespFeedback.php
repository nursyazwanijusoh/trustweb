<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RespFeedback extends Mailable
{
    use Queueable, SerializesModels;

    public $fb;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($feedback)
    {
        $this->fb = $feedback;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {


      return $this->subject('Your trUSt Feedback')->markdown('email.feedback', [
        'name' => $this->fb->Sender->name,
        'thef' => $this->fb
      ]);
    }
}
