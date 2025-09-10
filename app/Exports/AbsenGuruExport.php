<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class AbsenGuruExport implements FromView, WithStyles, WithEvents
{
    protected $data;


    public function __construct($json)
    {
        $this->data = $json;
    }

    public function view(): View
    {
        return view('component.exporter.AbsenGuruExel', [
            'json' => $this->data
        ]);
    }



    public function styles(Worksheet $sheet)
    {
     $highestRow = $sheet->getHighestRow(); // ambil baris terakhir yang ada datanya
        $highestColumn = $sheet->getHighestColumn(); // ambil kolom terakhir (bisa Z, AA, AB, ...)

        $range = 'A3:' . $highestColumn . $highestRow;
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
    }


    public function registerEvents(): array
    {
        return [
              
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle('B5:B' .   $lastRow)
                    ->getNumberFormat()
                    ->setFormatCode('0');
            
       
            }
        ];
    }
}
