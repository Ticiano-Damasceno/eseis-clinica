<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalaRequest;
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

    public function manage()
    {
        $salas = Sala::all();
        return view('admin.salas.index', compact('salas'));
    }
}
