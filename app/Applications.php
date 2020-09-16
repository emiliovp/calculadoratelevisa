<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\ControlConfigFuseApp;

class Applications extends Model
{
    protected $table="applications";

    protected $fillable = [
        'id',
        'name',
        'alias',
        'active',
        'fus'
    ];

    protected $hidden = ["responsability_id", "instance_id"];
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $apps = "";

    public function getNameApplicationById($id) {
        $app = Applications::select('alias')
        ->where('id', '=', $id)
        ->first();

        return $app['alias'];
    }

    public function getApplications() {
        return Applications::select('id','name',
        DB::raw("TRIM(REPLACE(REPLACE(REPLACE(TRIM(alias),'\t',''),'\n',''),'\r','')) as alias"),
        'active',
        'fus'
        )
        ->where('fus', '=', 1)
        ->orderBy('alias', 'asc')
        ->get()
        ->toArray();
    }

    public function getApplicationsRespCat() {
        $controlConfigFuseApp = new ControlConfigFuseApp;
        $appsAdmonMesa = $controlConfigFuseApp->usuarioByNoEmpName(Auth::user()->noEmployee, Auth::user()->name);

        if($appsAdmonMesa["todo"] == 1) {
            $this->apps = null;
        } else {
            $this->apps = json_decode($appsAdmonMesa["apps"], true);
        }

        return Applications::selectRaw("id, TRIM(REPLACE(REPLACE(REPLACE(TRIM(alias),'\t',''),'\n',''),'\r','')) as alias")
        ->where('fus', '=', 1)
        ->where(function($query) {
            if($this->apps != null || is_array($this->apps) === true){
                $query->whereIn('id', $this->apps);
            } else {
                $query->whereNotNull('id');
            }
        })
        ->orderBy('alias', 'asc')
        ->get()
        ->toArray();
    }
}
