<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CronCallReportEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $result_arr =array();
    //public $name;
    //public $logo;



    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($result_arr)
    {
        $this->result_arr = $result_arr;
       /* $this->name = $name;
        $this->logo = $logo;*/

        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('cafmotel@cafmotel.com')->subject('Daily Report')->view('emails.croncallreportemail');
    }
}