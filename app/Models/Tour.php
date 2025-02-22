<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $table = 'tour';
    protected $primaryKey = 'id_tour'; // Clave primaria
    public $incrementing = true; // AUTO_INCREMENT
    protected $keyType = 'integer'; // Tipo de la clave primaria

    protected $fillable = ['nombreto', 'descripcion', 'precio', 'duracion', 'fecha_inicio', 'destino']; // Campos que se pueden llenar masivamente

    //tendria que importar el modelo?
    public function reserva()
    {
        return $this->hasMany(Reserva::class);
    }
}
