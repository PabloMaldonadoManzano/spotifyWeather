<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Busqueda;

class BusquedaCancion extends Model
{

    protected $table = 'busquedas_canciones';

    protected $fillable = [
        'busqueda_id',
        'track_id',
        'track',
        'artisat'
    ];

    public function busqueda()
    {
        return $this->hasOne(Busqueda::class, 'id', 'busqueda_id');
    }

}
