<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AgenciaColaborador extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'agencias_colaboradores';

    protected $fillable = [
        'usuario_id',
        'agencia_id',
    ];

}
