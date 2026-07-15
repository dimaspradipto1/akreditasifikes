<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KurikulumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->routeIs('kurikulum.narasi.update')) {
            return [
                'kondisi_saat_ini'  => 'nullable|string',
                'data_fakta'        => 'nullable|string',
                'analisis'          => 'nullable|string',
                'permasalahan'      => 'nullable|string',
                'rencana_perbaikan' => 'nullable|string',
                'status'            => 'required|in:Memenuhi,Memenuhi Sebagian,Belum Memenuhi,Lengkap,Draft,Belum Diisi',
                'narasi_persen'     => 'nullable|integer|min:0|max:100',
                'bukti_persen'      => 'nullable|integer|min:0|max:100',
            ];
        }

        if ($this->routeIs('kurikulum.bukti.store')) {
            return [
                'kurikulum_id'  => 'required|exists:kurikulums,id',
                'kriteria_kode' => 'required|string',
                'nama_bukti'    => 'required|string|max:255',
                'level'         => 'required|in:PRODI,FIKES,UNIV',
                'status_bukti'  => 'required|in:Tersedia,Tidak Ada,Belum Memenuhi',
                'link'         => 'nullable|url',
                'pic'          => 'nullable|string|max:255',
                'deadline'     => 'nullable|date',
                'catatan'      => 'nullable|string',
            ];
        }

        if ($this->routeIs('kurikulum.bukti.update')) {
            return [
                'nama_bukti'   => 'sometimes|required|string|max:255',
                'level'        => 'sometimes|required|in:PRODI,FIKES,UNIV',
                'status_bukti' => 'sometimes|in:Tersedia,Tidak Ada,Belum Memenuhi',
                'link'       => 'nullable|string|max:255',
                'pic'        => 'nullable|string|max:255',
                'deadline'   => 'nullable|date',
                'catatan'    => 'nullable|string',
            ];
        }

        return [];
    }
}
