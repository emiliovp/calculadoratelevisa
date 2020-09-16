<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RechazoFusJefeAut extends Mailable
{
    use Queueable, SerializesModels;

    protected $idFus;
    protected $observacion;
    protected $jefeOAut;
    protected $rechazoApp;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($idFus, $observacion, $jefeOAut, $rechazoApp = null, $tipo_fus)
    {
        $this->idFus = $idFus;
        $this->observacion = $observacion;
        $this->jefeOAut = $jefeOAut;
        $this->rechazoApp = $rechazoApp;
        $this->tipo_fus = $tipo_fus;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('sysadmin@televisa.com.mx')
                ->view('mails.rechazofusjefe', ['id' => $this->idFus, 'observacion' => $this->observacion, 'jefeOAut' => $this->jefeOAut, 'rechazoApp', $this->rechazoApp])
                ->subject ('CIFRADOTVSA - FUS-e de '.$this->tipo_fus.' Folio #'.$this->idFus.' - Rechazo');
    }
}
