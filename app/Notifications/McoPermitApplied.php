<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class McoPermitApplied extends Notification
{
    use Queueable;

    protected $mco_travel_req;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($mcoreq)
    {
      $this->mco_travel_req = $mcoreq;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
      $url = route('notify.read', ['nid' => $this->id]);
      return (new MailMessage)
        ->subject('trUSt - MCO Travel Permit application')
        ->markdown('email.mco.applied', [
          'url' => $url,
          'name' => $this->mco_travel_req->requestor->name
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
      return [
        'id' => null,
        'param' => '',
        'route_name' => 'mco.ackreqs',
        'text' => 'MCO Permit: ' . $this->mco_travel_req->requestor->name,
        'icon' => 'fa fa-users'
      ];
    }
}
