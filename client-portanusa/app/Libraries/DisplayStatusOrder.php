<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Auth;

use App\Order_status;

class DisplayStatusOrder {

    public static function getStatus($number) {
        $message = "";
        $label = "";
        $order_status = Order_status::find($number);

        $highlight = $order_status->highlight_status;
        $message = $order_status->status;
        $label = $order_status->label;

        $status = array(
            "highlight" => $highlight,
            "message" => $message,
            "label" => $label
        );

        return $status;
    }

}
