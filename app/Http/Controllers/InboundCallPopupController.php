<?php
namespace App\Http\Controllers;
use Session;
use App\Helper\Helper;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;

class InboundCallPopupController extends Controller
{
  public function index(Request $request)
  {
  $body = array(
    'id' => Session::get('id'),
    'token' => Session::get('tokenId'),
    'extension' => Session::get('extension'),
    'alt_extension' => Session::get('private_identity')
  );

  $url = env('API_URL') . 'inbound-call-popup';
  $response = Helper::PostApi($url, $body);
  return response()->json($response);

  }
}

