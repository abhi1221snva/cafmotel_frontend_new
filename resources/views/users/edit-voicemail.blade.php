@extends('layouts.app')
@section('title', 'Voicemail List')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<style>
    .ivr-input-types .ivr_type_txt_to_speech, .ivr-input-types .ivr_type_audio_record{
        margin-top: 10px;
    }
    .ivr-input-types {
        text-align: center;
    }
    .recording-status{
        display:none;
        padding-left: 10px;
        color: red;
        animation: blinker 1s linear infinite;
    }
    #record {
        border-radius: 50%;
        background-color: red;
        color: #fff;
        font-size: 20px;
        width: 60px;
        height: 60px;
        border: none;
    }
    #stopRecord{
        border-radius: 50%;
        background-color: #3c8dbc;
        color: #fff;
        font-size: 20px;
        width: 60px;
        height: 60px;
        border: none;
        margin-left: 5px;
    }
    #recordedAudio{
        vertical-align: middle;
        margin-left: 10px;
    }
    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
     <section class="content-header">
            <h1>
               <b>Voicemail Drop</b>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Profile</li>
                <li class="active">Voicemail Drop</li>
            </ol>
    </section>
    <section class="content-header">
       <div class="text-right mt-5 mb-3">
            <a href="{{ url('/profile') }}"  class="btn btn-sm btn-primary"><i class="fa fa-arrow-circle-left"></i>&nbsp;Back</a>
       </div>
        </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-xs-12">
                <div class="box">

                    <div class="box-body">
                        <form method="post" action="{{ route('voice.mail.post') }}" enctype="multipart/form-data" class="edit_ivr_form">
                            @csrf
                            <div class="box-body">

                                <input type="hidden" class="form-control" name="id" value="{{isset($ivr_data->id) ? $ivr_data->id : "0"}}" id ="id" required>
                                <input type="hidden" class="form-control" name="old_ann_id" value="{{isset($ivr_data->ann_id) ? $ivr_data->ann_id : "0"}}" id="ann_id" required>

                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="" class="col-form-label">Audio File Description <i data-toggle="tooltip" data-placement="right" title="Type audio file description" class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span></label>
                                        <input value="{{isset($ivr_data->ivr_desc) ? $ivr_data->ivr_desc : ""}}" type="text" class="form-control" required  name="ivr_desc" id="ivr_desc" placeholder="IVR DESC">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row ivr-input-types">
                                    <div class="col-sm-6 radio ivr_type_file">

                                        <label for="ivr_audio_option_upload_file" class="col-form-label">
                                            <input type="radio" checked="checked" id="ivr_audio_option_upload_file" name="ivr_audio_option" value="upload" onclick="selectIvrUploadFileOption('upload_file_div');" />
                                            Upload File
                                        </label>
                                    </div>
                                    <div class="col-sm-6 radio ivr_type_txt_to_speech">
                                        <label for="ivr_audio_option_audio" class="col-form-label">
                                            <input type="radio" id="ivr_audio_option_audio" name="ivr_audio_option" value="text_to_speech" onclick="selectIvrUploadFileOption('text_to_speech_div');" />
                                            Convert Text to Audio
                                        </label>
                                    </div>
{{--                                    <div class="col-sm-4 radio ivr_type_audio_record">--}}
{{--                                        <label for="ivr_audio_option_audio_record" class="col-form-label">--}}
{{--                                            <input type="radio" @if(isset($ivr_data->prompt_option)) @if($ivr_data->prompt_option == 2) checked="checked" @endif @endif id="ivr_audio_option_audio_record" name="ivr_audio_option" value="audio_record" onclick="selectIvrUploadFileOption('audio_record');" />--}}
{{--                                            Record Audio--}}
{{--                                        </label>--}}
{{--                                    </div>--}}
                                </div>
                                <hr>
                                <div class="form-group row" id="upload_file_div">
                                    <div class="col-sm-6">
                                        <label for="" class="col-form-label">Upload File <i data-toggle="tooltip" data-placement="right" title="Upload only mp3 or wav file type" class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">* &nbsp; (Only mp3 or wav file type is allowed)</span></label>
                                        <input type="file" accept="audio/*"  class="form-control"   name="ann_id"  placeholder="Please Upload file"  />
                                    </div>
                                </div>
                                <div class="form-group row" id="text_to_speech_div" style="display: none;">
                                    <div class="col-sm-6">
                                        <label for="" class="col-form-label">Language</label>
                                        <select id="language_ddl" name="language" class="form-control" onchange="selectVoiceNameOnLanugageChange();">
                                            <option value="">--Select Language--</option>
                                            @foreach($arrLang as $key => $val)
                                            <option {{isset($ivr_data->language) && $ivr_data->language == base64_decode($key) ? "selected='selected'" : ""}} value="{{$key}}">{{base64_decode($key)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="" class="col-form-label">Voice Name</label>
                                        <select id="voice_name_ddl" name="voice_name" class="form-control">
                                            <option value="">--Select Voice Name--</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-10" style="padding-top: 10px;">
                                        <label for="" class="col-form-label">Text </label>
                                        <textarea id="speech_text" class="form-control" name="speech_text"
                                            placeholder="Type what you like your customers to hear and click on Listen button to listen">{{isset($ivr_data->speech_text) ? $ivr_data->speech_text : ""}}</textarea>
                                        <audio style="display:none;" id="test_audio" controls preload ='none'>
                                            <source src="" type='audio/mp3'>
                                        </audio>
                                    </div>
                                    <div class="col-sm-2" style="padding-top: 30px;">
                                        <a class="btn btn-primary" href="javascript:void(0);" onclick="getAudioOnText();">Listen</a>
                                    </div>
                                </div>
                                <div class="form-group row" id="record_audio" style="display: none;">
                                    <div class="col-sm-6">
                                        <button type="button" id="record" class="btn"><i class="fa fa-microphone"></i></button>
                                        <button type="button" id="stopRecord" class="btn" disabled><i class="fa fa-stop"></i></button>
                                        <span class="recording-status">Voice recording...</span>
                                        <audio id=recordedAudio></audio>
                                    </div>
                                </div>
                                <button type="submit" name ="submit"  class="btn btn btn-primary waves-effect waves-light">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>

    $(document).ready(function () {
        setTimeout(function() {
            selectVoiceNameOnLanugageChange();
        }, 1000);

        $(".edit_ivr_form").submit(function () {
            if ($("#ivr_audio_option_audio").prop("checked") && !$("#test_audio").attr("src")) {
                toastr.info("Please listen to the audio before submitting");
                return false;
            }
        });

        var prompt_option = @if(isset($ivr_data->prompt_option)) @php echo $ivr_data->prompt_option @endphp @else"0"@endif;
        var voice_name = "@if(isset($ivr_data->voice_name))@php echo $ivr_data->voice_name @endphp@else 0 @endif";

        if(prompt_option == 2){
            $("#ivr_audio_option_audio_record").prop("checked", true);
            $("#upload_file_div").hide();
            $("#text_to_speech_div").hide();
            $("#record_audio").show();
        } else if(prompt_option == 1){
            $("#ivr_audio_option_audio").prop("checked", true);
            $("#upload_file_div").hide();
            $("#record_audio").hide();
            $("#text_to_speech_div").show();

            setTimeout(function() {
                selectVoiceNameOnLanugageChange(voice_name);
            }, 2000);
        } else{
            $("#ivr_audio_option_upload_file").prop("checked", true);
            $("#record_audio").hide();
            $("#upload_file_div").show();
            $("#text_to_speech_div").hide();
        }
    });

    function selectIvrUploadFileOption(option) {
        if (option == 'text_to_speech_div') {
            $("#text_to_speech_div").show();
            $("#upload_file_div").hide();
            $("#record_audio").hide();
        } else if(option == 'audio_record') {
            getAudioRecordPermission();
            $("#record_audio").show();
            $("#upload_file_div").hide();
            $("#text_to_speech_div").hide();
        } else {
            $("#upload_file_div").show();
            $("#text_to_speech_div").hide();
            $("#record_audio").hide();
        }
    }

    function selectVoiceNameOnLanugageChange(defaultSelected = null) {
        $.ajax({
            url: root_url+'/get-voice-name-on-lanugage',
            type: 'post',
            data: {'language': $('#language_ddl').val()},
            success: function (response) {
                var html = '';
                $.each(response, function (index, value) {
                    var optionValue = value.language_code + " ## " + value.voice_name + " ## " + value.ssml_gender;
                    html += '<option value="'+ optionValue +'" ';
                    html += (defaultSelected == optionValue) ? 'selected >' : '>';
                    html += optionValue + '</option>';
                });
                $("#voice_name_ddl").html('');
                $("#voice_name_ddl").html(html);
            }
        });

    }

    function getAudioOnText() {
        if ($('#speech_text').val() == '') {
            toastr.error("Please Type Text");
            return;
        }
        if ($('#language_ddl').val() == '') {
            toastr.error("Please Select Language");
            return;
        }
        if ($('#voice_name_ddl').val() == '' || $('#voice_name_ddl').val() == null) {
            toastr.error("Please Select Voice Name");
            return;
        }

        $("#test_audio").attr('src', "");
        $.ajax({
            url: root_url+'/get-audio-on-text',
            type: 'post',
            data: {'language': $('#language_ddl').val(),
                'voice_name_ddl': $('#voice_name_ddl').val(),
                'speech_text': $('#speech_text').val()},
            success: function (response) {
                if (typeof (response.file) != 'undefined') {
                    var file = "{{env('FILE_UPLOAD_URL')}}" + "{{env('IVR_FILE_UPLOAD_FOLDER_NAME')}}" + "/" + response.file;
                     var d = new Date();
                    $("#test_audio").attr('src', file+"?"+d.getTime());
                    var x = document.getElementById("test_audio");
                    x.play();
                } else {

                }
            }
        });
    }

    function getAudioRecordPermission(){
        navigator.mediaDevices.getUserMedia({audio:true})
            .then(stream => {handlerFunction(stream)});
    }

    function handlerFunction(stream) {
        rec = new MediaRecorder(stream);
        rec.ondataavailable = e => {
            audioChunks.push(e.data);
            if (rec.state == "inactive"){
                let blob = new Blob(audioChunks,{type:'audio/wav'});
                recordedAudio.src = URL.createObjectURL(blob);
                recordedAudio.controls=true;
                recordedAudio.autoplay=true;
                sendData(blob)
            }
        }
    }

    function sendData(data) {
        var fd = new FormData();
        fd.append('data', data);

        $.ajax({
            url: '/save-recorded-audio',
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            success: function (response){
                // console.log(response);
            },
            error: function (response) {
                console.log(response);
            }
        });
    }

    record.onclick = e => {
        record.disabled = true;
        $(".recording-status").show();
        stopRecord.disabled=false;
        audioChunks = [];
        rec.start();
    }
    stopRecord.onclick = e => {
        record.disabled = false;
        stop.disabled=true;
        $(".recording-status").hide();
        rec.stop();
    }
</script>
@endsection
