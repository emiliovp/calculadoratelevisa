<?php
    use App\FusUserLogin;
    
    function getIdUserLogin($noEmployee){
        $user = new FusUserLogin;
        return $user->getIdUser($noEmployee);
    }