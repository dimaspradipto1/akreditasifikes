<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoenpkmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [];

        if ($this->routeIs('doenpkm.narasi.update')) {
            $rules = [
                'status' => 'required|in:Draft,Lengkap,Belum Diisi',
                'kondisi_saat_ini' => 'nullable|string',
                'data_fakta' => 'nullable|string',
                'analisis' => 'nullable|string',
                'permasalahan' => 'nullable|string',
                'rencana_perbaikan' => 'nullable|string',
                'narasi_persen' => 'nullable|integer|min:0|max:100',
            ];
        }

        if ($this->routeIs('doenpkm.bukti.store')) {
            $rules = [
                'doenpkm_id' => 'required|exists:doenpkms,id',
                'elemen_kode' => 'required|string',
                'nama_bukti' => 'required|string|max:255',
                'level' => 'nullable|string|in:PRODI,FIKES,UNIV',
                'status_bukti' => 'nullable|string|in:Tersedia,Tidak Ada,Belum Memenuhi',
                'link' => 'nullable|url|max:255',
                'pic' => 'nullable|string|max:255',
                'deadline' => 'nullable|date',
                'catatan' => 'nullable|string',
            ];
        }

        if ($this->routeIs('doenpkm.bukti.update')) {
            $rules = [
                'doenpkm_id' => 'sometimes|exists:doenpkms,id',
                'elemen_kode' => 'sometimes|string',
                'nama_bukti' => 'sometimes|string|max:255',
                'level' => 'sometimes|string|in:PRODI,FIKES,UNIV',
                'status_bukti' => 'sometimes|string|in:Tersedia,Tidak Ada,Belum Memenuhi',
                'link' => 'nullable|url|max:255',
                'pic' => 'nullable|string|max:255',
                'deadline' => 'nullable|date',
                'catatan' => 'nullable|string',
            ];
        }

        return $rules;
    }
}
