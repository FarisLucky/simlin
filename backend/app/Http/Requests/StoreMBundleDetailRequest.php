<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreMBundleDetailRequest extends FormRequest
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
            'kode_alat' => [
                'required',
                function ($attribute, $value, $fail) {
                    $exists = DB::table('m_alat_detail')
                        ->where([
                            'id_kategori' => $this->id_kategori,
                            'kode_alat' => $this->kode_alat
                        ])
                        ->exists();

                    if ($exists) {
                        $fail('Alat sudah ada di kategori');
                    }
                }
            ],
            'id_bundle' => ['required'],
            'jml' => ['required'],
        ];
    }
}
