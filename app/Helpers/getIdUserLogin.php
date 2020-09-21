<?php
    use App\CalUserLogin;
    
    function getIdUserLogin($noEmployee){
        $user = new CalUserLogin;
        return $user->getIdUser($noEmployee);
    }