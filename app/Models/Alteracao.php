<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Demanda;

class Alteracao extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'alteracoes';

    protected $fillable = [
        'demanda_id',
    ];

    public function demanda(){
        return $this->belongsTo(Demanda::class);
    }

}
