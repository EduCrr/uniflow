<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InformacaoUsuario;
use App\Models\Cidade;

class Estado extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'estados';

    public function usuario(){
        return $this->belongsToMany(Estado::class, 'informacoes_usuarios', 'estado_id', 'usuario_id');
    }

    public function cidade(){
        return $this->hasMany(Cidade::class);
    }

}
