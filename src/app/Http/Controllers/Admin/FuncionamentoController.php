<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHorarioFuncionamentoRequest;
use App\Models\BloqueioClinica;
use App\Models\HorarioFuncionamento;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class FuncionamentoController extends Controller
{
    public function index(): View
    {
        $diasSemana = [
            0 => 'Domingo',
            1 => 'Segunda-feira',
            2 => 'Terça-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
            6 => 'Sábado',
        ];

        $horariosPorDia = HorarioFuncionamento::query()
            ->orderBy('dia_semana')
            ->orderBy('horario_inicio')
            ->get()
            ->groupBy('dia_semana');

        $bloqueios = BloqueioClinica::query()
            ->orderBy('data', 'desc')
            ->paginate(10);

        return view('admin.configuracoes.funcionamento.index',[
            'diasSemana' => $diasSemana,
            'horariosPorDia' => $horariosPorDia,
            'bloqueios' => $bloqueios,
        ]);
    }

    public function store(StoreHorarioFuncionamentoRequest $request): RedirectResponse
    {
        $dados = $request->validated();

        $dados['horario_inicio'] .= ':00';
        $dados['horario_fim'] .= ':00';

        try {
            Cache::lock('funcionamento:escrita', 30)->block(
                5,
                function () use ($dados): void {
                    $sobrepoe = HorarioFuncionamento::query()
                        ->where('dia_semana', $dados['dia_semana'])
                        ->where('horario_inicio','<',$dados['horario_fim'])
                        ->where('horario_fim', '>', $dados['horario_inicio'])
                        ->exists();

                    if ($sobrepoe) {
                        throw ValidationException::withMessages([
                            'horario_inicio' =>
                            'Essa faixa se sobrepõe a um horário já cadastrado.',
                        ]);
                    }

                    HorarioFuncionamento::create($dados);
                }
            );
        } catch (LockTimeoutException $exception) {
            return back()
                ->withInput()
                ->withErrors([
                    'horario_inicio' =>
                        'Outro cadastro está em andamento. Tente novamente.',
                ]);
        }

        return to_route('admin.configuracoes.funcionamento.index')
            ->with('success', 'Horário cadastrado com sucesso.');
    }
}
