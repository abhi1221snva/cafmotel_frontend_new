<?php

namespace App\Helper;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\TransferStats;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;

class Helper
{
    public static function GetApi($url, array $headers = [], bool $associativeResponse = false)
    {
        return self::RequestApi($url, "GET", $body = [], $contentType = null, $headers, $associativeResponse);
    }

    public static function PostApi($url, $body = [], $contentType = "form-data", array $headers = [])
    {
        return self::RequestApi($url, "POST", $body, $contentType, $headers);
    }

    public static function RequestApi($url, $method = "GET", $body = [], $contentType = "form-data", array $headers = [], bool $associativeResponse = false)
    {
        $client = new \GuzzleHttp\Client(['headers' => $headers]);
        $options = [
            'headers' => array_merge([
                "x-client" => env("X_CLIENT"),
                "Accept" => "application/json",
                "Authorization" => "Bearer " . Session::get("tokenId"),
                "parent-id" => Session::get("parentId")
            ], $headers)
        ];

        if ($contentType === "form-data") {
            $options["form_params"] = $body;
        } elseif ($contentType === "json") {
            $options["headers"] = array_merge($options["headers"], ['Content-Type' => 'application/json']);
            $options["json"] = $body;
        }

        $time = null;
        $calcTime = null;
        $profiler = new ExecutionProfiler();
        $options['on_stats'] = function (TransferStats $stats) use (&$time) {
            // Estimated time the request was being transferred
            $time = $stats->getTransferTime();
        };

        $profiler->addInterval("sending");

        try {

            $response = $client->request($method, $url, $options);
            $profile = $profiler->calculate();
            $result = $response->getBody()->getContents();

            $json_result = json_decode($result, $associativeResponse);
            unset($options["headers"]);
            unset($options["on_stats"]);
            Log::channel("backend-request-" . app()->environment())->info("backend.request", [
                "method" => $method,
                "url" => $url,
                "options" => $options,
                "response" => $json_result,
                "time" => $time,
                "calcTime" => $profile
            ]);

            return $json_result;
        } catch (RequestException $re) {
            $httpRequest = $re->getRequest();
            $httpResponse = $re->getResponse();
            $message = $re->getMessage();
            $responseBody = null;
            $errorsReturned = false;
            if ($httpResponse) {
                $responseBody = json_decode($httpResponse->getBody()->getContents(), true);
                if (isset($responseBody['message']) || isset($responseBody['errors'])) {
                    $errorsReturned = true;
                }
            }
            $requestBody = $httpRequest->getBody()->getContents();
            $log = [
                "parent-id" => Session::get("parentId"),
                "user-id" => Session::get("id"),
                "endpoint" => $httpRequest->getMethod() . " " . $httpRequest->getUri(),
                "options" => $options,
                "requestBody" => $requestBody,
                "requestHeaders" => $options["headers"],
                "responseStatusCode" => $re->getCode(),
                "responseBody" => $responseBody,
                "errorsReturned" => $errorsReturned
            ];
            Log::error("backend.response.error", $log);
            if ($errorsReturned) {
                if ($associativeResponse)
                    return $responseBody;
                else
                    return (object)$responseBody;
            } else {
                throw $re;
            }
        }
    }
    
    public static function buildContext(\Throwable $throwable, array $context = []): array
    {
        $context["message"] = $throwable->getMessage();
        $context["file"] = $throwable->getFile();
        $context["line"] = $throwable->getLine();
        $context["code"] = $throwable->getCode();
        self::buildPrevious($throwable, $context);
        return $context;
    }

    public static function buildPrevious(\Throwable $throwable, array &$context, $index = 0)
    {
        $previous = $throwable->getPrevious();
        if ($previous) {
            $context["previous.$index"] = [
                "message" => $throwable->getMessage(),
                "file" => $throwable->getFile(),
                "line" => $throwable->getLine(),
                "code" => $throwable->getCode()
            ];
            self::buildPrevious($previous, $context, $index++);
        }
    }


    /*public static function shout(string $string)
    {
        return back()->withSuccess($string);
    }*/
}
