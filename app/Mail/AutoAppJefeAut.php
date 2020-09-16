<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AutoAppJefeAut extends Mailable
{
    use Queueable, SerializesModels;

    protected $tipo;
    protected $id;
    protected $jefeOAut;
    protected $fus;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tipo, $id, $jefeOAut, $fus)
    {
        $this->tipo = $tipo;
        $this->id = $id;
        $this->jefeOAut = $jefeOAut;
        $this->fus = $fus;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('sysadmin@televisa.com.mx')
                ->view('mails.autorizacionjefe', ['tipo' => $this->tipo, 'id' => $this->id, 'jefeOAut' => $this->jefeOAut, 'fus'=>  $this->fus, 'tipo_fus' => $this->fus])
                ->subject ('CIFRADOTVSA - Solicitud de autorización para FUS-e de '.$this->fus.' Folio #'.$this->id);
    }
}
