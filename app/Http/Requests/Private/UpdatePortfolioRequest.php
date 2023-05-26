<?php

namespace App\Http\Requests\Private;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePortfolioRequest extends FormRequest
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
            'type' =>  'required|string',
            'title' =>  'required|string|unique:portfolios,title' . $this->portfolio->id,
            'client_type' =>  'required|string',
            'client_name' =>  'required|string',
            'preview_url' =>  'required|url',
            'summary' =>  'required|string|min:20|max:80',
            'content' =>  'required|string|min:50|max:10000',
            'start_date' => 'required|date_format',
            'end_date' => 'required|date_format:Y-m-d',
            'tech' => 'required|array',
            'desc' => 'required|array',
        ];
    }
}
