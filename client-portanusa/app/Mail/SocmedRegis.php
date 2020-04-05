<?php

namespace App\Mail;

use AppConfiguration;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SocmedRegis extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     public function __construct(User $user)
     {
       $this->user = $user;
     }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

      $data = [
          'name' => $this->user->first_name,
          'email' => $this->user->email,
          'primary_domain' => AppConfiguration::primaryDomain()->value
      ];

      return $this->view('mail.socmedRegis')
                      ->with($data)
                      ->from("noreply@portanusa.com", "No Reply Portanusa")
                      ->subject("Portanusa - Socmed Registration");
    }
}
