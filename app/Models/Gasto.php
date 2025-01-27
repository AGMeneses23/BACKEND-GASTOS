<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;
    protected $table = 'gasto';

    //DEFINIR CAMPOS QUE PUEDEN SER LLENADOS MASIVAMENTE
    protected $fillable = [
        'id_users',
        'id_categoria',
        'fecha',
        'monto',
        'descripcion'
    ];

    //RELACIÓN DE GASTO A USUARIO
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    //RELACIÓN: UN GASTO PERTENECE A UNA CATEGORÍA
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }
}
