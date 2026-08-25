<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SarpraskeuanganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        if ($this->input('type') === 'narasi') {
            return [
                'kondisi_saat_ini' => 'nullable|string',
                'data_fakta' => 'nullable|string',
                'analisis' => 'nullable|string',
                'permasalahan' => 'nullable|string',
                'rencana_perbaikan' => 'nullable|string',
                'status' => 'nullable|in:Memenuhi,Memenuhi Sebagian,Belum Memenuhi,Lengkap,Draft,Belum Diisi',
                'narasi_persen' => 'nullable|integer|min:0|max:100',
                'bukti_persen' => 'nullable|integer|min:0|max:100',
            ];
        }

        if ($this->input('type') === 'bukti') {
            if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
                return [
                    'nama_bukti' => 'sometimes|required|string|max:255',
                    'level' => 'sometimes|required|in:PRODI,FIKES,UNIV',
                    'status_bukti' => 'sometimes|in:Tersedia,Tidak Ada,Belum Memenuhi',
                    'status' => 'sometimes|in:Tersedia,Tidak Ada,Belum Memenuhi',
                    'link' => 'nullable|string',
                    'pic' => 'nullable|string|max:255',
                    'deadline' => 'nullable|date',
                    'catatan' => 'nullable|string'
                ];
            }

            return [
                'sarpraskeuangan_id' => 'required|exists:sarpraskeuangans,id',
                'kriteria_kode' => 'required|string',
                'nama_bukti' => 'required|string|max:255',
                'level' => 'required|in:PRODI,FIKES,UNIV',
                'status_bukti' => 'sometimes|in:Tersedia,Tidak Ada,Belum Memenuhi',
                'status' => 'sometimes|in:Tersedia,Tidak Ada,Belum Memenuhi',
                'link' => 'nullable|string',
                'pic' => 'nullable|string|max:255',
                'deadline' => 'nullable|date',
                'catatan' => 'nullable|string'
            ];
        }

        return [];
    }
}
