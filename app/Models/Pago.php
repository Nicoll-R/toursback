<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pago';
    protected $primaryKey = 'id_pago'; // Clave primaria
    public $incrementing = true; // AUTO_INCREMENT
    protected $keyType = 'integer'; // Tipo de la clave primaria

    protected $fillable = ['id_reserva', 'monto', 'metodo', 'feca_pago']; // Campos que se pueden llenar masivamente

    //tendria que importar el modelo?
    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }
}
