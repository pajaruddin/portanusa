<?php

namespace App\Mail;

use App\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivationCustomer extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     public function __construct(Customer $user)
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
          'email' => $this->user->email
      ];

      return $this->view('mail.activationCustomer')
                      ->with($data)
                      ->from("portanusa@gmail.com", "Portanusa")
                      ->subject("Portanusa - Aktivasi Akun");
    }
}
