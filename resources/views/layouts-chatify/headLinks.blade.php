<title>{{ env('CHAT_NAME') }}</title>

{{-- Meta tags --}}
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="id" content="{{ $id }}">
<meta name="type" content="{{ $type }}">
<meta name="messenger-color" content="{{ $messengerColor }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="url" content="{{ url('') }}"  data-user="{{ Session::get('id') }}">
<meta name="video_url" content="{{ env('VIDEO_CALL_URL') }}"  data-user="{{ Session::get('id') }}">

{{-- scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
<script src="{{ asset('asset/js/font.awesome.min.js') }}"></script>
<script src="{{ asset('asset/js/autosize.js') }}"></script>
<script src="{{ asset('asset/js/app.js') }}"></script>
<script src='https://unpkg.com/nprogress@0.2.0/nprogress.js'></script>

{{-- styles --}}
<link rel='stylesheet' href='https://unpkg.com/nprogress@0.2.0/nprogress.css'/>
<link href="{{ asset('asset/css/style.css') }}" rel="stylesheet" />
<link href="{{ asset('asset/css/'.$dark_mode.'.mode.css') }}" rel="stylesheet" />
{{--<link href="http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" rel="stylesheet" />--}}
<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
{{--<link href="{{ asset('asset/css/app.css') }}" rel="stylesheet" />--}}

{{-- Messenger Color Style--}}
@include('layouts-chatify.messengerColor')
