<?php

namespace App\Http\Requests\Private;

use Illuminate\Foundation\Http\FormRequest;

class CreateAboutRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'tanggal_lahir' => 'required|',
            'alamat_ktp' => 'required|',
            'alamat_domisili' => 'required|',
            'agama' => 'required|',
            'jenis_kelamin' => 'required|',
            'summary_text' => 'required|',
            'about_text' => 'required|',
        ];
    }
}
