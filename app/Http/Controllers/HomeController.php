<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ControlConfigFuseApp;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $controlConfigFuseApp = new ControlConfigFuseApp;
        if(Auth::user()->useradmin == 1) {
            return view('home');
        } else {
            if($controlConfigFuseApp->existenciaUsuarioByNoEmpName(Auth::user()->noEmployee, Auth::user()->name) > 0) {
                return view('home')->with("admonmesas", 1);
            } 
            return redirect()->route('fus_lista');
        }
    }
}