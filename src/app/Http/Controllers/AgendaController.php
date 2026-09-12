<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $dados = $request->validate([
            'data' => ['nullable', 'date_format:Y-m-d'],
            'modo' => ['nullable', 'in:mes,semana'],
        ]);

        $data = CarbonImmutable::parse(
            $dados['data'] ?? today()->toDateString()
        )->startOfDay()->locale('pt_BR');

        $modo = $dados['modo'] ?? 'mes';

        if ($modo == 'mes') {
            $inicio = $data->startOfMonth()->startOfWeek();
            $quantidade = 42;

            $anterior = $data->startOfMonth()->subMonth();
            $proximo = $data->startOfMonth()->addMonth();
        } else {
            $inicio = $data->startOfWeek();
            $quantidade = 7;

            $anterior = $data->subWeek();
            $proximo = $data->addWeek();
        }

        $dias = collect(range(0, $quantidade - 1))->map(
            fn ($indice) => $inicio->addDays($indice)
        );

        return view('agenda.index', compact(
            'data', 'modo', 'inicio', 'dias', 'anterior', 'proximo'
        ));
    }
}
