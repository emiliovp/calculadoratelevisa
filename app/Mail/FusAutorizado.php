<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FusAutorizado extends Mailable
{
    use Queueable, SerializesModels;

    protected $id;
    protected $tipo;
    // protected $obj;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id, $tipo)
    {
        $this->id = $id;
        $this->tipo = $tipo;
        // $this->obj = $obj;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->from('fuselectronico@televisa.com.mx')
        return $this->from('sysadmin@televisa.com.mx')
            ->view('mails.fusautorizado', ['id' => $this->id, 'fus'=>  $this->tipo])
            ->subject ('CIFRADOTVSA - Notificación de Autorizaciones completas del FUS de '.$this->tipo.' con Folio #'.$this->id);
    }
}
