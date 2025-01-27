<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    protected $table = 'categorias';


    // app/Models/Categoria.php
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gastos()
    {
        return $this->hasMany(Gasto::class, 'id_categoria'); //RELACIÓN DE UNO A MUCHOS CON LA TABLA GASTO
    }
}
