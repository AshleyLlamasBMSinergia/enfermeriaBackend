<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesional extends Model
{
    use HasFactory;

    protected $table = 'Profesionales';

    protected $guarded = ['id', 'created_at', 'updated'];

    protected $fillable = [
        'nombre',
        'telefono',
        'correo',
        'cedula',
        'empresa_id',
        'direccion_id',
        'estatus',
        'puesto_id',
    ];

    //Uno a uno polimorfico
    public function historialMedico(){
        return $this->morphOne('App\Models\HistorialMedico', 'pacientable');
    }

    //Uno a uno polimorfico
    public function consulta(){
        return $this->morphOne('App\Models\Consulta', 'pacientable');
    }

     //Uno a Muchos Inversa
     public function puesto(){
        return $this->belongsTo('App\Models\NomPuesto');
    }

    //Uno a uno polimorficab
    public function user(){
        return $this->morphOne('App\Models\User', 'useable');
    }

    //Uno a Muchos
    public function citas(){
        return $this->hasMany('App\Models\Cita');
    }

    //Uno a Muchos
    public function consultas(){
        return $this->hasMany('App\Models\Consultas');
    }

    //Uno a Muchos
    public function dependientes(){
        return $this->hasMany('App\Models\RHDependiente', 'empleado_id');
    }

    //Uno a Muchos
    public function aprobaciones(){
        return $this->hasMany('App\Models\Aprobacion');
    }

    public function requisiciones(){
        return $this->hasMany('App\Models\Requisicion');
    }

    //Uno a uno polimorficab
    public function image(){
        return $this->morphOne('App\Models\Imagen', 'imageable');
    }

    //Muchos a Muchos
    public function inventarios(){
        return $this->belongsToMany('App\Models\Inventario', 'inventario_profesional', 'profesionales_id', 'inventario_id');
    }

    //Uno a muchos
    public function movimientos(){
        return $this->hasMany('App\Models\Movimiento');
    }
}
