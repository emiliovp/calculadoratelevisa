<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class envioanexos extends Mailable
{
    use Queueable, SerializesModels;

    protected $folio;
    protected $path;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($folio, $path)
    {
        $this->folio = $folio;
        $this->path = $path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mensaje = $this->from('sysadmin@televisa.com.mx')
            ->view('mails.envioanexos', ['folio' => $this->folio])
            ->subject ('CIFRADOTVSA - Envio de documentos adjuntos del folio #'.$this->folio);
        foreach ($this->path as $key) {
            $mensaje->attach($key);
        }
        return $mensaje;
            
    }
}
