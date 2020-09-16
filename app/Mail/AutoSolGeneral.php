<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AutoSolGeneral extends Mailable
{
    use Queueable, SerializesModels;

    protected $tipo;
    protected $id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fus, $id)
    {
        $this->fus = $fus;
        $this->id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('sysadmin@televisa.com.mx')
                ->view('mails.notsol', ['id' => $this->id, 'fus'=>  $this->fus])
                ->subject ('CIFRADOTVSA - Notificación de Alta de FUS-e de '.$this->fus.' Folio #'.$this->id);
    }
}
