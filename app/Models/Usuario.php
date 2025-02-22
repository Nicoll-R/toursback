<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'id_user'; // Clave primaria
    public $incrementing = true; // AUTO_INCREMENT
    protected $keyType = 'integer'; // Tipo de la clave primaria

    protected $fillable = ['name', 'lastname', 'telf', 'direccion']; // Campos que se pueden llenar masivamente

    //tendria que importar el modelo?
    public function reserva()
    {
        return $this->hasMany(Reserva::class);
    }
}
