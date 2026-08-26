<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
        'capacidade',
        'valor_hora',
        'infantil',
        'online',
        'ar_condicionado',
    ];

    public function getTipoLabelAttribute(): string
    {
        if ($this->infantil) return 'Infantil';
        if ($this->online) return 'Online';
        return $this->capacidade <= 2 ? 'Indivual' : 'Familiar';
    }
}
