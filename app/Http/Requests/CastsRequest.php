<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CastsRequest extends FormRequest
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
                'name' => 'required|max:255',
                'bio' => 'required',
                'age' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama Cast harus diisi',
            'name.max' => 'Nama Cast tidak boleh lebih dari 255 karakter',
            'bio.required' => 'Bio Cast harus diisi',
            'year.required' => 'Umur Cast harus diisi',
        ];
    }
}
