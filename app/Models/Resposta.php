<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Questionamento;
use App\Models\User;

class Resposta extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'respostas';

    protected $fillable = [
        'usuario_id',
        'questionamento_id',
        'conteudo',
        'criado',
        'visualizada_ag',
        'excluido',
    ];

    public function usuario(){
        return $this->belongsTo(User::class);
    }

    public function questionamentos(){
        return $this->belongsTo(Questionamento::class);
    }
}
