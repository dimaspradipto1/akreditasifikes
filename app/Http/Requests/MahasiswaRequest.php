<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MahasiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Narasi rules
            'kondisi_saat_ini' => 'nullable|string',
            'data_fakta' => 'nullable|string',
            'analisis' => 'nullable|string',
            'permasalahan' => 'nullable|string',
            'rencana_perbaikan' => 'nullable|string',
            'status' => 'nullable|in:Memenuhi,Memenuhi Sebagian,Belum Memenuhi,Lengkap,Draft,Belum Diisi',
            'narasi_persen' => 'nullable|integer|min:0|max:100',
            'bukti_persen' => 'nullable|integer|min:0|max:100',

            // Bukti rules (when storing/updating)
            'mahasiswa_id' => 'sometimes|required|exists:mahasiswas,id',
            'kriteria_kode' => 'sometimes|required|string',
            'nama_bukti' => 'sometimes|required|string|max:255',
            'level' => 'sometimes|required|in:PRODI,FIKES,UNIV',
            'status_bukti' => 'sometimes|in:Tersedia,Tidak Ada,Belum Memenuhi',
            'link' => 'nullable|string|max:255',
            'pic' => 'nullable|string|max:255',
            'deadline' => 'nullable|date',
            'catatan' => 'nullable|string',
        ];
    }
}
