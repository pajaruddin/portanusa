<?php

namespace App\Mail;

use AppConfiguration;
use App\User;
use App\Order;
use App\Order_product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerOrder extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $order;
    protected $order_products;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     public function __construct(User $user, $order, $order_products)
     {
       $this->user = $user;
       $this->order = $order;
       $this->order_products = $order_products;
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
          'order' => $this->order,
          'order_products' => $this->order_products,
          'primary_domain' => AppConfiguration::primaryDomain()->value
      ];

      return $this->view('mail.customerOrder')
                      ->with($data)
                      ->from("noreply@portanusa.com", "No Reply Portanusa")
                      ->subject("Portanusa - Order Payment");
    }
}
