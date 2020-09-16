<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AutorizacionApps extends Mailable
{
    use Queueable, SerializesModels;

    protected $tipo;
    protected $id;
    protected $jefeOAut;
    protected $idRelConf;
    protected $act_Apps_Otros;
    protected $idapp;
    protected $tipo_fus;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($idapp, $tipo, $id, $jefeOAut, $idRelConf, $act_Apps_Otros, $tipo_fus)
    {
        $this->tipo = $tipo;
        $this->id = $id;
        $this->jefeOAut = $jefeOAut;
        $this->idRelConf = $idRelConf;
        $this->act_Apps_Otros = $act_Apps_Otros;
        $this->tipo_fus = $tipo_fus;
        $this->idapp = $idapp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('sysadmin@televisa.com.mx')
                ->view('mails.autorizacionjefe', ['tipo' => $this->tipo, 'id' => $this->id, 'jefeOAut' => $this->jefeOAut, 'idRelConf' => $this->idRelConf, 'act_Apps_Otros' => $this->act_Apps_Otros, 'idapp' => $this->idapp, 'tipo_fus'=> $this->tipo_fus])
                ->subject ('CIFRADOTVSA - Solicitud de autorización para FUS-e de '.$this->tipo_fus.' Folio #'.$this->id);
    }
}
