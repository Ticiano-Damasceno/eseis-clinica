<?php

namespace App\Services;

use App\Models\HorarioFuncionamento;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class FuncionamentoService
{
    public function cadastrarHorario(array $dados): HorarioFuncionamento
    {
        $dados['horario_inicio'] .= ':00';
        $dados['horario_fim'] .= ':00';

        return Cache::lock('funcionamento:escrita',30)->block(
            5,
            function () use ($dados): HorarioFuncionamento {
                $sobrepoe = HorarioFuncionamento::query()
                    ->where('dia_semana', $dados['dia_semana'])
                    ->where('horario_inicio', '<', $dados['horario_fim'])
                    ->where('horario_fim', '>', $dados['horario_inicio'])
                    ->exists();
                
                if ($sobrepoe) {
                    throw ValidationException::withMessages([
                        'horario_inicio' => 'O horário informado sobrepõe outro horário já cadastrado.',
                    ]);
                }

                return HorarioFuncionamento::create($dados);
            }
        );
    }

    public function atualizarHorario(int $id, array $dados): HorarioFuncionamento
    {
        $dados['horario_inicio'] .= ':00';
        $dados['horario_fim'] .= ':00';

        return Cache::lock('funcionamento:escrita',30)->block(
            5,
            function () use ($id, $dados): HorarioFuncionamento {
                $horario = HorarioFuncionamento::findOrFail($id);

                $sobrepoe = HorarioFuncionamento::query()
                    ->where('id', '<>', $horario->id)
                    ->where('dia_semana', $dados['dia_semana'])
                    ->where('horario_inicio', '<', $dados['horario_fim'])
                    ->where('horario_fim', '>', $dados['horario_inicio'])
                    ->exists();
                
                if ($sobrepoe) {
                    throw ValidationException::withMessages([
                        'horario_inicio' => 'O horário informado sobrepõe outro horário já cadastrado.',
                    ]);
                }

                $horario->update($dados);
                return $horario;
            }
        );
    }

    public function excluirHorario(int $id): void
    {
        Cache::lock('funcionamento:escrita',30)->block(
            5,
            function () use ($id): void {
                $horario = HorarioFuncionamento::findOrFail($id);

                // Inserir aqui a regra de bloqueio quando houver reserva no horário a ser excluído.

                $horario->delete();
            }
        );
    }
}
