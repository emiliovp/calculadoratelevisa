<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\RelAnexosFus;

class DescargasControllerController extends Controller
{
    public $ip_address_client;
    
    public function __construct() {
        $this->ip_address_client = getIpAddress();
        $this->middleware('auth');
    }

    protected function downloadFile($src){
        if(is_file($src)){
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $content_type = finfo_file($finfo, $src);
            finfo_close($finfo);
            $file_name = basename($src).PHP_EOL;
            $size = filesize($src);
            header("Content-Type: $content_type");
            header("Content-Disposition: attachment; filename=$file_name");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: $size");
            readfile($src);
            return true;
        } else{
            abort(404);
            // return redirect()->back();
        }
    }

    public function download(Request $request){
        // if(!$this->downloadFile(base_path()."\\storage\\app\\anexos\\".$request->file)){
        //     return redirect()->back();
        // }
        $mimetype = \GuzzleHttp\Psr7\mimetype_from_filename($request->file);
        $path = base_path()."\\storage\\app\\anexos\\".$request->file;
        $titleFile = $request->file;
        
        // Se aplica el content type apartir del mimetype
        $headers = array('Content-Type' => $mimetype);
        
        // re retorna el archivo
        $response = Response::download($path,$titleFile,$headers);
        ob_end_clean();
        return $response;
    }
}
