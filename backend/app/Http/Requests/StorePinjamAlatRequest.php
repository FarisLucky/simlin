<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class StorePinjamAlatRequest extends FormRequest
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
            'kode_daftar' => ['required'],
            // 'id_bundle' => [new RequiredIf($this->type === 'SATUAN')],
            'nama' => ['required'],
            'jml' => ['required'],
            'id_kategori' => [new RequiredIf($this->type === 'BUNDLE')],
        ];
    }
}
