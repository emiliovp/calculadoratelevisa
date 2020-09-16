<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RelFusCapturistaModel extends Model
{
    protected $table = 'rel_fus_capturista';
   
    protected $fillable = [
        'fus_capturista_id',
        'fus_sysadmin_wtl_id',
    ];
    public $timestamps = false;
    public function RelFusCapturista($cap,$fus)
    {
        $rfcap = new RelFusCapturistaModel;
        $rfcap->fus_capturista_id = $cap;
        $rfcap->fus_sysadmin_wtl_id = $fus;
        $rfcap->save();
        $data = $rfcap->id;
    }
}
