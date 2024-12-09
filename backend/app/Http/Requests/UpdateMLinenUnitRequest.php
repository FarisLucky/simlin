<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMLinenUnitRequest extends FormRequest
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
            'kode' => ['required', Rule::unique('m_linen_unit','kode')->ignore($this->kode,'kode')->where('kode',$this->kode)],
            'kode_linen' => ['required'],
            'kode_unit' => ['required'],
            'terpakai' => ['required'],
            'bersih' => ['required'],
            'kotor' => ['required'],
            'status' => ['required'],
        ];
    }
}
