<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FusUserLogin extends Model
{
    public $tipo = null;
    protected $table = "fus_user_login";

    protected $fillable = [
        'id',
        'user_red',
        'num_employee',
        'created_at',
        'updated_at',
        'estatus'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;


    public function getIdUser($noEmployee) {
        $sql = FusUserLogin::Where('num_employee', '=', $noEmployee)->first();

        if($sql == null) {
            return 0;
        } else {
            return $sql->id;
        }
    }

    public function getIdByNameUser($user_red) {
        $sql = FusUserLogin::select(['fus_perfiles.modulos_acceso','fus_user_login.*', 
        'fus_areas_perfiles.area AS tipo_user', 'fus_perfiles.fus_areas_perfiles_id', 'fus_perfiles.perfil'])
        ->join('fus_perfiles', 'fus_perfiles.id', '=', 'fus_user_login.fus_perfiles_id')
        ->leftJoin('fus_areas_perfiles', 'fus_areas_perfiles.id', '=', 'fus_perfiles.fus_areas_perfiles_id')
        ->whereRaw('upper(fus_user_login.user_red) = upper("'.$user_red.'")')
        ->where('estatus', '=', 1)
        ->first();

        $result = null;
        
        if($sql){
            $result = $sql->toArray();
        }
        
        return $result;
    }

    public function getByUserRed($userRed, $numEmp) {
        $sql = FusUserLogin::whereRaw('upper(user_red) = upper("'.$userRed.'")')
        ->where('estatus', '=', 1)
        ->get()
        ->toArray();

        if(
            count($sql) > 0 &&
            strtoupper($sql[0]['user_red']) == strtoupper($userRed) &&
            $sql[0]['num_employee'] == $numEmp
        ) {
            return true;
        }

        return false;
    }

    public function getByUser($numEmp) {
        $sql = FusUserLogin::select('user_red','num_employee','estatus')
        ->where('num_employee', '=', $numEmp)
        ->where('estatus', '=', 1)
        ->get()
        ->toArray();

        if(count($sql) == 1) {
            return $sql;
        }
        return 0;
    }
    
    public function getDataUserByName($userred) {
        $sql = FusUserLogin::select(['fus_perfiles.modulos_acceso','fus_user_login.user_red','fus_user_login.num_employee','fus_areas_perfiles.area AS tipo_user','fus_user_login.estatus'])
        ->join('fus_perfiles', 'fus_perfiles.id', '=', 'fus_user_login.fus_perfiles_id')
        ->leftJoin('fus_areas_perfiles', 'fus_areas_perfiles.id', '=', 'fus_perfiles.fus_areas_perfiles_id')
        ->whereRaw('upper(user_red) = upper("'.$userred.'")')
        ->where('estatus', '=', 1)
        ->where('fus_perfiles.estado', '=', 'Activo')
        ->where('fus_areas_perfiles.estado', '=', 'Activo')
        ->orWhere('estatus', '=', 1)
        ->whereRaw('upper(user_red) = upper("'.$userred.'")')
        ->where('fus_perfiles.perfil', '=', 'root')
        ->get()
        ->toArray();
        
        if(count($sql) > 0) {
            return $sql;
        }
        return 0;
    }

    public function getUserExisByUsername($username) {
        $sql = FusUserLogin::select('user_red','num_employee','fus_perfiles_id as tipo_user')
        ->whereRaw('upper(user_red) = upper("'.$username.'")')
        ->where('estatus', '=', 1)
        ->get()
        ->toArray();

        return count($sql);
    }

    public function getUserActive($num = null){
        $this->tipo = $num;
        return FusUserLogin::select(['fus_user_login.id', 
            'fus_user_login.user_red', 
            'fus_user_login.num_employee', 
            'fus_areas_perfiles.area as tipo_user', 
            'fus_user_login.estatus',
            'activedirectory_employees.displayname',
            DB::raw('if(fus_perfiles.fus_areas_perfiles_id is null, fus_perfiles.perfil, concat(fus_perfiles.perfil," - ",fus_areas_perfiles.area)) as area')
            ])
        ->leftJoin("activedirectory_employees", DB::raw("UPPER(activedirectory_employees.samaccountname)"),"=", DB::raw("UPPER(fus_user_login.user_red)"))
        ->join('fus_perfiles', 'fus_perfiles.id', '=', 'fus_user_login.fus_perfiles_id')
        ->leftJoin('fus_areas_perfiles', 'fus_areas_perfiles.id', '=', 'fus_perfiles.fus_areas_perfiles_id')
        ->distinct()
        ->where('fus_user_login.estatus','=',1)
        ->where('fus_user_login.user_red', '<>', 'root')
        ->where(function($query) {
            if($this->tipo != null || $this->tipo ==0){
                $query->where('fus_areas_perfiles.area', '=', $this->tipo);
            }else{
                $query->whereRaw('1 = 1');
            }
        })
        ->get()
        ->toArray();
    }
    public function getUserActiveroot(){
        return FusUserLogin::select(['fus_user_login.id', 
            'fus_user_login.user_red', 
            'fus_user_login.num_employee', 
            'fus_areas_perfiles.area as tipo_user', 
            'fus_user_login.estatus',
            'activedirectory_employees.displayname',
            DB::raw('if(fus_perfiles.fus_areas_perfiles_id is null, fus_perfiles.perfil, concat(fus_perfiles.perfil," - ",fus_areas_perfiles.area)) as area')
            ])
        ->leftJoin("activedirectory_employees", DB::raw("UPPER(activedirectory_employees.samaccountname)"),"=", DB::raw("UPPER(fus_user_login.user_red)"))
        ->join('fus_perfiles', 'fus_perfiles.id', '=', 'fus_user_login.fus_perfiles_id')
        ->leftJoin('fus_areas_perfiles', 'fus_areas_perfiles.id', '=', 'fus_perfiles.fus_areas_perfiles_id')
        ->distinct()
        ->where('fus_user_login.estatus','=',1)
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
        $data = FusUserLogin::find($val);
        $data->estatus = 3;
        $data->updated_at = date('yy-m-d H:m:s');
        $data->save();
    }
}
