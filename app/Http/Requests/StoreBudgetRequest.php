<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBudgetRequest extends FormRequest
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
        return [
            'category_id'=>['sometimes','string'],
            'amount'=>['required','integer','min:1'],
            'month'=>['required','integer','max:12'],
            'year'=>['required','integer','min:2020'],
            'alert_percentage'=>['sometimes','integer','max:100']
        ];
    }
}
