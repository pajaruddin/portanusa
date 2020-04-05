<?php

namespace App\Mail;

use AppConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     public function __construct($email)
     {
       $this->email = $email;
     }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

      $data = [
          'email' => $this->email,
          'primary_domain' => AppConfiguration::primaryDomain()->value
      ];

      return $this->view('mail.newsletter')
                      ->with($data)
                      ->from("noreply@portanusa.com", "No Reply Portanusa")
                      ->subject("Portanusa - Newsletter");
    }
}
