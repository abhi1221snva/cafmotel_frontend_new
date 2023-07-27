<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\MessageBag;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        if (empty(\Session::get('tokenId'))) {
            return redirect('/');
        }
    }

    protected function buildErrors($response)
    {
        $errors = new MessageBag();
        $errors->add("error", $response->message);
        foreach ( $response->errors as $key => $messages ) {
            if (is_array($messages)) {
                foreach ($messages as $index => $message)
                    $errors->add("$key.$index", $message);
            } else {
                $errors->add($key, $messages);
            }
        }
        return $errors;
    }
    
    /**
    * Get dates array
    * @return int
    */
    public function getDates() {
        for($i=1; $i<32; $i++) {
            $dates[] = $i;
        }
        return $dates;
    }
    
    /**
    * Get months array
    * @return type
    */
    public function getMonths() {
        return ['1' => 'January','2' => 'February', '3' => 'March', '4' => 'April',
            '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September',
            '10' => 'October', '11' => 'November', '12' => 'December'];
    }
}
