<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class StorePinjamAlatDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nota_pinjam' => ['required'],
            'kode_daftar' => ['required'],
            'kode_alat' => [new RequiredIf($this->type === 'SATUAN')],
            'nama' => [new RequiredIf($this->type === 'SATUAN')],
            'jml' => [new RequiredIf($this->type === 'SATUAN')],
            'id_kategori' => [new RequiredIf($this->type === 'BUNDLE')],
        ];
    }
}
