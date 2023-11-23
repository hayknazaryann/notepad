<?php

namespace App\Http\Requests\Note;

use App\Enums\Extensions;
use App\Enums\PageSizes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterRequest extends FormRequest
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
            'keyword' => 'nullable|string',
            'group' => 'nullable|exists:note_groups,id',
            'pageSize' => ['nullable', Rule::in(PageSizes::all())],
            'page' => 'nullable|integer'
        ];
    }
}
