<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogBookMovements extends Model
{
    protected $table = 'fus_logbook_movements';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        "id",
        "ip_address",
        "description",
        "tipo",
        "created_at",
        "fus_user_login_id",
    ];

    public function guardarBitacora($data) {
        $bitacora = new LogBookMovements;
        $bitacora->ip_address = $data["ip_address"];
        $bitacora->description = $data["description"];
        $bitacora->tipo = $data["tipo"];
        $bitacora->fus_user_login_id = $data["id_user"];

        $bitacora->save();
    }

    public function movimientos() {
        $query = LogBookMovements::get()->toArray();
 
        return $query;
    }

    public function movimientosByRangeDate($dateInit, $dateFish) {
        $query = LogBookMovements::select(
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