<?php

namespace App\Providers;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        if (\Schema::hasTable('email')) {
            $mail = DB::table('smtp_setting')->first();
            if ($mail) //checking if table is not empty
            {
                $config = array(
                    'driver'     => $mail->mail_driver,
                    'host'       => $mail->mail_host,
                    'port'       => $mail->mail_port,
                    'from'       => array('address' => 'abhi2112mca@gmail.com' ,'name' => 'abhishek'), // $mail->from_address //$mail->from_name
                    'encryption' => $mail->mail_encryption,
                    'username'   => $mail->mail_username,
                    'password'   => $mail->mail_password,
                    'sendmail'   => '/usr/sbin/sendmail -bs',
                    'pretend'    => false,
                );

           // echo "<pre>";print_r($config);die;

                Config::set('mail', $config);
            }
        }
    }
}
