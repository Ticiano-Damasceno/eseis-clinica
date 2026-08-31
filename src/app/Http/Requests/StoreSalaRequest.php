<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSalaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->perfil === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'capacidade' => ['required', 'integer', 'min:1'],
            'valor_hora' => ['required', 'numeric', 'min:0'],
            'infantil' => ['boolean'],
            'online' => ['boolean'],
            'ar_condicionado' => ['boolean'],
        ];
    }
}
