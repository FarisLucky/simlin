<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLinenDetailRequest extends FormRequest
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
            'nota_linen' => ['required'],
            'kode_daftar' => ['required'],
            'kode' => ['required'],
            'nama' => ['required'],
            'jml' => ['required'],
        ];
    }
}
