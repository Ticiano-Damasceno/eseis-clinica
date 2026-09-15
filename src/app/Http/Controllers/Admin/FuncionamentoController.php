<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\DestroyHorarioFuncionamentoRequest;
use App\Http\Requests\Admin\UpdateHorarioFuncionamentoRequest;
use App\Services\FuncionamentoService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHorarioFuncionamentoRequest;
use App\Models\BloqueioClinica;
use App\Models\HorarioFuncionamento;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\RedirectResponse;
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

    public function store(StoreHorarioFuncionamentoRequest $request, FuncionamentoService $service): RedirectResponse
    {
        try {
            $service->cadastrarHorario($request->validated());
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

    public function update(UpdateHorarioFuncionamentoRequest $request, HorarioFuncionamento $horario, FuncionamentoService $service): RedirectResponse
    {
        try {
            $service->atualizarHorario(
                $horario->id,
                $request->validated()
            );
        } catch (LockTimeoutException $exception) {
            return back()->withErrors([
                    'horario_inicio' =>
                        'Outra alteração está em andamento. Tente novamente.',
                ]);
        }

        return to_route('admin.configuracoes.funcionamento.index')
            ->with('success', 'Horário atualizado com sucesso.');
    }

    public function destroy(DestroyHorarioFuncionamentoRequest $request, HorarioFuncionamento $horario, FuncionamentoService $service): RedirectResponse
    {
        try {
            $service->excluirHorario($horario->id);
        } catch (LockTimeoutException $exception) {
            return back()->withErrors([
                    'horario_inicio' =>
                        'Outra exclusão está em andamento. Tente novamente.',
                ]);
        }

        return to_route('admin.configuracoes.funcionamento.index')
            ->with('success', 'Horário de funcionamento excluído com sucesso.');
    }
}
