<?php

namespace App\Http\Requests\GroupExpense;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGroupExpenseRequest extends FormRequest
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

        'category_id' => ['sometimes', 'nullable', 'string'],
        'amount' => ['sometimes', 'integer', 'min:1'],
        'description' => ['sometimes', 'string'],
        'expense_date' => ['sometimes', 'date'],
        'split_type' => ['sometimes', Rule::in(['equally', 'custom'])],
        'include_payer' => ['sometimes', 'boolean'],
        'splits' => ['required_if:split_type,custom', 'array'],
        'splits.*.user_id' => ['required_with:splits', 'string'],
        'splits.*.amount' => ['required_with:splits', 'integer', 'min:1'],
        ];
    }
}
