<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Demanda;
use App\Models\User;

class DemandaImagem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'demandas_imagens';

    protected $fillable = [
        'imagem',
        'demanda_id',
        'usuario_id'
    ];

    public function demanda(){
        return $this->belongsTo(Demanda::class);
    }

    public function usuario(){
        return $this->belongsTo(User::class);
    }
}
