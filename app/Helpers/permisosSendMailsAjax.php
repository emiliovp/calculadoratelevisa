<?php
    use Illuminate\Support\Facades\Auth;
    use App\User;
    use App\permiso;

    function permisosSendMailsAjax() {
        $validacion = User::find(Auth::user()->id)->relProfile()->select('id')->first();
        if($permiso = permiso::where('profiles_users_id', '=', $validacion->id)->first()) {
            if(count($permiso->toArray()) > 0) {
                if($permiso->send_email == 1) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }