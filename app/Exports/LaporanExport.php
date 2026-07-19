<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromArray, WithHeadings, WithStyles
{
    protected $kriteria;
    protected $data;

    public function __construct($kriteria, $data)
    {
        $this->kriteria = $kriteria;
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->data as $item) {
            $rows[] = [
                'Kriteria' => $item['kriteria'],
                'Kode Sub-Kriteria' => $item['kode'],
                'Nama Sub-Kriteria' => $item['nama'],
                'Kondisi Saat Ini' => $item['kondisi_saat_ini'],
                'Status' => $item['status'],
                'Narasi (%)' => $item['narasi_persen'] . '%',
                'Bukti (%)' => $item['bukti_persen'] . '%',
            ];
        }
        return $rows;
    }

    public function headings(): array
    {
        return [
            'Kriteria',
            'Kode Sub-Kriteria',
            'Nama Sub-Kriteria',
            'Kondisi Saat Ini',
            'Status',
            'Narasi (%)',
            'Bukti (%)',
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
