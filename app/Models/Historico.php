<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Demanda;

class Historico extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'demandas';

    protected $fillable = [
        'inicio',
        'briefing',
        'usuario_id',
        'marca_id',
        'inicio',
        'final',
        'setor_id',
        'prioridade',
    ];

    public function usuario(){
        return $this->belongsTo(User::class);
    }

    public function demanda(){
        return $this->belongsTo(Demanda::class);
    }
    
}
