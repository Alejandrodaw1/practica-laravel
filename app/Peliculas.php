<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Peliculas extends Model
{
    protected $table = 'peliculas';
    protected $primaryKey = 'id';
    protected $fillable = array('id', 'portada', 'nombre', 'duracion', 'anyo');

    public function generos(){
        return $this->belongsToMany("App\Generos");
    }

    public function directores(){
        return $this->belongsToMany('App\Personas', 'personas_directores');
    }

    public function actores(){
        return $this->belongsToMany('App\Personas', 'personas_actores');
    }
}
