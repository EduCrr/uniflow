<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Demanda;
use App\Models\User;

class LinhaTempo extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'linha_tempo';

    protected $fillable = [
        'status',
        'demanda_id',
        'usuario_id',
        'code',
        'criado',
    ];

     public function demanda(){
        return $this->belongsTo(Demanda::class);
    }

     public function usuario(){
        return $this->belongsTo(User::class);
    }
}
