<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FusCapturistaModel extends Model
{
    protected $table = 'fus_capturista';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'no_empleado',
        'user_red',
        'created_at',
    ];
    public $timestamps = false;
    public function registrarCapturista($emp)
    {
        $resp = FusCapturistaModel::select('id','no_empleado','user_red')
        ->where("no_empleado", '=', $emp->noEmployee)
        ->get()
        ->toArray();
        if (count($resp) == 0) {
            $cap = new FusCapturistaModel;
            $cap->no_empleado = $emp->noEmployee;
            $cap->user_red = $emp->name;
            $cap->save();
            $data = $cap->id;
        }
        else {
            $data = $resp[0]['id'];
        }
        
        return $data;
    }
}
