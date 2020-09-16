<?php
namespace App\Http\controllers;

use App\FusConfiguracionesAutorizaciones;
use App\FUSSysadminWtl;
use App\reporteseguimiento;
use App\LogBookMovements;
use App\Applications;
use App\Http\controllers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Yajra\Datatables\Datatables;

class reportesController extends Controller
{
    public $ip_address_client;

    public function __construct(){
        $this->ip_address_client = getIpAddress();
        $this->middleware('auth');
    }
    public function index(){
        $noEmployee = (isset(Auth::user()->noEmployee) && Auth::user()->noEmployee != 0) ? Auth::user()->noEmployee : 1;
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }

        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Empleado con #'.$noEmployee.' visualizó lista de Catálogos',
            'tipo' => 'vista',
            'id_user' => $idEmployee
        );
        
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);

        return view('reportes.reportesServicios');
    }
    public function anyData(){
        $data = new FUSSysadminWtl;
        $val = $data->reporteFus();

        return Datatables::of($val)->make(true);
    }

    public function reporteseguimiento() {
        $noEmployee = (isset(Auth::user()->noEmployee) && Auth::user()->noEmployee != 0) ? Auth::user()->noEmployee : 1;
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }

        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Empleado con #'.$noEmployee.' visualizó lista de Catálogos',
            'tipo' => 'vista',
            'id_user' => $idEmployee
        );
        
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);

        $dataFormInf = new reporteseguimiento;
        $dataForm = $dataFormInf->infoParaForm();
        
        return view('reportes.reporteseguimiento')->with(["dataForm" => $dataForm]);
    }

    public function datareporteseguimiento() {
        $fus = new reporteseguimiento;
        $result = $fus->reporteseguimiento();
        // dd($result);
        return Datatables::of($result)->make(true);
    }

    public function filtersForm() {
        $dataFormInf = new reporteseguimiento;
        $dataForm = $dataFormInf->infoParaForm();
        return $dataForm;
    }

    public function datareporteseguimientopost(Request $request) {
        $data = array();

        $desde = null;
        $hasta = null;
        
        if(!empty($request->post("desde"))) {
            $desde = $request->post("desde")." 00:00:00";
        }
        if(!empty($request->post("hasta"))) {
            $hasta = $request->post("hasta")." 23:59:59";
        }
        
        $data["desde"] = $desde;
        $data["hasta"] = $hasta;
        $data["folio"] = $request->post("folio");
        $data["tipofus"] = $request->post("tipofus");
        $data["tipomovimiento"] = $request->post("tipomovimiento");
        $data["aplicacion"] = $request->post("aplicacion");
        $data["total"] = $request->post("total");
        $data["sox"] = $request->post("sox");
        
        $fus = new reporteseguimiento;
        $result = $fus->reporteseguimiento($data);
        // dd($result);
        if($data["total"] == 0) {
            return Datatables::of($result)->make(true);
        } else {
            return $result;
        }
    }
    public function reporteautorizadores(){
        $conApps = new applications;
        $apps = $conApps->getApplications();
        // dd($apps);
        $noEmployee = (isset(Auth::user()->noEmployee) && Auth::user()->noEmployee != 0) ? Auth::user()->noEmployee : 1;
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }

        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Empleado con #'.$noEmployee.' visualizó lista de Catálogos',
            'tipo' => 'vista',
            'id_user' => $idEmployee
        );
        // dd($apps);
        return view('reportes.reporteAutorizadores')->with(["listapps" => $apps]);
    }
    public function datareporteautorizador(Request $request)
    {
        $desde = ($request->post('desde') != null) ? $request->post('desde') : null;
        $hasta = ($request->post('hasta') != null) ? $request->post('hasta') : null;
        $tipoaut= ($request->post('tipoaut') != null) ? $request->post('tipoaut') : null;
        $app = ($request->post('app') != null) ? $request->post('app') : null;
        $responsabilidad = ($request->post('responsabilidad') != null) ? $request->post('responsabilidad') : null;
        $user = ($request->post('user') != null) ? $request->post('user') : null;
        $estatus = ($request->post('estatus') != null) ? $request->post('estatus') : null;
        $apps = new FusConfiguracionesAutorizaciones;
        $data = $apps->get_autorizador($desde, $hasta, $tipoaut, $app, $responsabilidad, $user,$estatus);
        // dd($data);
        return Datatables::of($data)->make(true);
    }
}
?>