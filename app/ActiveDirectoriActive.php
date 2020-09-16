<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\settingGeneral;

class ActiveDirectoriActive extends Model
{
    protected $table="activedirectory_employees_active";

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
        'email',
        'passwordlastchanged',
        'account_type',
        'user_type',
        'passwordlastset',
        'enabled',
        'lock_date',
        'account_expires',
        'useraccountcontrol',
        'directreports',
        'manager',
        'company',
        'samaccountname',
        'displayname',
        'info',
        'ratification_date',
        'consecutive'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;
    public function concilia_ad()
    {
        $sql = ActiveDirectoriActive::select(
            'extensionAttribute15 AS employee_number',
            'username',
            DB::raw("concat(lastname1,' ',lastname2) AS name"),
            DB::raw("'Directorio activo' AS aplicacion"))
            ->whereRaw('if (trim(UPPER(company)) = "TELEVISA TALENTO PRODUCCIONES SAN ANGEL" or trim(UPPER(company)) = "INTELLECTUS PRODUCCIONES SAN ANGEL",1,0) <> 1')
            ->whereRaw('extensionAttribute15 not in (select `employee_number` from `compare_labora_concilia`)')
            ->whereRaw('samaccountname not in (SELECT user_red FROM cuentas_excluidas WHERE estatus = 1)')
            ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)')
            ->where('user_type', '=', 'I')
            ->where('enabled', '=', 'True')
            ->whereIn('account_type', ['U', 'E'])
            ->get()
            ->toArray();

        return $sql;
    }
    public static function query()
    {
        $sql = ActiveDirectoriActive::select(
            'extensionAttribute15 AS employee_number', 
            'username',
            DB::raw("concat(lastname1,' ',lastname2) AS name"),
            DB::raw("'Directorio activo' AS aplicacion"))
            ->whereRaw('if (trim(UPPER(company)) = "TELEVISA TALENTO PRODUCCIONES SAN ANGEL" or trim(UPPER(company)) = "INTELLECTUS PRODUCCIONES SAN ANGEL",1,0) <> 1')
            ->whereRaw('extensionAttribute15 not in (select `employee_number` from `compare_labora_concilia`)')
            ->whereRaw('samaccountname not in (SELECT user_red FROM cuentas_excluidas WHERE estatus = 1)')
            ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)')
            ->whereIn('account_type', ['U', 'E'])
            ->where('enabled', '=', 'True')
            ->where('user_type', '=', 'I');

        return $sql;
    }
    public function query2()
    {
        $sql = ActiveDirectoriActive::select('samaccountname', 
        'displayname','passwordlastchanged', 'extensionAttribute15',
        DB::raw('DATE_FORMAT(passwordlastset, "%d-%m-%Y") AS passwordlastset'),
        DB::raw('TIMESTAMPDIFF(DAY, passwordlastchanged, CURDATE()) as dias ')
        )
        ->where('enabled', '=', 'True')
        ->whereIn('account_type', ['U', 'E'])
        ->whereRaw('TIMESTAMPDIFF(DAY, passwordlastchanged, CURDATE()) > (SELECT settings FROM general_settings WHERE id = 1)')
        ->whereRaw('samaccountname not in (SELECT user_red FROM cuentas_excluidas WHERE estatus = 1)')
        ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)')
        ->get()
        ->toArray();

        return $sql;
    }
    public static function query2export()
    {
        $sql = ActiveDirectoriActive::select('samaccountname', 
        'displayname',
        'extensionAttribute15',
        DB::raw('DATE_FORMAT(passwordlastset, "%d-%m-%Y") AS passwordlastset')
        )
        ->where('enabled', '=', 'True')
        ->whereIn('account_type', ['U', 'E'])
        ->whereRaw('TIMESTAMPDIFF(DAY, passwordlastchanged, CURDATE()) > (SELECT settings FROM general_settings WHERE id = 1)')
        ->whereRaw('samaccountname not in (SELECT user_red FROM cuentas_excluidas WHERE estatus = 1)')
        ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)');

        return $sql;
    }
    public function notificacionCuentasServicio365() {
        $diasConfig = new settingGeneral;
        $dias365 = $diasConfig->getValueSetting('days_without_password_change_service');
        
        $sql = ActiveDirectoriActive::select(
            'samaccountname AS username',
            'manager',
            'account_type',
            DB::raw('DATE_FORMAT(passwordlastset, "%d-%m-%Y") AS passwordlastset'),
            DB::raw('datediff(CURDATE(), passwordlastset) AS diferencia_dias')
        )
        ->where('enabled', '=', 'True')
        ->where('account_type', '=', 'S')
        ->whereRaw('datediff(CURDATE(), passwordlastset) > '.$dias365)
        ->whereRaw('samaccountname not in (SELECT user_red FROM cuentas_excluidas WHERE estatus = 1)')
        ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)')
        ->get()
        ->toArray();
        
        if(count($sql) > 0) {
            return $sql;
        }
        return false;
    }

    public static function notificacionCuentasServicio365Export() {
        $diasConfig = new settingGeneral;
        $dias365 = $diasConfig->getValueSetting('days_without_password_change_service');
        
        $sql = ActiveDirectoriActive::select(
            'samaccountname AS username',
            'manager',
            DB::raw('IF(account_type = "S", "Servicio", "Usuario") AS account_type'),
            DB::raw('DATE_FORMAT(passwordlastset, "%d-%m-%Y") AS passwordlastset')
        )
        ->where('enabled', '=', 'True')
        ->where('account_type', '=', 'S')
        ->whereRaw('datediff(CURDATE(), passwordlastset) > '.$dias365)
        ->whereRaw('samaccountname not in (SELECT user_red FROM cuentas_excluidas WHERE estatus = 1)')
        ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)');
        
        return $sql;
    }

    public function notificacionCuentasSinExpirar() {
        $sql = ActiveDirectoriActive::select(
            'samaccountname AS username',
            'displayname',
            'extensionAttribute15 AS employee_number',
            'manager',
            // DB::raw('IF(account_type = "S", "Servicio", "Usuario") AS account_type')
            DB::raw('
                CASE
                    WHEN account_type = "S" THEN "Servicio"
                    WHEN account_type = "U" THEN "Usuario"
                    WHEN account_type = "E" THEN "Especial"
                END
                AS account_type
            ')
        )
        ->where('enabled', '=', 'True')
        ->where('passwordneverexpires', '=', 'True')
        ->whereRaw('samaccountname not in (SELECT user_red FROM cuentas_excluidas WHERE estatus = 1)')
        ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)')
        ->get()
        ->toArray();
        
        if(count($sql) > 0) {
            return $sql;
        }
        return false;
    }

    public static function notificacionCuentasSinExpirarExport() {
        $sql = ActiveDirectoriActive::select(
            'samaccountname AS username',
            'displayname',
            'extensionAttribute15 AS employee_number',
            'manager',
            // DB::raw('IF(account_type = "S", "Servicio", "Usuario") AS account_type')
            DB::raw('
                CASE
                    WHEN account_type = "S" THEN "Servicio"
                    WHEN account_type = "U" THEN "Usuario"
                    WHEN account_type = "E" THEN "Especial"
                END
                AS account_type
            ')
        )
        ->where('enabled', '=', 'True')
        ->where('passwordneverexpires', '=', 'True')
        ->whereRaw('samaccountname not in (SELECT user_red FROM cuentas_excluidas WHERE estatus = 1)')
        ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)');
        
        return $sql;
    }

    public function cuentas_bloqueadas()
    {
        $sql = ActiveDirectoriActive::select(
            'samaccountname', 'displayname', 
            'extensionattribute15', 'manager', 
            'lock_date', 'account_type', 
            DB::raw('TIMESTAMPDIFF(DAY, lock_date, CURDATE()) as dias ')
        )
        ->where('enabled', '=', 'False')
        ->whereIn('account_type', ['S', 'E','U'])
        ->whereRaw('TIMESTAMPDIFF(DAY, lock_date, CURDATE()) > (SELECT settings FROM general_settings WHERE id = 3)')
        ->whereRaw('samaccountname not in (SELECT user_red FROM cuentas_excluidas WHERE estatus = 1)')
        ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)')
        ->get()
        ->toArray();

        return $sql;
    }
    
    public static function cuentas_bloqueadas2()
    {
        $sql = ActiveDirectoriActive::select(
            'samaccountname', 
            'displayname', 
            'extensionattribute15', 
            'manager', 
            // DB::raw('IF(account_type = "S", "Servicio", "Usuario") AS account_type')
            DB::raw('
                CASE
                    WHEN account_type = "S" THEN "Servicio"
                    WHEN account_type = "U" THEN "Usuario"
                    WHEN account_type = "E" THEN "Especial"
                END
                AS account_type
            '),
            DB::raw('TIMESTAMPDIFF(DAY, lock_date, CURDATE()) as dias ')
        )
        ->where('enabled', '=', 'False')
        ->whereIn('account_type', ['S', 'E','U'])
        ->whereRaw('TIMESTAMPDIFF(DAY, lock_date, CURDATE()) > (SELECT settings FROM general_settings WHERE id = 3)')
        ->whereRaw('samaccountname not in (SELECT user_red FROM cuentas_excluidas WHERE estatus = 1)')
        ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)');

        return $sql;
    }
    
    public function cuentasSinConfigurarPass() {
        $sql = ActiveDirectoriActive::select(
            'samaccountname AS username',
            'displayname',
            'extensionAttribute15 AS employee_number',
            'manager',
            // DB::raw('IF(account_type = "S", "Servicio", "Usuario") AS account_type')
            DB::raw('
                CASE
                    WHEN account_type = "S" THEN "Servicio"
                    WHEN account_type = "U" THEN "Usuario"
                    WHEN account_type = "E" THEN "Especial"
                END
                AS account_type
            ')
        )
        ->where('enabled', '=', 'True')
        ->where('useraccountcontrol', '=', 544)
        ->whereRaw('samaccountname not in (SELECT user_red FROM cuentas_excluidas WHERE estatus = 1)')
        ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)')
        ->get()
        ->toArray();
        
        if(count($sql) > 0) {
            return $sql;
        }

        return false;
    }

    public static function cuentasSinConfigurarPassExport() {
        $sql = ActiveDirectoriActive::select(
            'samaccountname AS username',
            'displayname',
            'extensionAttribute15 AS employee_number',
            'manager',
            // DB::raw('IF(account_type = "S", "Servicio", "Usuario") AS account_type')
            DB::raw('
                CASE
                    WHEN account_type = "S" THEN "Servicio"
                    WHEN account_type = "U" THEN "Usuario"
                    WHEN account_type = "E" THEN "Especial"
                END
                AS account_type
            ')
        )
        ->where('enabled', '=', 'True')
        ->where('useraccountcontrol', '=', 544)
        ->whereRaw('samaccountname not in (SELECT user_red FROM cuentas_excluidas WHERE estatus = 1)')
        ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)');

        return $sql;
    }

    public function cuentasSinResponsable() {
        $sql = ActiveDirectoriActive::select(
            'samaccountname AS username',
            'displayname',
            'extensionAttribute15 AS employee_number',
            'displayname',
            DB::raw('
                CASE
                WHEN account_type = "S" THEN "Servicio"
                WHEN account_type = "U" THEN "Usuario"
                WHEN account_type = "E" THEN "Especial"
                END
                AS account_type
            ')
        )
        ->where('enabled', '=', 'True')
        ->whereIn('account_type', ['S', 'E','U'])
        ->whereRaw("(manager IS NULL OR manager = '')")
        ->whereRaw('samaccountname not in (SELECT user_red FROM cuentas_excluidas WHERE estatus = 1)')
        ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)')
        ->get()
        ->toArray();
        
        if(count($sql) > 0) {
            return $sql;
        }
        return false;
    }

    public static function cuentasSinResponsableExport() {
        $sql = ActiveDirectoriActive::select(
            'samaccountname AS username',
            'displayname',
            DB::raw('
                CASE
                    WHEN account_type = "S" THEN "Servicio"
                    WHEN account_type = "U" THEN "Usuario"
                    WHEN account_type = "E" THEN "Especial"
                END
                AS account_type
            ')
        )
        ->where('enabled', '=', 'True')
        ->whereIn('account_type', ['S', 'E','U'])
        ->whereRaw("(manager IS NULL OR manager = '')")
        ->whereRaw('samaccountname not in (SELECT user_red FROM cuentas_excluidas WHERE estatus = 1)')
        ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)');
        
        return $sql;
    }

    public function cuentasServiciosDependientesDeBajas($nombre) {
        $sql = ActiveDirectoriActive::select(
            'manager',
            'samaccountname',
            'displayname',
            'directreports'
        )
        ->where('directreports','LIKE','%'.$nombre.'%')
        ->where('account_type','=','S')
        ->whereRaw('employee_number not in (SELECT employee_number FROM cuentas_excluidas)')
        ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)')
        ->get()
        ->toArray();

        return $sql;
    }

    public static function cuentasServiciosDependientesDeBajasExport($nombre) {
        $sql = ActiveDirectoriActive::select(
            'manager',
            'samaccountname',
            'displayname',
            'directreports'
        )
        ->where('directreports','LIKE','%'.$nombre.'%')
        ->where('account_type','=','S')
        ->whereRaw('employee_number not in (SELECT employee_number FROM cuentas_excluidas)')
        ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)');

        return $sql;
    }

    public function concilia_externos_ad() {
        $sql = ActiveDirectoriActive::select(
            'extensionAttribute15 AS employee_number',
            'username',
            DB::raw("concat(lastname1,' ',lastname2) AS name"),
            DB::raw("'Directorio activo' AS aplicacion"))
            ->whereRaw('if (trim(UPPER(company)) = "TELEVISA TALENTO PRODUCCIONES SAN ANGEL" or trim(UPPER(company)) = "INTELLECTUS PRODUCCIONES SAN ANGEL",1,0) <> 1')
            ->whereRaw('extensionAttribute15 not in (select `employee_number` from `compare_labora_concilia`)')
            ->whereRaw('extensionAttribute15 not in (select `employee_number` from `cuentas_excluidas`)')
            ->where('user_type', '=', 'E')
            ->where('enabled', '=', 'True')
            ->get()
            ->toArray();

        return $sql;
    }

    public static function concilia_externos_ad_export() {
        $sql = ActiveDirectoriActive::select(
            'extensionAttribute15 AS employee_number',
            'username',
            DB::raw("concat(lastname1,' ',lastname2) AS name"),
            DB::raw("'Directorio activo' AS aplicacion"))
            ->whereRaw('if (trim(UPPER(company)) = "TELEVISA TALENTO PRODUCCIONES SAN ANGEL" or trim(UPPER(company)) = "INTELLECTUS PRODUCCIONES SAN ANGEL",1,0) <> 1')
            ->whereRaw('extensionAttribute15 not in (select `employee_number` from `compare_labora_concilia`)')
            ->whereRaw('extensionAttribute15 not in (select `employee_number` from `cuentas_excluidas`)')
            ->where('enabled', '=', 'True')
            ->where('user_type', '=', 'E');

        return $sql;
    }
    public function ver_ratificacion($var, $fecha)
    {
        $sql = ActiveDirectoriActive::select('extensionAttribute15',
            'ratification_date')
            ->where('extensionAttribute15','=', $var)
            ->where('ratification_date','>', $fecha)
            ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)')
            ->get()
            ->toArray();
             
        return $sql;
    } 

    public function getEmailByDisplayname($manager) {
        $sql = ActiveDirectoriActive::select(
            'email'
        )
        ->where('displayname','=', $manager)
        ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)')
        ->get()
        ->toArray();
        
        return $sql;
    }

    public function getEmployeeByNumEmp($numEmp) {
        return ActiveDirectoriActive::where('employee_number', '=', $numEmp)
        ->where('enabled', '=', 'True')
        ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)')
        ->get()
        ->toArray();
    }
    
    public function getEmployeeByNumEmpFw($numEmp) {
        return ActiveDirectoriActive::select('employee_number as numero','displayname as nombre','email as correo','samaccountname AS cuenta')
        ->whereRaw("employee_number = TRIM(LEADING '0' FROM ".$numEmp.")")
        // ->where('employee_number', '=', $numEmp)
        ->whereRaw('consecutive = (SELECT max(consecutive) from activedirectory_employees_active)')
        ->limit(5)
        ->get()
        ->toArray();
    }
    public function getEmployeeByUsernameFus($name){
        return ActiveDirectoriActive::where('username','LIKE','%'.$name.'%')
        ->get()
        ->toArray();
    
    }
}