<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalaRequest;
use App\Http\Requests\UpdateSalaRequest;
use Illuminate\Http\Request;
use App\Models\Sala;
use Carbon\CarbonImmutable;

class SalaController extends Controller
{
    public function index()
    {
        $salas = Sala::all();
        return view('salas.index', compact('salas'));
    }
    public function create()
    {
        return view('salas.create');
    }

    public function store(StoreSalaRequest $request)
    {
        $dados = $request->validated();

        if($request->hasFile('imagem')) {
            $dados['imagem'] = $request->file('imagem')->store('salas', 'public');
        }

        Sala::create($dados);
        return redirect()->route('salas.index')->with('success', 'Sala criada com sucesso!');
    }

    public function edit(Sala $sala)
    {
        return view('salas.edit', compact('sala'));
    }

    public function update(UpdateSalaRequest $request, Sala $sala)
    {
        $dados = $request->validated();

        if ($request->hasFile('imagem')) {
            $dados['imagem'] = $request->file('imagem')->store('salas', 'public');
        } else {
            unset($dados['imagem']); // Remove 'imagem' de $dados se houver nova imagem
        }

        $sala->update($dados);
        return redirect()->route('admin.salas.index')->with('success', 'Sala atualizada com sucesso!');
    }

    public function destroy(Sala $sala)
    {
        $sala->delete();
        return redirect()->route('admin.salas.index')->with('success', 'Sala excluída com sucesso!');
    }

    public function show(Sala $sala)
    {
        $inicio = CarbonImmutable::today()->startOfWeek(0);

        $dias = collect(range(0,6))->map(fn ($indice) => $inicio->addDays($indice));

        return view('salas.show', compact('sala', 'dias'));
    }
}
