<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioFuncionamento extends Model
{
    protected $table = 'horario_funcionamento';

    protected $fillable = [
        'dia_semana',
        'horario_inicio',
        'horario_fim'
    ];

    protected function casts(): array{
        return[
            'dia_semana' => 'integer',
        ];
    }
}
