<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class NasabahImportSheets implements WithMultipleSheets
{
    public NasabahImport $nasabahImport;

    public function __construct()
    {
        $this->nasabahImport = new NasabahImport();
    }

    public function sheets(): array
    {
        return [
            0 => $this->nasabahImport,
        ];
    }

    public function failures()
    {
        return $this->nasabahImport->failures();
    }
}