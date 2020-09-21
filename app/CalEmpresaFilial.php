<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CalEmpresaFilial extends Model
{
    protected $table="cal_empresa_filial";

    protected $fillable = [
        'id', 
        'cal_nombre', 
        'cal_alias', 
        'created_at', 
        'updated_at'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    public function registrar_emp($nom)
    {
        $resp = CalEmpresaFilial::select('id')
        ->whereRaw("cal_nombre like '%".$nom."%'")
        ->get()
        ->toArray();
        if (count($resp) == 0) {
            $emp = new CalEmpresaFilial;
            $emp->cal_nombre = $nom;
            $emp->cal_alias = $nom;
            $emp->save();
            $data = $emp->id;
        }
        else {
            $data = $resp[0]['id'];
        }
        return $data;
    }

    public function getEmpresa($id) {
        $empresa = CalEmpresaFilial::find($id)->toArray();
        return $empresa['cal_nombre'];
    }
}
