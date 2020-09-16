<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FUSUsersSessions extends Authenticatable
{
    protected $table = "fus_users_sessions";

    protected $fillable = [
        'id', 'name', 'mail', 'noEmployee', 'useradmin', 'created_at', 'update_at'
    ];

    protected $hidden = [
        'remember_token',
    ];
    protected $primaryKey = 'id';
    public $timestamps = false;

    // Validar no. empleado
    public function validateNumberEmployee($email) {
        return FUSUsersSessions::where('email', $email)->first();
    }

    // Validar sesión más actual
    public function validateDoubleSession() {
        $query = FUSUsersSessions::where([
            'email' => Auth::user()->email,
            'created_at' => DB::raw('(SELECT MAX(created_at) FROM fus_users_sessions WHERE email = "'.Auth::user()->email.'")')
        ])
        ->first();
        
        return $query->created_at;
    }
}
