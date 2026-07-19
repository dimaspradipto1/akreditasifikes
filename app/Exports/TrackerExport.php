<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TrackerExport implements FromArray, WithHeadings, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->data as $b) {
            $rows[] = [
                'KRITERIA' => $b->kriteria ?? '',
                'SUB-K' => $b->sub_k ?? '',
                'KODE EU' => $b->kode_eu ?? '',
                'NAMA DOKUMEN / BUKTI' => $b->nama_dokumen ?? '',
                'LEVEL' => $b->level ?? '',
                'STATUS' => $b->status ?? '',
                'PIC' => $b->pic ?? '',
            ];
        }
        return $rows;
    }

    public function headings(): array
    {
        return [
            'KRITERIA',
            'SUB-K',
            'KODE EU',
            'NAMA DOKUMEN / BUKTI',
            'LEVEL',
            'STATUS',
            'PIC',
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
