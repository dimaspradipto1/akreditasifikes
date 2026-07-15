<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VmtsRequest extends FormRequest
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
        if ($this->routeIs('vmts.narasi.update')) {
            return [
                'kondisi_saat_ini'  => 'nullable|string',
                'data_fakta'        => 'nullable|string',
                'analisis'          => 'nullable|string',
                'permasalahan'      => 'nullable|string',
                'rencana_perbaikan' => 'nullable|string',
                'status'            => 'required|in:Draft,Lengkap,Belum Diisi',
            ];
        }

        if ($this->routeIs('vmts.bukti.store')) {
            return [
                'vmts_id'    => 'required|exists:vmts,id',
                'nama_bukti' => 'required|string|max:255',
                'level'      => 'required|in:PRODI,FIKES,UNIV',
                'status'     => 'required|in:Tersedia,Tidak Ada,Belum Memenuhi',
                'link'       => 'nullable|url',
                'pic'        => 'nullable|string|max:255',
                'deadline'   => 'nullable|date',
                'catatan'    => 'nullable|string',
            ];
        }

        if ($this->routeIs('vmts.bukti.update')) {
            return [
                'nama_bukti' => 'required|string|max:255',
                'level'      => 'required|in:PRODI,FIKES,UNIV',
                'status'     => 'required|in:Tersedia,Tidak Ada,Belum Memenuhi',
                'link'       => 'nullable|url',
                'pic'        => 'nullable|string|max:255',
                'deadline'   => 'nullable|date',
                'catatan'    => 'nullable|string',
            ];
        }

        return [];
    }
}
