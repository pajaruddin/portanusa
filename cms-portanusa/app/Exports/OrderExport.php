<?php

namespace App\Exports;

use App\Order;
use \Maatwebsite\Excel\Concerns\Exportable;
use \Maatwebsite\Excel\Concerns\FromQuery;
use \Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderExport implements FromQuery, WithHeadings
{
    use Exportable;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function query() {

        $query_order = Order::query()->select('customers.first_name', 'order_products.name', 'order_products.quantity', 'order_products.price', 'orders.invoice_no', 'orders.shipping_price', 'orders.voucher_code', 'orders.discount_price', 'orders.total_price', 'orders.created_at')
        ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
        ->leftJoin('order_products', 'order_products.order_id', '=', 'orders.id')
        ->leftJoin('order_status', 'order_status.id', '=', 'orders.status');

        if (!empty($this->data['export_month']) && !empty($this->data['export_year']) && !empty($this->data['status'])) {
            $month = date('m', strtotime($this->data['export_month']));
            $year = date('Y', strtotime($this->data['export_year']));
            $status = $this->data['status'];

            $query_order->whereMonth('orders.created_at', '>=', $month)->whereYear('orders.created_at', '<=', $year)->where('orders.status', $status);
        } else {
            $query_order->whereDate('orders.created_at', '=', date('Y-m-d'));
        }
        

        $newsletters = $query_order->orderBy('created_at', 'ASC');
        return $newsletters;
    }

    public function headings(): array {
        return [
            'Nama Pelanggan',
            'Nama Product',
            'Jumlah',
            'Harga',
            'No Invoice',
            'Harga Pengiriman',
            'Kode Voucher',
            'Diskon',
            'Total Harga',
            'Tanggal Order'
        ];
    }
}
