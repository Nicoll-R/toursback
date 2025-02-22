<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reserva';
    protected $primaryKey = 'id_reserva'; // Clave primaria
    public $incrementing = true; // AUTO_INCREMENT
    protected $keyType = 'integer'; // Tipo de la clave primaria

    protected $fillable = ['id_user', 'id_tour', 'fecha_reserva', 'estado']; // Campos que se pueden llenar masivamente


    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function Tour()
    {
        return $this->belongsTo(Tour::class);
    }
}
