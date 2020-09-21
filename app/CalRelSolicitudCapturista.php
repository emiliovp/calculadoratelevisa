<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CalRelSolicitudCapturista extends Model
{
    protected $table = 'cal_rel_solicitud_capturista';
   
    protected $fillable = [
        'cal_capturista_id',
        'cal_solicitud_id'
    ];
    public $timestamps = false;
    
    public function RelCalCapturista($cap,$fus)
    {
        $rfcap = new CalRelSolicitudCapturista;
        $rfcap->cal_capturista_id = $cap;
        $rfcap->cal_solicitud_id = $fus;
        $rfcap->save();
        $data = $rfcap->id;
    }
}
