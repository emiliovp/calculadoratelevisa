<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ActivedirectoryEmployees extends Model
{
    protected $table="activedirectory_employees";

    protected $fillable = [
        'id',
        'username',
        'employee_number',
        'group',
        'name',
        'lastname1',
        'lastname2',
        'created',
        'extensionAttribute15',
        'email'
    ];

    protected $hidden = [];

    public $timestamps = false;
    protected $primaryKey = 'id';
    public $numbersEmpNotifys;

    public function getmails($numbersEmployees) {
        $this->numbersEmpNotifys = $numbersEmployees;

        $emails = array();

        $emailsResp = ActivedirectoryEmployees::select('extensionAttribute15', 'email')
        ->where(function ($query) {
            $query->whereIn('extensionAttribute15', $this->numbersEmpNotifys); // 4802408,5600653,4108627,5600746
        })
        ->get()
        ->toArray();

        foreach ($emailsResp as $key => $value) {
            $emails[] = array("number" => $value["extensionAttribute15"], "email" => $value["email"]);
        }

        return $emails;
    }
    public function correo($id)
    {
        $sql= AutorizadorResponsable::select('email AS correo')
        ->join('activedirectory_employees','tcs_autorizador_responsable.number','=','activedirectory_employees.extensionAttribute15')
        ->where('tcs_autorizador_responsable.status','=','1')
        ->where('tcs_autorizador_responsable.tcs_request_fus_id','=',$id)
        ->get()
        ->toArray();
        return $sql;
    }

    public function getEmployeeByNumEmp($numEmp) {
        return ActivedirectoryEmployees::where('employee_number', '=', $numEmp)->get()->toArray();
    }
    public function getNameEmployeeByNumEmp($numEmp, $username) {
        $query = ActivedirectoryEmployees::select("displayname")->where('employee_number', '=', $numEmp)->where('username', '=', $username)->limit(1)->get()->toArray();
        if(isset($query[0])) {
            return $query[0]["displayname"];
        } else {
            return "";
        }
    }
    public function getEmployeeByCuenta($cuenta) {
        return ActivedirectoryEmployees::whereRaw("email LIKE '".$cuenta."%'")->get()->toArray();
    }
    public function getEmployeeByNumEmpFw($numEmp) {
        return ActivedirectoryEmployees::select(DB::raw('cast(employee_number  AS signed ) as numero'),
                'displayname as nombre',
                'email as correo','samaccountname AS cuenta')
            ->whereRaw("cast(employee_number  AS signed ) = cast(".$numEmp." AS signed)")
            ->limit(5)
            ->get()
            ->toArray();
    }
}
