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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:filter', 'max:255'],
            'tel' => ['required', 'string', 'min:9', 'max:20'],
            'city' => ['nullable', 'string', 'max:255'],
            'zip' => ['nullable', 'string', 'max:10'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Pole se jménem je povinné',
            'name.max' => 'Jméno může mít nejvýše 255 znaků',
            'email.required' => 'Pole s emailem je povinné',
            'email.email' => 'Email není ve správném formátu',
            'tel.required' => 'Pole s telefonem je povinné',
            'tel.min' => 'Telefoní číslo musí mít alespoň 9 znaků',
            'tel.max' => 'Telefoní číslo může mít nejvýše 20 znaků',
            'zip.max' => 'PSČ může mít nejvýše 10 znaků',
            'message.required' => 'Pole se zprávou je povinné',
            'message.max' => 'Zpráva může mít nejvýše 5000 znaků',
        ];
    }

    /**
     * Telefon se ukládá bez mezer, ať se dá spolehlivě prokliknout.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('tel')) {
            $this->merge(['tel' => preg_replace('/\s+/', '', (string) $this->input('tel'))]);
        }
    }

    public function getRedirectUrl(): string
    {
        return parent::getRedirectUrl().'#kontakt';
    }
}
