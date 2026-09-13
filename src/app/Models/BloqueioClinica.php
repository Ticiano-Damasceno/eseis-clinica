<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloqueioClinica extends Model
{
    protected $table = 'bloqueios_clinica';

    protected $fillable = [
        'data','motivo'
    ];

    protected function casts(): array {
        return [
            'data' => 'immutable_date'
        ];
    }
}
