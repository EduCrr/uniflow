<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AgenciaUsuario extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'agencias_usuarios';

    protected $fillable = [
        'usuario_id',
        'agencia_id',
    ];

}
