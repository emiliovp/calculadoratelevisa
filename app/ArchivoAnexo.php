<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchivoAnexo extends Model
{
    protected $table = "fus_archivo_anexo";
    
    protected $fillable = [
        'id',
        'path',
        'id_relacion',
        'tipo',
        'created_at'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function guardar($nombre, $id, $tipo) {
        $anexo = new ArchivoAnexo;
        $anexo->path = $nombre;
        $anexo->id_relacion = $id;
        $anexo->tipo = $tipo;
        $anexo->save();
    }

    public function getAllsSRCAnexo($idapp, $idfus) {
        $path = ArchivoAnexo::select('path')
        ->where('applications_id', '=', $idapp)
        ->where('fus_sysadmin_wtl_id', '=', $idfus)
        ->get()
        ->toArray();

        return $path;
    }
}
