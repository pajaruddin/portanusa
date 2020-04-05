<?php

namespace App\Mail;

use AuthUser;
use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderFailed extends Mailable
{
    use Queueable,
        SerializesModels;

    protected $order;
    protected $customer;
   
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, $customer) {
        $this->order = $order;
        $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $data = [
            'order' => $this->order,
            'customer' => $this->customer,
        ];

        return $this->view('mail.orderFailed')
                      ->with($data)
                      ->from("portanusa@gmail.com", "Portanusa")
                      ->subject("Portanusa - Pembayaran Tidak Berhasil");
    }

}