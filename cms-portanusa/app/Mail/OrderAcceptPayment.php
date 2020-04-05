<?php

namespace App\Mail;

use AuthUser;
use App\Order;
use App\Libraries\AppConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderAcceptPayment extends Mailable
{
    use Queueable,
        SerializesModels;

    protected $order;
    protected $customer;
    protected $orderproduct;
    protected $price;
   
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, $customer, $orderproduct, $price) {
        $this->order = $order;
        $this->customer = $customer;
        $this->orderproduct = $orderproduct;
        $this->price = $price;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $asset_domain = AppConfiguration::assetPortalDomain()->value;
        $path_tax = AppConfiguration::taxPath()->value;

        $file_tax = $asset_domain ."/". $path_tax . "/";
        if($this->order->file_tax != null){
            $file_tax .= $this->order->file_tax; 
        }

        $data = [
            'order' => $this->order,
            'customer' => $this->customer,
            'order_products' => $this->orderproduct,
            'price' => $this->price
        ];

        return $this->view('mail.orderPayment')
                      ->with($data)
                      ->from("portanusa@gmail.com", "Portanusa")
                      ->attach($file_tax)
                      ->subject("Portanusa - Pembayaran Berhasil");
    }

}