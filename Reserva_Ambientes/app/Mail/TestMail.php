<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class TestMail extends Mailable
{
    use Queueable, SerializesModels;
    public $details22;
    public $data22;
    public $data33;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$materia, $grupo)
    {
       $this->details22 = $data;
       $this->data22 = $materia;
       $this->data33 = $grupo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Notificacion de reserva de aula')->view('emails.TestEmail');
    }
}
