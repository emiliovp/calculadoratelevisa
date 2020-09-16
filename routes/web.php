<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('login');
});
Route::get('/ldap/prueba','LDAPController@index')->name('ldap_prueba');
Route::get('/ldap/buscar','LDAPController@buscar')->name('ldap_busqueda');
Route::get('ldap/getusr', 'LDAPController@getUsr')->name('ldapSearch');
// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/homeajax', 'HomeController@homeajax')->name('homeajax')->middleware('userProfileInactivo');

// Pruebas
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

//fus wintel
// Route::group(['prefix' => 'fus_wintel', 'middleware'=>'userProfileInactivo'], function(){
Route::group(['prefix' => 'fus_wintel'], function(){
    // Route::get('/fus_home', 'FusWintelController@index')->name('homefus')->middleware('reading');
    Route::get('/fus_cuenta/{id}', 'FusWintelController@correo_usuario')->name('cuenta_wintel')->middleware('doublesession');
    Route::get('/ver_fuses', 'FusWintelController@lista')->name('verfus');
    Route::get('autocomplete', ['uses'=>'FusWintelController@autocomplete'])->name('fus.autocomplete');
    Route::get('autocomplete2', ['uses'=>'FusWintelController@autocomplete2'])->name('fus.autocomplete2');
    Route::get('validuser', ['uses'=>'FusWintelController@validaruser'])->name('fus.validuser');
    Route::post('/guardar', 'FusWintelController@insert_fus')->name('fus.insertar_fus');
});

Route::group(['prefix' => 'configuracionesautorizaciones'], function(){
    Route::get('/listapps', 'ConfigautorizacionesController@index')->name('listappsconfig')->middleware('doublesession');
    Route::get('/anyData',  'ConfigautorizacionesController@anyData')->name('listconfig');
    Route::get('/alta', 'ConfigautorizacionesController@alta')->name('createconfigauto')->middleware('doublesession');
    Route::post('/guardar', 'ConfigautorizacionesController@store')->name('saveregister');
    Route::post('/busqueda', 'ConfigautorizacionesController@searchEmployeeLabora')->name('searchautorizador');
    Route::post('/bajaconfapp', 'ConfigautorizacionesController@baja')->name('bajaconf');

    Route::post('/getcatalogos', 'ConfigautorizacionesController@getcatalogoByIdApp')->name('getcatalogos');
    Route::post('/getopcionescatalogos', 'ConfigautorizacionesController@getCatOpcionesByIdCat')->name('getopcionescatalogos');

    Route::post('/totalbyresponsabilidad', 'ConfigautorizacionesController@totalbyresponsabilidad')->name('totalbyresponsabilidad');

    /** CASC autocomplete*/
    Route::get('autocomplete', ['uses'=>'ConfigautorizacionesController@autocomplete'])->name('autocomplete');
});
/* casc */
Route::group(['prefix' => 'otrasconfiguraciones'], function(){
    Route::get('/confg', 'ConfigautorizacionesOtrosController@index')->name('listaconfig')->middleware('doublesession');
    Route::get('/alta', 'ConfigautorizacionesOtrosController@alta')->name('createconfigautooper')->middleware('doublesession');
    Route::post('/guardar', 'ConfigautorizacionesOtrosController@store')->name('saveconfig');
    Route::get('/anyData',  'ConfigautorizacionesOtrosController@anyData')->name('dataconfig');
    Route::post('/bajaconfig',  'ConfigautorizacionesOtrosController@baja')->name('bajaconfig');
    // Route::post('/busqueda', 'ConfigautorizacionesController@searchEmployeeLabora')->name('searchautorizador');
});

Route::group(['prefix' => 'fusaplicaciones'], function(){
    Route::get('/seleccionapps', 'FusSysadminController@index')->name('seleccionfusapps')->middleware('doublesession');
    Route::get('/solicitud', 'FusSysadminController@formRequest')->name('solicitudfusapps');
    Route::post('/solicitud', 'FusSysadminController@formRequest')->name('solicitudfusapps');
    Route::post('/guardar', 'FusSysadminController@store')->name('storefusapp');
    Route::post('/busqueda', 'FusSysadminController@getConfiguracionAutorizaciones')->name('getconfigsauto');
    Route::post('/busquedacatalogo', 'FusSysadminController@getCatalogoYOpciones')->name('getcatalogo');
});

Route::group(['prefix' => 'fus'], function(){
    Route::get('/showfus/{id}', 'FusController@showFus')->name('showfus')->middleware('doublesession');
    Route::get('/showfus/{id}/{tipo}/{jefeOAut}', 'FusController@showFus')->name('showfusmail')->middleware('doublesession');
    Route::get('/showfus/{id}/{tipo}/{jefeOAut}/{idconfig}/{tiporelconfig}/{idapp}', 'FusController@showFus')->name('showfusmailautoapps')->middleware('doublesession');
    Route::post('/rechazofusjefe', 'FusController@rechazofusjefe')->name('rechazofusjefe');
    Route::post('/autorizacionjefe', 'FusController@autorizacionjefe')->name('autorizacionjefe');
    Route::post('/aplicacionapp', 'FusController@aplicacionapp')->name('aplicacionapp');

    Route::get('/fusesporautorizar', 'FusAutorizacionesController@index')->name('listafusesporautorizar')->middleware('doublesession');
    Route::get('/fusesporautorizar/{msjok}', 'FusAutorizacionesController@index')->name('listafusesporautorizarok')->middleware('doublesession');
    Route::get('/fusesporautorizardata', 'FusAutorizacionesController@dataIndex')->name('listafusesporautorizar.data')->middleware('doublesession');
    
    Route::get('/fusarevisar/{id}', 'FusAutorizacionesController@fusarevisar')->name('fusarevisar');//->middleware('doublesession');
    Route::post('/guardarautorizaciones', 'FusAutorizacionesController@guardarautorizaciones')->name('guardarautorizaciones'); //->middleware('doublesession');
});
/* CASC */
Route::group(['prefix' => 'FusGeneral'], function(){
    Route::get('/fuses',    'FusGeneralController@index')->name('fus_lista')->middleware('doublesession');
    Route::get('/fuses/{guardo}/{folio}', 'FusGeneralController@index')->name('fus_lista_despues')->middleware('doublesession');
    Route::get('/anyData',  'FusGeneralController@anyData')->name('fus.data');
    Route::get('/Data',  'FusGeneralController@anyData2')->name('fus.data2');
    Route::get('/listafuses',    'FusGeneralController@listaFuses')->name('lista_dedicada')->middleware('doublesession');
    Route::get('/dominio',    'FusGeneralController@get_option')->name('opDom');
    Route::get('/opresp',    'FusGeneralController@get_autorizadores')->name('opresp');

    Route::get('/appsfuses/{idfus}','FusGeneralController@listaAppsPorFus')->name('fusapps')->middleware('doublesession');
    Route::get('/dataAppsFus/{idfus}','FusGeneralController@dataAppsFus')->name('fus.appsdata');
    // Route::get('/exportfus','FusGeneralController@export')->name('fus.export');
});

Route::group(['prefix' => 'notificaciones'], function(){
    Route::get('/reenvios/{id}', 'NotificacionesController@reenvioDeNotificaciones')->name('reenvios');
    Route::get('/fusautorizado', 'NotificacionesController@fusAutorizado')->name('fusAutorizado');
    Route::post('/fusfinal', 'NotificacionesController@not_fus_final')->name('notificacionFinalWtl1');
});

Route::group(['prefix' => 'descargas'], function(){
    Route::get('/anexos/{file}', 'DescargasControllerController@download')->name('descargaanexo');
    Route::get('/anexos/{idfus}/{idapp}', 'DescargasControllerController@multiDownload')->name('multidescargaanexo');
});

/*CASC modulo del crud de usuarios*/
Route::group(['prefix' => 'usuarios'], function(){
    Route::get('/listausuarios','UserController@index')->name('ListaUsuarios')->middleware('doublesession');
    Route::get('/anyData',  'UserController@anyData')->name('listusers');
    Route::post('/bajausr',  'UserController@bajaUsr')->name('bajausr');
    // Route::post('/newusr',  'UserController@setusuario')->name('newuser');
    Route::get('/alta', 'UserController@setusuario')->name('createnewusr')->middleware('doublesession');
    Route::post('store', 'UserController@store')->name('storeuser');
    Route::get('search', ['uses'=>'UserController@getUsr'])->name('getUsr');
});
/*CASC Modulo de reportes*/
Route::group(['prefix' => 'reportes'], function(){
    Route::get('/reportFus', 'reportesController@index')->name('reporteservicios')->middleware('doublesession');
    Route::get('/listafuses', 'reportesController@anyData')->name('listservicios');

    Route::get('reporteseguimiento', 'reportesController@reporteseguimiento' )->name('reporteseguimiento');
    Route::get('datareporteseguimiento', 'reportesController@datareporteseguimiento' )->name('datareporteseguimiento');
    Route::get('filtersForm', 'reportesController@filtersForm' )->name('filtersForm');
    Route::post('datareporteseguimientopost', 'reportesController@datareporteseguimientopost' )->name('datareporteseguimientopost');
    Route::get('/reporteautorizador', 'reportesController@reporteautorizadores')->name('reporteautorizador')->middleware('doublesession');
    Route::post('datareporteautorizadorpost', 'reportesController@datareporteautorizador' )->name('datareporteautorizadores');
    
});
Route::group(['prefix' => 'admonmesas'], function(){
    Route::get('listacontrol', 'ControlconfigfuseappController@index' )->name('listacontrol');
    Route::get('datalistacontrol', 'ControlconfigfuseappController@datalistacontrol' )->name('datalistacontrol');
    Route::put('eliminarAdmonMesas', 'ControlconfigfuseappController@bajaAdmonMesa' )->name('eliminarAdmonMesas');
    Route::put('updateAdmonMesas', 'ControlconfigfuseappController@actualizaAdmonMesas' )->name('updateAdmonMesas');
    Route::post('storeAdmonMesas', 'ControlconfigfuseappController@altaAdmonMesa' )->name('storeAdmonMesas');
    
});
Route::group(['prefix' => 'catalogos'], function(){
    Route::get('/lista', 'CatalogosController@index')->name('listacatalogos');
    Route::get('/dataIndexCat', 'CatalogosController@dataIndexCat')->name('dataIndexCat');
    Route::post('/store', 'CatalogosController@storecat')->name('storecat');
    Route::put('/eliminacion', 'CatalogosController@deletecat')->name('eliminarCatalogos');

    Route::get('/mesas', 'CatalogosController@listaMesas')->name('listamesas'); // ->middleware('doublesession');
    Route::get('/listamesas', 'CatalogosController@getMesas')->name('getmesas');
    Route::post('/altamesa', 'CatalogosController@storemesa')->name('newmesa');
    Route::post('/editarmesa', 'CatalogosController@editar')->name('editmesa');
    Route::post('/bajamesa', 'CatalogosController@bajamesas')->name('deletemesa');

    Route::get('/listaopciones/{id}/{idapp}', 'OpcionescatalogosController@index')->name('listaopciones');
    Route::get('/listaopciones/{id}/{idapp}/{msjOk}', 'OpcionescatalogosController@index')->name('listaopcionesok');
    Route::get('/dataIndexOptCat', 'OpcionescatalogosController@dataIndexOptCat')->name('dataIndexOptCat');
    Route::post('/verdependencias', 'OpcionescatalogosController@verdependencias')->name('verdependencias');
    Route::put('/eliminacionopciones', 'OpcionescatalogosController@deleteoptcat')->name('eliminarOptCatalogos');
    Route::get('/altaopt/{id}/{idapp}', 'OpcionescatalogosController@altaOpt')->name('altaopciones');
    Route::post('/storeoptcat', 'OpcionescatalogosController@storeoptcat')->name('storeoptcat');
    Route::get('/editaropt/{id}/{idapp}/{idopt}', 'OpcionescatalogosController@editarOpt')->name('editaropciones');
    Route::post('/updateoptcat', 'OpcionescatalogosController@updateoptcat')->name('updateoptcat');
    Route::get('/opbycat',    'OpcionescatalogosController@OptionByCatId')->name('opByCat');
});
// acceso
Route::get('/validateaccesos/{id}/{modulo}', 'accesosController@validateAcceso')->name('validateaccesos');
Route::get('soporte/enviomail', 'SoporteController@index')->name('envioMail');
Route::get('soporte/sendmail', 'SoporteController@sendMail')->name('envioadjunto');
Route::get('soporte/sabanas', 'SoporteController@buscar_sabanas')->name('vistasabanas');
Route::get('soporte/sendSabanas', 'SoporteController@send_sabanas')->name('notificacionFinalWtl');
/*CASC Modulo de perfiles*/
Route::group(['prefix' => 'perfiles'], function(){
    Route::get('/lista', 'PerfilesController@index')->name('listaperfiles');  // ->middleware('doublesession');
    Route::get('/listaperfil/{msjOk}', 'PerfilesController@index')->name('listaperfilesok');
    Route::get('/anydata', 'PerfilesController@anyData')->name('anydataper');  // ->middleware('doublesession');
    Route::get('/alta', 'PerfilesController@alta')->name('mewperfil');
    Route::post('/store', 'PerfilesController@store')->name('storeperfiles');
    // Route::post('/update', 'PerfilesController@updatePerfil')->name('updperfiles');
    Route::post('/bloqueo', 'PerfilesController@bloqueoPerfil')->name('bloqueoperfiles');
    Route::get('/editarperfil/{id}', 'PerfilesController@editarPerfil')->name('editarperfil');
    Route::post('/updateperfil/', 'PerfilesController@updatePerfil')->name('updperfil');
});
Route::group(['prefix' => 'areas'], function(){
    Route::get('/lista', 'AreasController@index')->name('listaareas');
    Route::get('/anydata', 'AreasController@anyData')->name('listaareastabla');
    // Route::get('/listamesas', 'AreasController@getMesas')->name('getmesas');
    Route::post('/altaArea', 'AreasController@storearea')->name('newarea');
    Route::post('/editararea', 'AreasController@editar')->name('editarea');
    Route::post('/bloqueararea', 'AreasController@bloquear')->name('bloqueoarea');
});