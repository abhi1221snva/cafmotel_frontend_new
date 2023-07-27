<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Pusher\Pusher;

class MessagesController extends Controller
{
    protected $perPage = 30;
    protected $messengerFallbackColor = '#2180f3';
    public $pusher;

    //TODO: Beautify this file
    public function __construct()
    {
        if(!$this->pusher){
            $this->pusher = new Pusher(
                env("PUSHER_APP_KEY"),
                env("PUSHER_APP_SECRET"),
                env("PUSHER_APP_ID"),
                ['cluster' => env("PUSHER_APP_CLUSTER"), 'encrypted' => false]
            );
        }
    }
    /**
     * Authinticate the connection for pusher
     *
     * @param Request $request
     * @return void
     */
    public function pusherAuth(Request $request)
    {
        // Auth data
        $authData = json_encode([
            'user_id' => Session::get('id'),
            'user_info' => [
                'name' => Session::get('display_name')
            ]
        ]);

        return $this->pusherAuthorize(
            $request['channel_name'],
            $request['socket_id'],
            $authData
        );
    }

    /**
     * Returning the view of the app with the required data.
     *
     * @param int $id
     * @return void
     */
    public function index( $id = null)
    {
        $routeName= FacadesRequest::route()->getName();
        $type = in_array($routeName, ['user','group'])
            ? $routeName
            : 'user';

        return view('pages.app', [
            'id' => $id ?? 0,
            'type' => $type ?? 'user',
            'messengerColor' => Auth::user()->messenger_color ?? $this->messengerFallbackColor,
            'dark_mode' => 'light',
        ]);
    }


    /**
     * Fetch data by id for (user/group)
     *
     * @param Request $request
     * @return collection
     */
    public function idFetchData(Request $request)
    {

        $url = env('API_URL') . "idInfo";
        $body = [
            'to_id' => $request['id'],
            'type' => $request['type'],
            '_token' => $request->get("_token")
        ];
        if ($request['type'] == 'user') $body['type'] = 'user';

        try {
            $response = Helper::PostApi($url, $body);
            if ($response->success) {
                return Response::json([
                    'favorite' => $response->data->favorite,
                    'fetch' => $response->data->fetch ?? [],
                    'user_avatar' => $response->data->user_avatar ?? null
                ]);
            } else {
                return response()->json($response->message);
            }
        } catch (\Throwable $ex) {
            return ['status' => $ex->getMessage()];
        }
    }

    /**
     * This method to make a links for the attachments
     * to be downloadable.
     *
     * @param string $fileName
     * @return void
     */
    public function download($fileName)
    {
        $path = env('FILE_UPLOAD_PATH').env('MESSENGER_FOLDER') . '/' . $fileName;
        if (file_exists($path)) {
            return Response::download($path, $fileName);
        } else {
            return abort(404, "Sorry, File does not exist in our server or may have been deleted!");
        }
    }

    /**
     * Send a message to database
     *
     * @param Request $request
     * @return JSON response
     */
    public function send(Request $request)
    {
        // default variables
        $error = (object)[
            'status' => 0,
            'message' => null
        ];
        $attachment = null;
        $attachment_title = null;
        $temporaryMsgId = '';

        // if there is attachment [file]
        if ($request->hasFile('file')) {
            // allowed extensions
            $allowed_images = $this->getAllowedImages();
            $allowed_files  = $this->getAllowedFiles();
            $allowed        = array_merge($allowed_images, $allowed_files);

            $file = $request->file('file');
            // if size less than 150MB
            if ($file->getSize() < 150000000) {
                if (in_array($file->getClientOriginalExtension(), $allowed)) {
                    // get attachment name
                    $attachment_title = $file->getClientOriginalName();
                    // upload attachment and store the new name
                    $attachment = Session::get('parentId') . Str::uuid() . "." . $file->getClientOriginalExtension();
                    // /var/www/html/branch/backend/upload/messenger
                    $file->move(env('FILE_UPLOAD_PATH').env('MESSENGER_FOLDER'), $attachment);
                } else {
                    $error->status = 1;
                    $error->message = "File extension not allowed!";
                }
            } else {
                $error->status = 1;
                $error->message = "File extension not allowed!";
            }
        }

        if (!$error->status) {
            $url = env('API_URL') . "sendMessage";
            $body = [
                'to_id' =>$request['id'],
                'message' =>$request['message'],
                'type' =>$request['type'],
                'meeting_key' =>$request['meeting_key'],
                'attachment' => ($attachment) ? json_encode((object)[
                    'new_name' => $attachment,
                    'old_name' => htmlentities(trim($attachment_title), ENT_QUOTES, 'UTF-8'),
                ]) : null,
            ];

            try {
                $response = Helper::PostApi($url, $body);

                if ($response->success) {
                    $messageData = $response->data->message;
                    $temporaryMsgId = $response->data->tempID;

                    // send to user using pusher
                    $pusherResponse =  $this->push('private-chatify', 'messaging', [
                        'from_id' => Session::get('id'),
                        'to_id' => $request['id'],
                        'sender_name' => $response->data->sender_name,
                        'message' => $this->messageCard( (array) $messageData, 'default')
                    ]);

                    return Response::json([
                        'pusherResponse' => $pusherResponse,
                        'status' => '200',
                        'error' => $error,
                        'message' => $this->messageCard( (array) @$messageData),
                        'tempID' => $request['temporaryMsgId'],
                    ]);
                } else {
                    $error->status = 1;
                    $error->message = $response->message;
                    return Response::json([
                        'status' => '500',
                        'error' => $error,
                        'message' => '',
                        'tempID' => $temporaryMsgId,
                    ]);
                }
            } catch (\Throwable $ex) {
                $error->status = 1;
                $error->message = $ex->getMessage();
                return Response::json([
                    'status' => '500',
                    'error' => $error,
                    'message' => '',
                    'tempID' => $temporaryMsgId,
                ]);
            }
        }
    }

    /**
     * fetch [user/group] messages from database
     *
     * @param Request $request
     * @return JSON response
     */
    public function fetch(Request $request)
    {
        $url = env('API_URL') . "fetchMessages";
        $body = [
            'to_id' => $request['id']
        ];

        try {
            $ApiResponse = Helper::PostApi($url, $body);

            if ($ApiResponse->success) {
                $totalMessages = $ApiResponse->data->total;
                $lastPage = $ApiResponse->data->last_page;
                $response = [
                    'total' => $totalMessages,
                    'last_page' => $lastPage,
                    'last_message_id' => $ApiResponse->data->last_message_id,
                    'messages' => ''
                ];

                // if there is no messages yet.
                if ($totalMessages < 1) {
                    $response['messages'] ='<p class="message-hint center-el"><span>Say \'hi\' and start messaging</span></p>';
                    return Response::json($response);
                } else {
                    $allMessages = null;
                    foreach ($ApiResponse->data->messages as $message) {
                        $intMessageId = $message->id;
                        $allMessages .= $this->messageCard((array) $ApiResponse->data->messageData->$intMessageId);
                    }

                    $response['messages'] = $allMessages;
                    return Response::json($response);
                }
            } else {
                return response()->json($ApiResponse->message);
            }
        } catch (\Throwable $ex) {
            return Response::json($ex->getMessage());
        }
    }

    /**
     * Make messages as seen
     *
     * @param Request $request
     * @return void
     */
    public function seen(Request $request)
    {
        // make as seen
        $url = env('API_URL') . "makeSeen";
        $body = [
            'from_id' => $request['id']
        ];

        try {
            $response = Helper::PostApi($url, $body);
            if ($response->success) {
                return Response::json([
                    'status' => $response->data->status,
                ], 200);
            } else {
                return response()->json($response->message);
            }
        } catch (\Throwable $ex) {
            return ['status' => $ex->getMessage()];
        }
    }

    /**
     * Get contacts list
     *
     * @param Request $request
     * @return JSON response
     */
    public function getContacts(Request $request)
    {
        $url = env('API_URL') . "/getContacts";
        try {
            $response = Helper::GetApi($url);

            if ($response->success) {
                $usersList = $response->data->contacts;
                if (count($usersList) > 0) {
                    $contacts = '';
                    $arrContactItemData = (array) $response->data->ContactItemData;

                    foreach ($usersList as $user) {
                        $contacts .= view('layouts-chatify.listItem', [
                            'get' => 'users',
                            'user' => $user,
                            'lastMessage' => $arrContactItemData[$user->id]->lastMessage,
                            'unseenCounter' => $arrContactItemData[$user->id]->unseenCounter,
                        ])->render();
                    }
                }else{
                    $contacts = '<p class="message-hint center-el"><span>Your contact list is empty</span></p>';
                }
            } else {
                return response()->json($response->message);
            }

        return Response::json([
            'contacts' => $contacts,
            'total' => $response->data->total,
            'last_page' => $response->data->lastPage,
        ], 200);
        } catch (\Throwable $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

    /**
     * Update user's list item data
     *
     * @param Request $request
     * @return JSON response
     */
    public function updateContactItem(Request $request)
    {
        // Get user data
        $url = env('API_URL') . "updateContacts";
        $body = [
            'user_id' => $request['user_id']
        ];

        try {
            $response = Helper::PostApi($url, $body);

            if ($response->success) {
                $contactItem =  view('layouts-chatify.listItem', [
                    'get' => 'users',
                    'user' => $response->data->user,
                    'lastMessage' => $response->data->lastMessage,
                    'unseenCounter' => $response->data->unseenCounter,
                ])->render();

                return Response::json([
                    'contactItem' => $contactItem,
                ], 200);
            } else {
                return response()->json($response->message);
            }
        } catch (\Throwable $ex) {
            return ['status' => $ex->getMessage()];
        }
    }

    /**
     * Put a user in the favorites list
     *
     * @param Request $request
     * @return void
     */
    public function favorite(Request $request)
    {
        // check action [star/unstar]
        $url = env('API_URL') . "star";
        $body = [
            'user_id' => $request['user_id']
        ];

        try {
            $response = Helper::PostApi($url, $body);

            if ($response->success) {
                return Response::json([
                    'status' => $response->data->status,
                ], 200);
            } else {
                return response()->json($response->message);
            }
        } catch (\Throwable $ex) {
            return response()->json($ex->getMessage());
        }
    }

    /**
     * Get favorites list
     *
     * @param Request $request
     * @return void
     */
    public function getFavorites(Request $request)
    {
        $favoritesList = null;

        $url = env('API_URL') . "/favorites";
        $body = [
//            'message' => $request->input('message')
        ];

        try {
            $response = Helper::PostApi($url, $body);
            if ($response->success) {
                $favorites = $response->data->favorites;
                foreach ($favorites as $favorite) {
                    // get user data
//                    $user = User::where('id', $favorite->favorite_id)->first();
                    $favoritesList .= view('Chatify::layouts.favorite', [
                        'user' => $favorite->user,
                    ]);
                }

                // send the response
                return Response::json([
                    'count' => $response->data->total,
                    'favorites' => $response->data->total > 0
                        ? $favoritesList
                        : 0,
                ], 200);
            }
        } catch (\Throwable $ex) {
            return ['status' => $ex->getMessage()];
        }

    }

    /**
     * Search in messenger
     *
     * @param Request $request
     * @return void
     */
    public function search(Request $request)
    {
        $getRecords = null;
        $input = trim(filter_var($request['input'], FILTER_SANITIZE_STRING));

        $url = env('API_URL') . "search?_token". $request->get("_token") ."&input=".$input."&page=".$request->get("page");
        $body = ['input' => $request->get("input")];

        try {
            $response = Helper::GetApi($url);
            Log::error("backend.response.error", [$body,$url,$response]);
            if ($response->success) {
                foreach ($response->data->records as $record) {
                    $getRecords .= view('layouts-chatify.listItem', [
                        'get' => 'search_item',
                        'type' => 'user',
                        'user' => $record,
                    ])->render();
                }
                if($response->data->total < 1){
                    $getRecords = '<p class="message-hint center-el"><span>Nothing to show.</span></p>';
                }

                // send the response
                return Response::json([
                    'records' => $getRecords,
                    'total' => $response->data->total,
                    'last_page' => $response->data->last_page
                ], 200);
            }
        } catch (\Throwable $ex) {
            //TODO: modify these return statements vijay
            return ['status' => $ex->getMessage()];
        }
    }

    /**
     * Get shared photos
     *
     * @param Request $request
     * @return void
     */
    public function sharedPhotos(Request $request)
    {
        $sharedPhotos = null;

        // shared with its template
        $url = env('API_URL') . 'shared';
        $body = [
            'to_id' => $request['user_id']
        ];
        try {
            $response = Helper::PostApi($url,$body);

            if ($response->success) {
                $shared = $response->data->shared;
                for ($i = 0; $i < count($shared); $i++) {
                    $sharedPhotos .= view('layouts-chatify.listItem', [
                        'get' => 'sharedPhoto',
                        'image' => env('FILE_UPLOAD_URL').env('MESSENGER_FOLDER').'/'. $shared[$i],
                    ])->render();
                }

                return Response::json([
                    'shared' => count($shared) > 0 ? $sharedPhotos : '<p class="message-hint"><span>Nothing shared yet</span></p>',
                ], 200);
            }
        } catch (\Throwable $ex) {
            return ['status' => $ex->getMessage()];
        }
    }

    /**
     * Delete conversation
     *
     * @param Request $request
     * @return void
     */
    public function deleteConversation(Request $request)
    {
        // delete
        $url = env('API_URL') . "deleteConversation";
        $body = [
            'to_id' =>  $request['id']
        ];

        try {
            $response = Helper::PostApi($url, $body);
            if ($response->success) {
                return Response::json([
                    'deleted' => $response->data->deleted,
                ], 200);
            } else {
                return Response::json(response()->json($response->message));
            }
        } catch (\Throwable $ex) {
            return Response::json(['status' => $ex->getMessage()]);
        }
    }

    public function updateSettings(Request $request)
    {
        $msg = null;
        $error = $success = 0;

        $url = env('API_URL') . "updateSettings";
        $body = [
            'dark_mode' => $request['dark_mode'],
            'messengerColor' => $request['messengerColor'],
        ];

        try {
            $response = Helper::PostApi($url, $body);
            if ($response->success) {
                return Response::json([
                    'status' => $response->data->status,
                    'error' => $response->data->error,
                    'message' => $response->data->message,
                ], 200);
            } else {
                return response()->json($response->message);
            }
        } catch (\Throwable $ex) {
            return ['status' => $ex->getMessage()];
        }

    }

    /**
     * Set user's active status
     *
     * @param Request $request
     * @return void
     */
    public function setActiveStatus(Request $request)
    {
        $url = env('API_URL') . "setActiveStatus";
        $body = [
            'status' =>  $request['status']
        ];

        try {
            $response = Helper::PostApi($url, $body);
            if ($response->success) {
                return Response::json([
                    'status' => $response->data->status,
                ], 200);
            } else {
                return response()->json($response->message);
            }
        } catch (\Throwable $ex) {
            return ['status' => $ex->getMessage()];
        }
    }

    public function messageCard($data, $viewType = null){
        $data['viewType'] = ($viewType) ? $viewType : $data['viewType'];
        return view('layouts-chatify.messageCard',$data)->render();
    }

    public function push($channel, $event, $data)
    {
        $sss = $this->pusher->trigger($channel, $event, $data);
        return $sss;

    }

    public function pusherAuthorize($channelName, $socket_id, $data = null){
        return $this->pusher->socket_auth($channelName, $socket_id, $data);
    }

    public function getAllowedImages(){
        return (array) ['png','jpg','jpeg','gif'];
    }

    public function getAllowedFiles(){
        return (array) ['zip','rar','txt'];
    }
}
