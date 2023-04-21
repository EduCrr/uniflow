<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Notificacao;
use App\Models\Demanda;
use App\Models\User;
use App\Models\DemandaTempo;

class Agencia extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'agencias';

    protected $fillable = [
        'nome',
        'logo',
        'criado',
        'excluido'
    ];

    public function agenciasUsuarios(){
        return $this->belongsToMany(User::class, 'agencias_usuarios', 'agencia_id', 'usuario_id');
    }

    public function colaboradoresUsuarios(){
        return $this->belongsToMany(User::class, 'agencias_colaboradores', 'agencia_id', 'usuario_id');
    }

    public function demandas(){
        return $this->hasMany(Demanda::class);
    }

    public function notificacoes(){
        return $this->hasMany(Notificacao::class);
    }
    
    public function prazosAgencias(){
        return $this->hasMany(DemandaTempo::class);
    }

}
