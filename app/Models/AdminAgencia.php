<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class AdminAgencia extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'admin_agencias';

    protected $fillable = [
        'usuario_id',
        'excluido'
    ];


}
