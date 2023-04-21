<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Demanda;
use App\Models\User;

class Marca extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'marcas';

    protected $fillable = [
        'nome',
        'cor',
        'logo',
        'ordem',
    ];

    public function demandas(){
        return $this->belongsToMany(Demanda::class, 'demandas_marcas', 'marca_id', 'demanda_id',);
    }

    public function usuarios(){
        return $this->belongsToMany(User::class, 'marcas_usuarios', 'marca_id', 'usuario_id',);
    }

}
