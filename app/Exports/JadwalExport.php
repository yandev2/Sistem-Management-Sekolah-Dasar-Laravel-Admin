<?php

namespace App\Exports;

use App\Models\JadwalModel;
use Maatwebsite\Excel\Concerns\FromCollection;

class JadwalExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return JadwalModel::all();
    }
}
