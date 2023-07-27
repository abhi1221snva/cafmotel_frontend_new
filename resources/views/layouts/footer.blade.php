<?php

use \App\Http\Controllers\InheritApiController;

$userdetails = InheritApiController::headerUserDetails();
$questionsInfo = App\Http\Controllers\OpeningQuestionsController::getQuestionsInfo();

$callbackStatus = \App\Http\Controllers\APiCallBackController::getReminderStatus();
if ($callbackStatus == null) {
    $callbackStatus = new stdClass();
    $callbackStatus->callback_reminder = null;
}
?>
<footer class="main-footer">

	<strong>© 2019 - {{date('Y')}} <a
            href="#">@if(!empty($userdetails->data->company_name)){{$userdetails->data->company_name}} @else
                {{env('DEFAULT_COMPANY_NAME')}} @endif</a>.</strong> All Rights Reserved.
  </footer>