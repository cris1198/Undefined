<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contigua extends Mailable
{
    use Queueable, SerializesModels;
    public $datos;
    public $materia;
    public $grupo;
    public $aula2;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($datos1,$materia1,$grupo1,$aula)
    {
        $this->datos = $datos1;
        $this->materia = $materia1;
        $this->grupo = $grupo1;
        $this->aula2 = $aula;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Notificacion de reserva de aula')->view('emails.Contigua');
    }
}
