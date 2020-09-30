<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDiaryRpt extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($cgrp)
    {
      $this->cgrp = $cgrp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->subject('Your trUSt Diary report is ready')
          ->markdown('email.sendreport', [
            'url' => route('report.gwd.summary')
          ])
          ->attach(storage_path("app/reports/".$this->cgrp->extra_info));
    }
}
