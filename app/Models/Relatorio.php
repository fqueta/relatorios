<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relatorio extends Model
{
    use HasFactory;
    use HasFactory;
    protected $fillable = [
        'id_publicador',
        'id_grupo',
        'ativo',
        'ano',
        'mes',
        'participou',
        'publicacao',
        'video',
        'hora',
        'revisita',
        'estudo',
        'privilegio',
        'obs',
        'compilado',
    ];
}
