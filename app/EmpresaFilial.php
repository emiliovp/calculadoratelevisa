<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmpresaFilial extends Model
{
    protected $table="empresa_filial";

    protected $fillable = [
        'id', 
        'nombre', 
        'alias', 
        'created_at', 
        'updated_at'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    public function registrar_emp($nom)
    {
        $resp = EmpresaFilial::select('id')
        ->whereRaw("nombre like '%".$nom."%'")
        ->get()
        ->toArray();
        if (count($resp) == 0) {
            $emp = new EmpresaFilial;
            $emp->nombre = $nom;
            $emp->alias = $nom;
            $emp->save();
            $data = $emp->id;
        }
        else {
            $data = $resp[0]['id'];
        }
        return $data;
    }

    public function getEmpresa($id) {
        $empresa = EmpresaFilial::find($id)->toArray();
        return $empresa['nombre'];
    }
}
