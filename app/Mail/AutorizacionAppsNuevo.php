<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AutorizacionAppsNuevo extends Mailable
{
    use Queueable, SerializesModels;

    protected $idFus;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($idFus)
    {
        $this->idFus = $idFus;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('sysadmin@televisa.com.mx')
                ->view('mails.autorizacionapps', ['idFus' => $this->idFus])
                ->subject ('CIFRADOTVSA - Solicitud de autorización para FUS-e de Aplicaciones Folio #'.$this->idFus);
    }
}
