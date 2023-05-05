<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Notificacao;
use App\Models\Demanda;
use App\Models\User;
use App\Models\DemandaTempo;

class ComentarioPauta extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'comentarios_pautas';

    protected $fillable = [
        'comentario_id',
        'demandapauta_id',
    ];

}
