<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FeedbackMail extends Mailable
{
    use Queueable, SerializesModels;
    public $feedback;
    public $name;
    public $logo;



    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($feedback,$name,$logo)
    {
        $this->feedback = $feedback;
        $this->name = $name;
        $this->logo = $logo;

        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('cafmotel@cafmotel.com')->subject('Verify Links')->view('emails.feedback');
    }
}