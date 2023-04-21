<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Agencia;

class InformacaoUsuario extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'informacoes_usuario';

    protected $fillable = [
        'cidade_id',
        'estado_id',
        'agencia_id',
        'usuario_id',
    ];

    public function Usuario(){
        return $this->belongsTo(User::class);
    }

    public function agencia(){
        return $this->belongsTo(Agencia::class);
    }
}
