<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Cidade;
use App\Models\Estado;

class InformacaoUsuario extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'agencia_id',
        'cidade_id',
        'estado_id',
    ];

    protected $table = 'informacoes_usuarios';

    public function usuario(){
        return $this->belongsTo(User::class);
    }

    public function cidade(){
        return $this->belongsTo(Cidade::class);
    }

    public function estado(){
        return $this->belongsTo(Estado::class);
    }
}
