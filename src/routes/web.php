<?php

use App\Http\Controllers\Admin\FuncionamentoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\AgendaController;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin,psicologo'])->group(function () {
    Route::get('/salas', [SalaController::class, 'index'])->name('salas.index');
    Route::get('/salas/{sala}', [SalaController::class, 'show'])->name('salas.show');
    Route::get('/minha-agenda', [AgendaController::class, 'index'])->name('agenda.index');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/salas', [SalaController::class, 'index'])->name('salas.index');
    Route::get('/salas/criar', [SalaController::class, 'create'])->name('salas.create');
    Route::post('/salas', [SalaController::class, 'store'])->name('salas.store');
    Route::get('/salas/{sala}/editar', [SalaController::class, 'edit'])->name('salas.edit');
    Route::put('/salas/{sala}', [SalaController::class, 'update'])->name('salas.update');
    Route::delete('/salas/{sala}', [SalaController::class, 'destroy'])->name('salas.destroy');

    Route::get('/configuracoes/funcionamento', [FuncionamentoController::class,'index',])->name('configuracoes.funcionamento.index');
    Route::post('/configuracoes/funcionamento', [FuncionamentoController::class,'store'])->name('configuracoes.funcionamento.store');
    Route::put('/configuracoes/funcionamento/{horario}', [FuncionamentoController::class,'update',])->name('configuracoes.funcionamento.update');
    Route::delete('/configuracoes/funcionamento/{horario}', [FuncionamentoController::class,'destroy',])->name('configuracoes.funcionamento.destroy');

    Route::get('/teste', function () {return 'Você é admin: ' . auth()->user()->nome;});
});

Route::get('/preview', function () {
    return view('preview.login');
});

require __DIR__ . '/auth.php';
