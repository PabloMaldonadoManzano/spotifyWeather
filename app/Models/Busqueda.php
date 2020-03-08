<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BusquedaResultado;
use App\Models\BusquedaCancion;

class Busqueda extends Model
{

    protected $table = 'busquedas';

    protected $fillable = [
        'city',
        'lat',
        'lon'
    ];

    public function resultado()
    {
        return $this->hasOne(BusquedaResultado::class, 'busqueda_id', 'id');
    }

    public function canciones()
    {
        return $this->hasMany(BusquedaCancion::class, 'busqueda_id', 'id');
    }

}
