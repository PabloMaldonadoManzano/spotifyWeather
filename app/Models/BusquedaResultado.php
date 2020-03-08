<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Busqueda;

class BusquedaResultado extends Model
{

    protected $table = 'busquedas_resultados';

    protected $fillable = [
        'busqueda_id',
        'city',
        'lat',
        'lon',
        'temp',
        'timezone',
        'type'
    ];

    public function busqueda()
    {
        return $this->hasOne(Busqueda::class, 'id', 'busqueda_id');
    }

}
