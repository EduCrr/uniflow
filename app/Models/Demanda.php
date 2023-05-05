<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Marca;
use App\Models\Setor;
use App\Models\Notificacao;
use App\Models\Alteracao;
use App\Models\Comentario;
use App\Models\DemandaImagem;
use App\Models\UcaImagem;
use App\Models\DemandaUsuario;
use App\Models\User;
use App\Models\LinhaTempo;
use App\Models\Agencia;
use App\Models\DemandaTempo;
use App\Models\DemandaStatu;
use App\Models\Questionamento;
use App\Models\DemandaReaberta;

class Demanda extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'demandas';
    
    protected $fillable = [
        'titulo',
        'briefing',
        'usuario_id',
        'criador_id',
        'marca_id',
        'inicio',
        'final',
        'prioridade',
        'cor',
        'criado',
        'em_pauta',
        'entregue',
        'em_alteracao',
        'finalizada',
        'excluido'

    ];

    public function marcas(){
        return $this->belongsToMany(Marca::class, 'demandas_marcas', 'demanda_id', 'marca_id');
    }

    public function alteracoes(){
        return $this->hasMany(Alteracao::class);
    }

    public function notificacoes(){
        return $this->hasMany(Notificacao::class);
    }

    public function comentarios(){
        return $this->hasMany(Comentario::class);
    }

    public function status(){
        return $this->hasMany(DemandaStatu::class);
    }

    public function linhaTempo(){
        return $this->hasMany(LinhaTempo::class);
    }

    public function imagens(){
        return $this->hasMany(DemandaImagem::class);
    }

    public function prazosDaPauta(){
        return $this->hasMany(DemandaTempo::class);
    }

    public function questionamentos(){
        return $this->hasMany(Questionamento::class);
    }

    public function demandasReabertas(){
        return $this->hasMany(DemandaReaberta::class);
    }

    public function criador(){
        return $this->belongsTo(User::class);
    }

    public function agencia(){
        return $this->belongsTo(Agencia::class);
    }

    public function demandasUsuario(){
        return $this->belongsToMany(User::class, 'demandas_usuarios', 'demanda_id', 'usuario_id');
    }

    //demandas que o admin agencia cria
    public function demandasUsuarioAdmin(){
        return $this->belongsToMany(User::class, 'admin_demandas_usuarios', 'demanda_id', 'usuario_id');
    }

    public function marcasDemandas(){
        return $this->belongsToMany(Marca::class, 'demandas_marcas', 'demanda_id', 'marca_id');
    }
}
