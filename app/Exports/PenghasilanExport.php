<?php
// app/Exports/PenghasilanExport.php
// app/Exports/PenghasilanExport.php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PenghasilanExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions->filter(function ($t) {
        return in_array($t->status, ['paid', 'berhasil']);
    });

    }

    public function view(): View
    {
        return view('exports.penghasilan', [
            'penghasilan' => $this->transactions
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A:F')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        return [];
    }
}
