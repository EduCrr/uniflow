<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Demanda;
use App\Models\User;
use App\Models\Resposta;
use App\Models\DemandaTempo;

class Questionamento extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'questionamentos';

    protected $fillable = [
        'usuario_id',
        'demanda_id',
        'descricao',
        'criado',
        'visualizada_ag',
        'visualizada_col',
        'excluido'
    ];

    public function usuario(){
        return $this->belongsTo(User::class);
    }

    public function demanda(){
        return $this->belongsTo(Demanda::class);
    }
    
    public function respostas(){
        return $this->hasMany(Resposta::class);
    }

    public function cometarios(){
        return $this->belongsToMany(Questionamento::class, 'demandas_tempos', 'comentario_id', 'demandapauta_id', );
    }

    public function count_respostas()
    {
        return $this->hasMany(Resposta::class)
            ->where('visualizada_ag', 0)
            ->where('excluido', null)
            ->selectRaw('questionamento_id, count(*) as count')
            ->groupBy('questionamento_id');
    }
    
}
