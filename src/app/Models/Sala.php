<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sala extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'nome',
        'descricao',
        'imagem',
        'capacidade',
        'valor_hora',
        'infantil',
        'online',
        'ar_condicionado',
    ];

    public function getTipoLabelAttribute(): string
    {
        if ($this->infantil)
            return 'Infantil';
        if ($this->online)
            return 'Online';
        return $this->capacidade <= 2 ? 'Indivual' : 'Familiar';
    }

    protected function casts(): array
    {
        return [
            'infantil' => 'boolean',
            'online' => 'boolean',
            'ar_condicionado' => 'boolean',
            'valor_hora' => 'decimal:2',
        ];
    }
}
