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

    protected function prepareForValidation(): void
    {
        $this->merge([
            'infantil' => $this->boolean('infantil'),
            'online' => $this->boolean('online'),
            'ar_condicionado' => $this->boolean('ar_condicionado'),
            'valor_hora' => $this->normalizarValor($this->input('valor_hora')),
        ]);
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
            'imagem' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'capacidade' => ['required', 'integer', 'min:1'],
            'valor_hora' => ['required', 'numeric', 'min:0'],
            'infantil' => ['boolean'],
            'online' => ['boolean'],
            'ar_condicionado' => ['boolean'],
        ];
    }

    private function normalizarValor(?string $valor): ?string
    {
        if ($valor === null || $valor === '') {
            return $valor;
        }

        return str_replace(',', '.', str_replace('.', '', $valor));
    }
}
