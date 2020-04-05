<?php

namespace App\Mail;

use AppConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $full_name;
    protected $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     public function __construct($full_name, $email)
     {
       $this->email = $email;
       $this->full_name = $full_name;
     }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

      $data = [
          'full_name' => $this->full_name,
          'email' => $this->email,
          'primary_domain' => AppConfiguration::primaryDomain()->value
      ];

      return $this->view('mail.inquiry')
                      ->with($data)
                      ->from("noreply@portanusa.com", "No Reply Portanusa")
                      ->subject("Portanusa - Inquiry");
    }
}
