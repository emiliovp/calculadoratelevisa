<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalLogBookMovements extends Model
{
    protected $table = 'cal_log_movimientos';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        "id",
        "cal_ip_address",
        "cal_descripcion",
        "cal_tipo",
        "created_at",
        "cal_user_login_id"
    ];

    public function guardarBitacora($data) {
        $bitacora = new CalLogBookMovements;
        $bitacora->cal_ip_address = $data["ip_address"];
        $bitacora->cal_descripcion = $data["description"];
        $bitacora->cal_tipo = $data["tipo"];
        $bitacora->cal_user_login_id = $data["id_user"];

        $bitacora->save();
    }

    public function movimientos() {
        $query = CalLogBookMovements::get()->toArray();
 
        return $query;
    }

    public function movimientosByRangeDate($dateInit, $dateFish) {
        $query = CalLogBookMovements::select(
            'logbook_movements.ip_address',
            'logbook_movements.description',
            'logbook_movements.tipo',
            'logbook_movements.created_at',
            'users.email'
        )
        ->join('users', 'users.id', '=', 'logbook_movements.id_user')
        ->whereBetween('logbook_movements.created_at', array($dateInit.' 00:00:00', $dateFish.' 23:59:59'))
        ->get()
        ->toArray();
 
        return $query;
    }
}