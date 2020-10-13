<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CalCapturista extends Model
{
    protected $table = 'cal_capturista';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'cal_no_empleado',
        'cal_user_red',
        'created_at',
    ];
    public $timestamps = false;
    public function registrarCapturista($emp)
    {
        $resp = CalCapturista::select('id','cal_no_empleado','cal_user_red')
        ->where("cal_no_empleado", '=', $emp->noEmployee)
        ->get()
        ->toArray();
        if (count($resp) == 0) {
            $cap = new CalCapturista;
            $cap->cal_no_empleado = $emp->noEmployee;
            $cap->cal_user_red = $emp->name;
            $cap->save();
            $data = $cap->id;
        }
        else {
            $data = $resp[0]['id'];
        }
        
        return $data;
    }
}
