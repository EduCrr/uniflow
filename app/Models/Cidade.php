<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InformacaoUsuario;
use App\Models\Estado;

class Cidade extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'cidades';

    public function usuario(){
        return $this->belongsToMany(Cidade::class, 'informacoes_usuarios', 'cidade_id', 'usuario_id');
    }

    public function estado(){
        return $this->belongsTo(Estado::class);
    }
}
