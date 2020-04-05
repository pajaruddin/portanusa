<?php

namespace App\Exports;

use App\Newsletter;
use \Maatwebsite\Excel\Concerns\Exportable;
use \Maatwebsite\Excel\Concerns\FromQuery;
use \Maatwebsite\Excel\Concerns\WithHeadings;

class NewsletterExport implements FromQuery, WithHeadings {

    use Exportable;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function query() {
        $query_newsletter = Newsletter::query()->select('email', 'created_at');

        if (!empty($this->data['periode'])) {
            $periode = $this->data['periode'];
            if ($periode == "today") {
                $query_newsletter->whereDate('created_at', '=', date('Y-m-d'));
            } else {
                if (!empty($this->data['date_start']) && !empty($this->data['date_end'])) {
                    $date_start = date('Y-m-d', strtotime($this->data['date_start']));
                    $date_end = date('Y-m-d', strtotime($this->data['date_end']));

                    $query_newsletter->whereDate('created_at', '>=', $date_start)->whereDate('created_at', '<=', $date_end);
                } else {
                    $query_newsletter->whereDate('created_at', '=', date('Y-m-d'));
                }
            }
        }

        $newsletters = $query_newsletter->orderBy('created_at', 'ASC');
        return $newsletters;
    }

    public function headings(): array {
        return [
            'Email',
            'Tanggal Registrasi'
        ];
    }
}
