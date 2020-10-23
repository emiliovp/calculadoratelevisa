<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CalUserLogin extends Model
{
    public $tipo = null;
    protected $table = "cal_user_login";

    protected $fillable = [
        'id',
        'cal_user_red',
        'cal_num_employee',
        'cal_estado',
        'cal_perfiles_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;


    public function getIdUser($noEmployee) {
        $sql = CalUserLogin::Where('cal_num_employee', '=', $noEmployee)->first();

        if($sql == null) {
            return 0;
        } else {
            return $sql->id;
        }
    }

    public function getIdByNameUser($user_red) {
        $sql = CalUserLogin::select([
            'cal_perfiles.cal_modulos_acceso',
            'cal_user_login.*', 
            'cal_areas_perfiles.cal_area AS tipo_user', 
            'cal_perfiles.cal_areas_perfiles_id', 
            'cal_perfiles.cal_perfil'
        ])
        ->join('cal_perfiles', 'cal_perfiles.id', '=', 'cal_user_login.cal_perfiles_id')
        ->leftJoin('cal_areas_perfiles', 'cal_areas_perfiles.id', '=', 'cal_perfiles.cal_areas_perfiles_id')
        ->whereRaw('upper(cal_user_login.cal_user_red) = upper("'.$user_red.'")')
        ->where('cal_user_login.cal_estado', '=', 1)
        ->first();

        $result = null;
        
        if($sql){
            $result = $sql->toArray();
        }
        
        return $result;
    }

    public function getByUserRed($userRed, $numEmp) {
        $sql = CalUserLogin::whereRaw('upper(cal_user_red) = upper("'.$userRed.'")')
        ->where('cal_estado', '=', 1)
        ->get()
        ->toArray();

        if(
            count($sql) > 0 &&
            strtoupper($sql[0]['cal_user_red']) == strtoupper($userRed) &&
            $sql[0]['cal_num_employee'] == $numEmp
        ) {
            return true;
        }

        return false;
    }

    public function getByUser($numEmp) {
        $sql = CalUserLogin::select('cal_user_red','cal_num_employee','cal_estado')
        ->where('cal_num_employee', '=', $numEmp)
        ->where('cal_estado', '=', 1)
        ->get()
        ->toArray();

        if(count($sql) == 1) {
            return $sql;
        }
        return 0;
    }
    
    public function getDataUserByName($userred) {
        $sql = CalUserLogin::select([
            'cal_perfiles.cal_modulos_acceso',
            'cal_user_login.cal_user_red',
            'cal_user_login.cal_num_employee',
            'cal_areas_perfiles.cal_area AS tipo_user',
            'cal_user_login.cal_estado'
        ])
        ->join('cal_perfiles', 'cal_perfiles.id', '=', 'cal_user_login.cal_perfiles_id')
        ->leftJoin('cal_areas_perfiles', 'cal_areas_perfiles.id', '=', 'cal_perfiles.cal_areas_perfiles_id')
        ->whereRaw('upper(cal_user_red) = upper("'.$userred.'")')
        ->where('cal_user_login.cal_estado', '=', 1)
        ->where('cal_perfiles.cal_estado', '=', 'Activo')
        ->where('cal_areas_perfiles.cal_estado', '=', 'Activo')
        ->orWhere('cal_user_login.cal_estado', '=', 1)
        ->whereRaw('upper(cal_user_red) = upper("'.$userred.'")')
        ->where('cal_perfiles.cal_perfil', '=', 'root')
        ->get()
        ->toArray();
        
        if(count($sql) > 0) {
            return $sql;
        }
        return 0;
    }

    public function getUserExisByUsername($username) {
        $sql = CalUserLogin::select('cal_user_red','cal_num_employee','cal_perfiles_id as tipo_user')
        ->whereRaw('upper(cal_user_red) = upper("'.$username.'")')
        ->where('cal_estado', '=', 1)
        ->get()
        ->toArray();

        return count($sql);
    }

    public function getUserActive($num = null){
        $this->tipo = $num;
        return CalUserLogin::select(['cal_user_login.id', 
            'cal_user_login.cal_user_red', 
            'cal_user_login.cal_num_employee', 
            'cal_areas_perfiles.cal_area as tipo_user', 
            'cal_user_login.cal_estado',
            // 'activedirectory_employees.displayname',
            DB::raw('if(cal_perfiles.cal_areas_perfiles_id is null, cal_perfiles.cal_perfil, concat(cal_perfiles.cal_perfil," - ",cal_areas_perfiles.cal_area)) as area')
        ])
        // ->leftJoin("activedirectory_employees", DB::raw("UPPER(activedirectory_employees.samaccountname)"),"=", DB::raw("UPPER(cal_user_login.cal_user_red)"))
        ->join('cal_perfiles', 'cal_perfiles.id', '=', 'cal_user_login.cal_perfiles_id')
        ->leftJoin('cal_areas_perfiles', 'cal_areas_perfiles.id', '=', 'cal_perfiles.cal_areas_perfiles_id')
        ->distinct()
        ->where('cal_user_login.cal_estado','=',1)
        ->where('cal_user_login.cal_user_red', '<>', 'root')
        ->where(function($query) {
            if($this->tipo != null || $this->tipo ==0){
                $query->where('cal_areas_perfiles.cal_area', '=', $this->tipo);
            }else{
                $query->whereRaw('1 = 1');
            }
        })
        ->get()
        ->toArray();
    }
    public function getUserActiveroot(){
        return CalUserLogin::select(['cal_user_login.id', 
            'cal_user_login.cal_user_red', 
            'cal_user_login.cal_num_employee', 
            'cal_areas_perfiles.cal_area as tipo_user', 
            'cal_user_login.cal_estado',
            // (function() {
                
            // }),
            // 'activedirectory_employees.displayname',
            DB::raw('if(cal_perfiles.cal_areas_perfiles_id is null, cal_perfiles.cal_perfil, concat(cal_perfiles.cal_perfil," - ",cal_areas_perfiles.cal_area)) as area')
        ])
        // ->leftJoin("activedirectory_employees", DB::raw("UPPER(activedirectory_employees.samaccountname)"),"=", DB::raw("UPPER(cal_user_login.cal_user_red)"))
        ->join('cal_perfiles', 'cal_perfiles.id', '=', 'cal_user_login.cal_perfiles_id')
        ->leftJoin('cal_areas_perfiles', 'cal_areas_perfiles.id', '=', 'cal_perfiles.cal_areas_perfiles_id')
        ->distinct()
        ->where('cal_user_login.cal_estado','=',1)
        /*->where(function($query) {
            if($this->tipo != null || $this->tipo ==0){
                $query->where('fus_areas_perfiles.area', '=', $this->tipo);
            }else{
                $query->whereRaw('1 = 1');
            }
        })*/
        ->get()
        ->toArray();
    }
    public function bajaUsr($val){
        date_default_timezone_set('America/Mexico_City');
        $data = CalUserLogin::find($val);
        $data->cal_estado = 3;
        $data->updated_at = date('yy-m-d H:m:s');
        $data->save();
    }
}
