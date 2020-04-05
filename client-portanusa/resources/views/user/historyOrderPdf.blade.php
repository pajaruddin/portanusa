<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->invoice_no }}</title>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        @import url("https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,700&subset=cyrillic,cyrillic-ext,latin,greek-ext,greek,latin-ext,vietnamese");
        html, body, div, span, applet, object, iframe,
        h1, h2, h3, h4, h5, h6, p, blockquote, pre,
        a, abbr, acronym, address, big, cite, code,
        del, dfn, em, img, ins, kbd, q, s, samp,
        small, strike, strong, sub, sup, tt, var,
        b, u, i, center,
        dl, dt, dd, ol, ul, li,
        fieldset, form, label, legend,
        table, caption, tbody, tfoot, thead, tr, th, td,
        article, aside, canvas, details, embed,
        figure, figcaption, footer, header, hgroup,
        menu, nav, output, ruby, section, summary,
        time, mark, audio, video {
        margin: 0;
        padding: 0;
        border: 0;
        font: inherit;
        font-size: 100%;
        vertical-align: baseline;
        }

        html {
        line-height: 1;
        }

        ol, ul {
        list-style: none;
        }

        table {
          width: 100%;
          border-collapse: collapse;
          border-spacing: 0;
          margin-bottom: 20px;
        }

        caption, th, td {
        text-align: left;
        font-weight: normal;
        vertical-align: middle;
        background: #EEEEEE;
        border-bottom: 1px solid #FFFFFF;
        }

        q, blockquote {
        quotes: none;
        }
        q:before, q:after, blockquote:before, blockquote:after {
        content: "";
        content: none;
        }

        a img {
        border: none;
        }

        article, aside, details, figcaption, figure, footer, header, hgroup, main, menu, nav, section, summary {
        display: block;
        }
        html, body {
        /* MOVE ALONG, NOTHING TO CHANGE HERE! */
        }
        .clearfix {
        display: block;
        clear: both;
        }

        .hidden {
        display: none;
        }

        b, strong, .bold {
        font-weight: bold;
        }

        #container {
        font: normal 13px/1.4em 'Open Sans', Sans-serif;
        margin: 0 auto;
        padding: 50px 40px;
        min-height: 1058px;
        }

        #client-info {
        margin-bottom: 40px;
        }
        #client-info > div {
        margin-bottom: 3px;
        min-width: 20px;
        }
        #client-info span {
        display: block;
        min-width: 20px;
        }

        table {
        table-layout: fixed;
        }
        table th, table td {
        vertical-align: top;
        word-break: keep-all;
        word-wrap: break-word;
        }

        #items {
        margin: 20px 0 35px 0;
        }
        #items .first-cell, #items table th:first-child, #items table td:first-child {
        width: 18px;
        text-align: right;
        }
        #items table {
        border-collapse: separate;
        width: 100%;
        }
        #items table th {
        padding: 12px 10px;
        text-align: right;
        background: #E6E7E7;
        border-bottom: 4px solid #487774;
        }
        #items table th:nth-child(2) {
        width: 30%;
        text-align: left;
        }
        #items table th:last-child {
        text-align: right;
        padding-right: 20px !important;
        }
        #items table td {
        padding: 15px 10px;
        text-align: right;
        border-right: 1px solid #CCCCCF;
        }
        #items table td:first-child {
        text-align: left;
        border-right: 0 !important;
        }
        #items table td:nth-child(2) {
        text-align: left;
        }
        #items table td:last-child {
        border-right: 0 !important;
        padding-right: 20px !important;
        }

        .currency {
        border-bottom: 4px solid #487774;
        padding: 0 20px;
        }
        .currency span {
        font-size: 11px;
        font-style: italic;
        color: #8b8b8b;
        display: inline-block;
        min-width: 20px;
        }

        #sums {
        background: #8BA09E;
        background-size: auto 100px;
        color: white;
        }
        #sums table tr th, #sums table tr td {
        padding: 8px 20px 8px 35px;
        font-weight: 600;
        }
        #sums table tr th {
        text-align: left;
        padding-right: 25px;
        }
        #sums table tr.amount-total th {
        text-transform: uppercase;
        }
        #sums table tr.amount-total th, #sums table tr.amount-total td {
        font-size: 16px;
        font-weight: bold;
        }
        #sums table tr:last-child th {
        text-transform: uppercase;
        }
        #sums table tr:last-child th, #sums table tr:last-child td {
        font-size: 16px;
        font-weight: bold;
        padding-top: 20px !important;
        padding-bottom: 40px !important;
        }

        #terms {
        margin: 50px 20px 10px 20px;
        }
        #terms > span {
        display: inline-block;
        min-width: 20px;
        font-weight: bold;
        }
        #terms > div {
        margin-top: 10px;
        min-height: 50px;
        min-width: 50px;
        }

        .payment-info {
        margin: 0 20px;
        }
        .payment-info div {
        font-size: 12px;
        color: #8b8b8b;
        display: inline-block;
        min-width: 20px;
        }

        .ib_bottom_row_commands {
        margin: 10px 0 0 20px !important;
        }

        .ibcl_tax_value:before {
        color: white !important;
        }
        @media print {
        /* Here goes your print styles */
        }

        #logo {
            margin-left: 10px;
            margin-bottom: 10px;
            float: left;
            position: relative;
        }

        #logo img {
            height: 70px;
        }

        #company {
            position: relative;
            float: right;
            text-align: right;
            margin-right: 5px;
            width: 70%;
            font-size: 11pt;
            
        }

        .clearfix:after {
          content: "";
          display: table;
          clear: both;
        }

        header {
          padding: 10px 0;
          margin-bottom: 20px;
          border-bottom: 1px solid #AAAAAA;
          position: relative;
        }


        h2.name {
          font-size: 1.4em;
          font-weight: normal;
          margin: 0;
        }

        a {
          color: #0087C3;
          text-decoration: none;
        }
        .almt {
            font-size: 10pt;
        }

        #details {
            margin-bottom: 50px;
        }

        #client {
          padding-left: 6px;
          float: left;
          border-left: 6px solid #0087C3;
          width: 350px;
        }

        #client .to {
          color: #777777;
        }

        #invoice { 
            position: relative;
            float: right;
            text-align: right;
            margin-right: 5px;
            width: 50%;
            font-size: 11pt;
        }

        #invoice h2 {
          color: #0087C3;
          font-size: 1.4em;
          line-height: 1em;
          font-weight: normal;
          margin: 0  0 10px 0;
        }

        #invoice .date {
          font-size: 1.1em;
          color: #777777;
        }
        
        #thanks{
          font-size: 2em;
          margin-bottom: 50px;
        }

        #notices{
          padding-left: 6px;
          border-left: 6px solid #0087C3;  
        }

        #notices .notice {
          font-size: 1.2em;
        }

    </style>
</head>
<body>
    <?php $status = DisplayStatusOrder::getStatus($order->status) ?>
    <div id="container">
        <header class="clearfix">
            <div id="logo">
                <img src="<?= DisplayMenu::getLogo() ?>" alt="">
            </div>
            <div id="company">
                    <h2 class="name">PT. Porta Nusa Indonesia</h2>
                    <div class="almt">Ruko Permata Regensi Blok D No.37 Jl. H. Kelik Rt. 001/006 Kel. Srengseng, Kec. Kembangan Kota Administrasi Jakarta Barat Daya.</div>
                    <div>(021) 29327180</div>
                    <div><a href="mailto:customercare@portanusa.com">customercare@portanusa.com</a></div>
            </div>
        </header>
        <main>
            <div id="details" class="clearfix">
                <div id="client">
                    <div class="to">Invoice to :</div>
                    <h2 class="name">{{ $order->shipping_name }}</h2>
                    <div class="almt">{{ $order->shipping_address }}<br>{{ $order->shipping_province.", ".$order->shipping_city.", ".$order->shipping_postal_code }}</div>
                    <div>Phone : {{ $order->shipping_phone }}</div>
                </div>
                <div id="invoice">
                    <h2>INVOICE {{ $order->invoice_no }}</h2>
                    <div class="date">Date of Invoice:  {{ date('d/m/y', strtotime($order->created_at)) }}</div>
                    <div>Transaction Status : <b>{{ $status['message'] }}</b></div>
                </div>
            </div>
        </main>
    
        <div class="clearfix"></div>
        
        @if(count($order_products) != 0)
        <section id="items">
            
            <table cellpadding="0" cellspacing="0">
            
            <tr>
                <th style="width:10%;text-align:center">No</th> <!-- Dummy cell for the row number and row commands -->
                <th>Description</th>
                <th style="width:15%;text-align:center">Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
            </tr>
            
            <?php $no = 1 ?>
            <?php $subtotal = 0 ?>
            @foreach($order_products as $product)
            <tr>
                <td style="width:10%;text-align:center">{{$no}}</td> <!-- Don't remove this column as it's needed for the row commands -->
                <td>{{$product->name}}</td>
                <td style="width:15%;text-align:center">{{$product->quantity}}</td>
                <td>Rp {{ number_format($product->price, 0, 0, '.') }}</td>
                <td>
                    <?php 
                    $product_price = $product->quantity * $product->price;
                    $subtotal += $product_price;
                    ?>
                    Rp {{ number_format($product_price, 0, 0, '.') }}
                </td>
            </tr>
            <?php $no++ ?>
            @endforeach

            <tr>
                <td colspan="4" style="text-align:right;border-right:none;background-color:#8BA09E;color: #ffffff">Subtotal</td>
                <td style="text-align:right;border-right:none;background-color:#8BA09E;color: #ffffff">Rp {{ number_format($subtotal, 0, 0, '.') }}</td>
            </tr>
            @if(!empty($order->voucher_code))
            <tr>
                <td colspan="4" style="text-align:right;border-right:none;background-color:#8BA09E;color: #ffffff">Discount</td>
                <td style="text-align:right;border-right:none;background-color:#8BA09E;color: #ffffff">Rp {{ number_format($order->discount_price, 0, 0, '.') }}</td>
            </tr>
            @endif
            <tr>
                <td colspan="4" style="text-align:right;border-right:none;background-color:#8BA09E;color: #ffffff">Shipping Costs</td>
                <td style="text-align:right;border-right:none;background-color:#8BA09E;color: #ffffff">Rp {{ number_format($order->shipping_price, 0, 0, '.') }}</td>
            </tr>
            
            <tr class="amount-total">
                <td colspan="4" style="text-align:right;border-right:none;background-color:#8BA09E;color: #ffffff">Total</td>
                <td style="text-align:right;border-right:none;background-color:#8BA09E;color: #ffffff">Rp {{ number_format($order->total_price, 0, 0, '.') }}</td>
            </tr>
            </table>
            
        </section>
        @endif
        
        <div class="clearfix"></div>

        <div id="thanks">Thank you!</div>
        <div id="notices">
            <div>NOTICE:</div>
            <div class="notice">Thanks and Happy Shopping</div>
        </div>
    </div>

</body>
</html>
