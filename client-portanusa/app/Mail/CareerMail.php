<?php

namespace App\Mail;

use AppConfiguration;
use App\Career_applicant;
use App\Career_post;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CareerMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $career_applicant;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Career_applicant $career_applicant)
    {
        $this->career_applicant = $career_applicant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $career_post = Career_post::find($this->career_applicant->career_post_id);

        $data = [
            'full_name' => $this->career_applicant->full_name,
            'position' => $career_post->position,
            'primary_domain' => AppConfiguration::primaryDomain()->value
        ];

        return $this->view('mail.applicant')
                        ->with($data)
                        ->from("noreply@portanusa.com", "No Reply Portanusa")
                        ->subject("Portanusa - Career ". $career_post->position);
    }
}
