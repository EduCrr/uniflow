<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Demanda;
use App\Models\User;

class Comentario extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'comentarios';

    protected $fillable = [
        'usuario_id',
        'demanda_id',
        'conteudo',
    ];

    public function usuario(){
        return $this->belongsTo(User::class);
    }

    public function demanda(){
        return $this->belongsTo(Demanda::class);
    }
}
