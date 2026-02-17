<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamCreateRequest extends FormRequest
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
            'conference'   => 'required|string|max:50',
            'division'     => 'required|string|max:50',
            'city'         => 'required|string|max:100',
            'name'         => 'required|string|max:100',
            'full_name'    => 'required|string|max:150',
            'abbreviation' => 'required|string|max:10'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'conference.required'   => 'O campo conferência é obrigatório.',
            'conference.string'     => 'A conferência deve ser um texto.',
            'conference.max'        => 'A conferência não pode exceder 50 caracteres.',
            'division.required'     => 'O campo divisão é obrigatório.',
            'division.string'       => 'A divisão deve ser um texto.',
            'division.max'          => 'A divisão não pode exceder 50 caracteres.',
            'city.required'         => 'O campo cidade é obrigatório.',
            'city.string'           => 'A cidade deve ser um texto.',
            'city.max'              => 'A cidade não pode exceder 100 caracteres.',
            'name.required'         => 'O campo nome é obrigatório.',
            'name.string'           => 'O nome deve ser um texto.',
            'name.max'              => 'O nome não pode exceder 100 caracteres.',
            'full_name.required'    => 'O campo nome completo é obrigatório.',
            'full_name.string'      => 'O nome completo deve ser um texto.',
            'full_name.max'         => 'O nome completo não pode exceder 150 caracteres.',
            'abbreviation.required' => 'O campo abreviação é obrigatório.',
            'abbreviation.string'   => 'A abreviação deve ser um texto.',
            'abbreviation.max'      => 'A abreviação não pode exceder 10 caracteres.',
        ];
    }
}
