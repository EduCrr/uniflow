<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Demanda;
use App\Models\Notificacao;
use App\Models\DemandaImagem;
use App\Models\Agencia;
use App\Models\Cidade;
use App\Models\Estado;
use App\Models\Marca;
use App\Models\Questionamento;
use App\Models\Resposta;
use App\Models\LinhaTempo;
use App\Models\AdminAgencia;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'email',
        'password',
        'criado',
        'tipo',
        'token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $timestamps = false;
    protected $table = 'usuarios';


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function usuariosAgencias(){
        return $this->belongsToMany(Agencia::class, 'agencias_usuarios', 'usuario_id', 'agencia_id');
    }

    public function colaboradoresAgencias(){
        return $this->belongsToMany(Agencia::class, 'agencias_colaboradores', 'usuario_id', 'agencia_id');
    }

    public function usuarioDemandas(){
        return $this->belongsToMany(Demanda::class, 'demandas_usuarios', 'usuario_id', 'demanda_id');
    }

    public function marcas(){
        return $this->belongsToMany(Marca::class, 'marcas_usuarios', 'usuario_id', 'marca_id',);
    }

    public function imagens(){
        return $this->hasMany(DemandaImagem::class);
    }

    public function cidade(){
        return $this->belongsToMany(Cidade::class, 'informacoes_usuarios', 'usuario_id', 'cidade_id');
    }

    public function estado(){
        return $this->belongsToMany(Estado::class, 'informacoes_usuarios', 'usuario_id', 'estado_id');
    }

    public function usuarioQuestionamentos(){
        return $this->hasMany(Questionamento::class);
    }

    public function linhaTempo(){
        return $this->hasMany(LinhaTempo::class);
    }

    public function respostas(){
        return $this->hasMany(Resposta::class);
    }

    public function notificacoes(){
        return $this->hasMany(Notificacao::class);
    }

    public function adminUserAgencia(){
        return $this->hasMany(AdminAgencia::class, 'usuario_id');
    }
}
