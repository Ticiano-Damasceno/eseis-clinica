<?php

namespace App\Http\Requests\Admin;

class UpdateHorarioFuncionamentoRequest extends StoreHorarioFuncionamentoRequest
{
    public function after(): array
    {
        return [];
    }
}
