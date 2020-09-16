<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RelAnexosFus extends Model
{
    protected $table = "rel_anexos_fus";
    
    protected $fillable = [
        'id',
        'path',
        'fus_sysadmin_wtl_id',
        'applications_id',
        'created_at'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function guardar($nombre,$fus,$app) {
        $anexo = new RelAnexosFus;
        $anexo->path = $nombre;
        $anexo->fus_sysadmin_wtl_id = $fus;
        $anexo->applications_id = $app;
        $anexo->save();
    }

    // public function getSRCAnexo($idapp, $idfus) {
    //     $path = RelAnexosFus::select('path')
    //     ->where('applications_id', '=', $idapp)
    //     ->where('fus_sysadmin_wtl_id', '=', $idfus)
    //     ->first();

    //     return $path['path'];
    // }

    public function getAllsSRCAnexo($idapp, $idfus) {
        $path = RelAnexosFus::select('path')
        ->where('applications_id', '=', $idapp)
        ->where('fus_sysadmin_wtl_id', '=', $idfus)
        ->get()
        ->toArray();

        return $path;
    }
    public function getAllsAnexo($idfus) {
        $path = RelAnexosFus::select('path')
        ->where('fus_sysadmin_wtl_id', '=', $idfus)
        ->get()
        ->toArray();
        return $path;
    }
}
