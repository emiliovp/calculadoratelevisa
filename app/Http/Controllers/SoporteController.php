<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\envioanexos;
use App\Mail\NotFinalWtl;
use App\FUSSysadminWtl;
use Adldap\AdldapInterface;
use Adldap\Objects\Paginator;
use App\RelAnexosFus;

use Adldap\Laravel\Facades\Adldap;
use App\Http\Controllers;
use App\Http\Controllers\FusGeneralController;

class SoporteController extends Controller
{
    public $dataLDAP;
    public function __construct() {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->dataLDAP = config('ldap.connections');
    }
    public function index() {
        return view('soporte.envioadjunto'); // ->with('idfus', $request->idfus);
    }
    public function sendMail(Request $request){
        $folio =$request->get('term', '');
        $dest =$request->get('dest', ''); 
        $correos = explode(',', $dest);
        $conn = new RelAnexosFus;
        $docnombre = $conn->getAllsAnexo($folio);
        $param = array();
        if (count($docnombre) > 0) {
            foreach ($correos as $value) {
                // dd($value);
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    echo json_encode($param['error'] = 1);
                    exit();
                }
            }
            $docs = array();
            foreach ($docnombre as $key) {
                $docs[] = base_path('storage/app/anexos/'.$key['path']);
            }
            $mail = Mail::to($correos);
            $mail->send(new envioanexos($folio,$docs));
            echo json_encode($param['ok']=2);
        }else{
            echo json_encode($param['error'] = 0);
        }
    }
    public function buscar_sabanas(){
        return view('soporte.enviosabanas');
    }
    public function send_sabanas(Request $request){
        $folio =$request->get('term', '');
        $dest =$request->get('dest', ''); 
        $correos = explode(',', $dest);
        $a = new FusGeneralController;
        $fus = new FUSSysadminWtl;
        $data = $fus->getFusByIdWtlReenvio($folio);
        if ($data != null) {
            foreach ($correos as $value) {
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    echo json_encode($param['error'] = 1);
                    exit();
                }
            }
            $doc = $a->export($folio);
            // $correo= array('Opservicedesk@televisa.com.mx','Cat@televisa.com.mx');
            $mail = Mail::to($correos);
            $mail->send(new NotFinalWtl($folio,$doc['nombre'], $doc['archivo']));
            echo json_encode($param['ok']=2);
        }else{
            echo json_encode($param['error'] = 0);
        }
    }
}