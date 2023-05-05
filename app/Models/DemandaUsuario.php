<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class DemandaUsuario extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'demandas_usuarios';

    protected $fillable = [
        'usuario_id',
        'demanda_id',
    ];

}
