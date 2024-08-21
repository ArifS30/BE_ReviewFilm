<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovieRequest extends FormRequest
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
                'title' => 'required|max:255',
                'summary' => 'required',
                'year' => 'required|date',
                'poster' => 'mimes:jpg,bmp,png',
                'genre_id' => 'required',
            ];
        
    }
    public function messages()
    {
        return [
            'title.required' => 'Judul film harus diisi',
            'title.max' => 'Judul film tidak boleh lebih dari 255 karakter',
            'summary.required' => 'Sinopsis film harus diisi',
            'year.required' => 'Tahun film harus diisi',
            'year.date' => 'Tahun film harus dalam format tanggal yang valid',
            'poster.mimes' => 'Poster film harus dalam format JPG, BMP, atau PNG',
            'genre_id.required' => 'ID genre film harus diisi',
        ];
    }
}
