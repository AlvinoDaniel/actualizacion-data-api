<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

class RecoveryPassword extends Mailable
{
    use Queueable, SerializesModels;
    public $token;
    public $persona;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, $persona)
    {
        $this->token = $token;
        $this->persona = $persona;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('dircomputacion@udo.edu.ve', 'Dirección de Computación')
            ->with(['message' => $this])
            ->subject('Recuperar Contraseña')
            ->view('emails.reset_password');
    }
}
