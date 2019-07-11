<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $rejecteduser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($rejusr)
    {
        $this->rejecteduser = $rejusr;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('trUSt registration ' . $this->rejecteduser->action)->view('email.regreject');
    }
}
