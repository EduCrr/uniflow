<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Demanda;


class DemandaReaberta  extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'demandas_reabertas';

    protected $fillable = [
        'demanda_id',
        'sugerido',
        'excluido'
    ];

    public function demandas(){
        return $this->belongsTo(Demanda::class);
    }
}
