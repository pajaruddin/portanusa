<?php

namespace App\Mail;

use AuthUser;
use App\User;
use App\Libraries\AppConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRegister extends Mailable
{
    use Queueable,
        SerializesModels;

    protected $user;
    protected $password;
   
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $password) {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $data = [
            'first_name' => $this->user->first_name,
            'created_name' => AuthUser::fullName(),
            'portal_login_domain' => AppConfiguration::cmsDomain()->value . '/login',
            'email' => $this->user->email,
            'password' => $this->password
        ];

        return $this->view('mail.userRegister')
                      ->with($data)
                      ->from("portanusa@gmail.com", "Portanusa")
                      ->subject("Portanusa - Admin Akun");
    }

}