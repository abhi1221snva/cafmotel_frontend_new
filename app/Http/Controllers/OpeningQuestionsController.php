<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Session;

class OpeningQuestionsController extends Controller
{
    public static function getQuestionsInfo()
    {
        $openingQuestionsData = [];

        list($boolResponseStatus, $openingQuestionsStatus) = self::getQuestionDndStatus();

        if (Session::get('showQuestion') == TRUE && $openingQuestionsStatus->status == 0 && (Session::get("level") >= 7)) {
            $errors = new MessageBag();
            $url = env('API_URL') . 'opening-questions/next';

            try {
                $response = Helper::GetApi($url);
                if ($response->success) {
                    $openingQuestionsData = (array)$response->data;
                } else {
                    foreach ($response->errors as $key => $messages) {
                        if (is_array($messages)) {
                            foreach ($messages as $index => $message)
                                $errors->add("$key.$index", $message);
                        } else {
                            $errors->add($key, $messages);
                        }
                    }
                    return view("dashboard.dashboard", compact("errors", $errors));
                }
            } catch (\Throwable $ex) {
                $errors->add("error", $ex->getMessage());
                return view("dashboard.dashboard", compact("errors", $errors));
            }
        }

        return $openingQuestionsData;
    }

    public function doNotShow(Request $request)
    {
        Session::put('showQuestion', FALSE);
        return 'Settings Saved';
    }

    public function flashPanel(Request $request)
    {
        $openingQuestionsData = $openingQuestionsStatus = [];
        $errors = new MessageBag();
        $url = env('API_URL') . 'opening-questions';

        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $openingQuestionsData = (array)$response->data;
            } else {
                foreach ($response->errors as $key => $messages) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return view("questions.flash-panel", compact("errors", $errors));
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return view("questions.flash-panel", compact("errors", $errors));
        }

        list($boolResponseStatus, $openingQuestionsStatus) = $this->getQuestionDndStatus();

        return view("questions.flash-panel", compact('openingQuestionsData', 'openingQuestionsStatus'));
    }

    public static function getQuestionDndStatus()
    {
        $openingQuestionsStatus = NULL;
        $errors = new MessageBag();

        $strStatusUrl = env('API_URL') . 'opening-questions/status';
        try {
            $response = Helper::GetApi($strStatusUrl);
            if ($response->success) {
                $openingQuestionsStatus = $response->data;
            } else {
                foreach ($response->errors as $key => $messages) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return [false, $errors];
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return [false, $errors];
        }
        return [true, $openingQuestionsStatus];
    }

    public function hideQuestionsPermanently(Request $request)
    {
        $strResponseMessage = '';
        $errors = new MessageBag();
        $url = env('API_URL') . 'opening-questions/hide/permanently';

        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $strResponseMessage = (array)$response->message;
            } else {
                foreach ($response->errors as $key => $messages) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return $errors;
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return $errors;
        }
        return $strResponseMessage;
    }

    public function showQuestionsPermanently(Request $request)
    {
        $strResponseMessage = '';
        $errors = new MessageBag();
        $url = env('API_URL') . 'opening-questions/show/permanently';

        try {
            $response = Helper::GetApi($url);
            if ($response->success) {
                $strResponseMessage = (array)$response->message;
            } else {
                foreach ($response->errors as $key => $messages) {
                    if (is_array($messages)) {
                        foreach ($messages as $index => $message)
                            $errors->add("$key.$index", $message);
                    } else {
                        $errors->add($key, $messages);
                    }
                }
                return $errors;
            }
        } catch (\Throwable $ex) {
            $errors->add("error", $ex->getMessage());
            return $errors;
        }
        Session::put('showQuestion', TRUE);

        return $strResponseMessage;
    }
}
