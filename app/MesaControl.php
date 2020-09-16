<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MesaControl extends Model
{
    protected $table = 'tcs_cat_helpdesk';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        "id",
        "name",
        "alias",
        "description",
        "status",
        "created_at",
        "updated_at",
    ];

    public function mesascontrol() {
        $mesascontrol = MesaControl::select(
            "id",
            "name",
            "alias",
            "description",
            DB::raw("UPPER(status) as status")
        )
        ->get()
        ->toArray();
        return $mesascontrol;
    }

    public function getMesaForAuto() {
        return MesaControl::where('status', '=', 'Activo')->get()->toArray();
    }

    public function getMesaById($id) {
        return MesaControl::where('id', '=', $id)->get()->toArray();
    }

    public function altaMesaControl($data) {
        $mesacontrol = new MesaControl;
        $mesacontrol->name = mb_strtoupper($data["name"]);
        $mesacontrol->alias = mb_strtoupper($data["alias"]);
        $mesacontrol->description = mb_strtoupper($data["description"]);

        if($mesacontrol->save()) {
            return true;
        }

        return false;
    }

    public function editarMesaControl($data) {
        $mesacontrol = MesaControl::find($data["id"]);
        $mesacontrol->name = mb_strtoupper($data["name"]);
        $mesacontrol->alias = mb_strtoupper($data["alias"]);
        $mesacontrol->description = mb_strtoupper($data["description"]);
        $mesacontrol->updated_at = date("Y-m-d H:m:i");
        
        if($mesacontrol->save()) {
            return true;
        }
        
        return false;
    }
    public function editarStatusMesaControl($data) {
        $mesacontrol = MesaControl::find($data["id"]);
        $mesacontrol->status = $data["status"];
        $mesacontrol->updated_at = date("Y-m-d H:m:i");
        
        if($mesacontrol->save()) {
            return true;
        }
        
        return false;
    }
}