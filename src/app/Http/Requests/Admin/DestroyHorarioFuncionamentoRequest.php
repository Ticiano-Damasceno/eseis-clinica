<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DestroyHorarioFuncionamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->perfil === 'admin';
    }

    public function rules(): array
    {
        return [];
    }
}
