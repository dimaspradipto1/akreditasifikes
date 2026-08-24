<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MutuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'type' => 'nullable|string',
            'kondisi_saat_ini' => 'nullable|string',
            'data_fakta' => 'nullable|string',
            'analisis' => 'nullable|string',
            'permasalahan' => 'nullable|string',
            'rencana_perbaikan' => 'nullable|string',
            'status' => 'sometimes|required|in:Memenuhi,Memenuhi Sebagian,Belum Memenuhi,Lengkap,Draft,Belum Diisi',
            'narasi_persen' => 'nullable|integer|min:0|max:100',
            'bukti_persen' => 'nullable|integer|min:0|max:100',
            'mutu_id' => 'sometimes|required|exists:mutus,id',
            'kriteria_kode' => 'sometimes|required|string',
            'nama_bukti' => 'sometimes|required|string|max:255',
            'level' => 'sometimes|required|in:PRODI,FIKES,UNIV',
            'status_bukti' => 'sometimes|required|in:Tersedia,Tidak Ada,Belum Memenuhi',
            'link' => 'nullable|string',
            'pic' => 'nullable|string|max:255',
            'deadline' => 'nullable|date',
            'catatan' => 'nullable|string'
        ];
    }
}