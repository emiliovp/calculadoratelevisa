<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TCSUsersSessions extends Model
{
    protected $table = "tcs_users_sessions";

    protected $fillable = [
        'id', 'name', 'mail', 'noEmployee', 'create_at', 'update_at'
    ];

    protected $hidden = [
        'remember_token',
    ];
    protected $primaryKey = 'id';
    public $timestamps = false;

    // Validar no. empleado
    public function validateNumberEmployee($email) {
        return TCSUsersSessions::where('email', $email)->first();
    }
}
