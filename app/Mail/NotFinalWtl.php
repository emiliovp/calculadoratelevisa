<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotFinalWtl extends Mailable
{
    use Queueable, SerializesModels;

    protected $id;
    protected $tipo;
    protected $doc;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id, $tipo, $doc)
    {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->doc = $doc;
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
            ->view('mails.notfinalfus', ['id' => $this->id, 'fus'=>  $this->tipo])
            ->subject ('CIFRADOTVSA - '.$this->tipo.' con Folio #'.$this->id.' autorizado ')
            ->attach(base_path('storage/app/'.$this->doc));
    }
}
