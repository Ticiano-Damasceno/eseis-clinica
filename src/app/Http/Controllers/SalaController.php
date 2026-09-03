<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalaRequest;
use App\Http\Requests\UpdateSalaRequest;
use Illuminate\Http\Request;
use App\Models\Sala;

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
        Sala::create($request->validated());
        return redirect()->route('salas.index')->with('success', 'Sala criada com sucesso!');
    }

    public function edit(Sala $sala)
    {
        return view('salas.edit', compact('sala'));
    }

    public function update(UpdateSalaRequest $request, Sala $sala)
    {
        $sala->update($request->validated());
        return redirect()->route('admin.salas.index')->with('success', 'Sala atualizada com sucesso!');
    }

    public function destroy(Sala $sala)
    {
        $sala->delete();
        return redirect()->route('admin.salas.index')->with('success', 'Sala excluída com sucesso!');
    }
}
