<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Demanda;
use App\Models\Agencia;
use App\Models\Questionamento;

class DemandaTempo extends Model
{
    
    public $timestamps = false;

    protected $table = 'demandas_tempos';

    protected $fillable = [
        'usuario_id',
        'demanda_id',
        'status',
        'criado',
        'finalizado'
    ];

    public function demanda(){
        return $this->belongsTo(Demanda::class);
    }

    public function agencia(){
        return $this->belongsTo(Agencia::class);
    }

    public function comentarios(){
        return $this->belongsToMany(Questionamento::class, 'comentarios_pautas', 'demandapauta_id', 'comentario_id');
    }
}
