<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewDemandRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email',
            'tel' => 'required|min:9',
            'message' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Pole se jménem je povinné',
            'email.required' => 'Pole s emailem je povinné',
            'email.email' => 'Email není ve správném formátu',
            'tel.required' => 'Pole s telefonem je povinné',
            'tel.min' => 'Telefoní číslo musí mít alespoň 9 znaků',
            'message.required' => 'Pole se zprávou je povinné'
        ];
    }

    public function getRedirectUrl(): string
    {
        return parent::getRedirectUrl().'#kontakt';
    }
}
