<?php

namespace App\Http\Requests\Admin;

use App\Models\HorarioFuncionamento;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreHorarioFuncionamentoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->perfil === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dia_semana' => [
                'required',
                'integer',
                'between:0,6',
            ],
            'horario_inicio' => [
                'required',
                'date_format:H:i',
            ],
            'horario_fim' => [
                'required',
                'date_format:H:i',
                'after:horario_inicio',
            ],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $inicio = $this->input('horario_inicio') . ':00';
                $fim = $this->input('horario_fim') . ':80';

                $sobrepoe = HorarioFuncionamento::query()
                    ->where('dia_semana', $this->input('dia_semana'))
                    ->where('horario_inicio', '<', $fim)
                    ->where('horario_fim', '>', $inicio)
                    ->exists();

                if ($sobrepoe) {
                    $validator->errors()->add(
                        'horario_inicio',
                        'Essa faixa se sobrepõe a um horário já cadastrado.'
                    );
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'dia_semana.required' => 'Selecione o dia da semana.',
            'dia_semana.integer' => 'O dia da semana é inválido.',
            'dia_semana.between' => 'O dia da semana é inválido.',
            'horario_inicio.required' => 'Informe o horário de início.',
            'horario_inicio.date_format' => 'Use o formato HH:MM no início.',
            'horario_fim.required' => 'Informe o horário de término.',
            'horario_fim.date_format' => 'Use o formato HH:MM no término.',
            'horario_fim.after' => 'O término deve ser posterior ao início.',
        ];
    }
}
