<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $filleable = ['nombre', 'tipo_documento', 'num_documento', 'direccion', 'telefono', 'email'];

    public function proveedore(){
        return $this->hasOne('App\Proveedor');
    }
}
