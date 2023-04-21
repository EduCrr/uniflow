<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandaMarca extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'demandas_marcas';

    protected $fillable = [
        'marca_id',
        'demanda_id',
    ];
}
