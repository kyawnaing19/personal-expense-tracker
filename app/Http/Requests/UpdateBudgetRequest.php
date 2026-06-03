<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBudgetRequest extends FormRequest
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
            'amount'=>['sometimes','integer','min:1'],
            'month'=>['sometimes','integer','max:12'],
            'year'=>['sometimes','integer','min:2020'],
            'alert_percentage'=>['sometimes','integer','max:100']
        ];
    }
}
