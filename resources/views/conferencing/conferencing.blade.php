@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<style>
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
                   <b>Conferencing List </b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Conferencing</li>
                    <li class="active">Conferencing List </li>
                </ol>
        </section>

         <section class="content-header">
                   <div class="text-right mt-5 mb-3">
                  <a id="openIpSettingForm" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Conferencing</a>
           </div>
        </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table id="example" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <!-- <th>Account No</th> -->
                                    <th>Title</th>
                                    <th>Conference Id</th>
                                    <th>Host Pin</th>
                                    <th>Participant Pin</th>
                                    <th>Max Participant</th>
                                    <th>Lock</th>
                                    <th>Mute</th>
                                    <th>Prompt File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            @if(!empty($conferencing_list))
                            <tbody>

                                @foreach($conferencing_list as $key => $conference)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{$conference->title}}</td>
                                    <td>{{$conference->conference_id}}</td>
                                    <td>{{$conference->host_pin}}</td>
                                    <td>{{$conference->part_pin}}</td>
                                    <td>{{$conference->max_part}}</td>
                                    <td>
                                        @if ($conference->locked == '1')
                                            <span class="badge bg-green">YES</span>

                                        @else
                                            <span class="badge bg-red">NO</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($conference->mute == '1')
                                            <span class="badge bg-green">YES</span>

                                        @else
                                            <span class="badge bg-red">NO</span>
                                        @endif
                                    </td>
                                    <td><audio controls preload ='none'><source src="{{env('FILE_UPLOAD_URL')}}{{env('CONFERENCE_FILE_UPLOAD_FOLDER_NAME')}}/{{$conference->prompt_file}}.wav" type='audio/wav'></audio></td>
                                    <td>
                                        <a style="cursor:pointer;color:blue;" class='editConference' data-auto_id={{$conference->id}}  ><i class="fa fa-edit fa-lg"></i></a> |
                                        <a style="cursor:pointer;color:red;" class='openConferenceDelete' data-id={{$conference->id}}><i class="fa fa-trash fa-lg"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            @endif
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="add-edit"></h4>
                    </div>

                    <form method="post" action="" enctype="multipart/form-data" id="conferencing">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" class="form-control" name="auto_id" value="" id="id">
                            <label for="inputEmail3" class="col-form-label ">Title <i data-toggle="tooltip" data-placement="right" title="Type conference title" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <input type="text" class="form-control closed" required name="title" id="title" placeholder="Enter title">

                            <label for="inputEmail3" class="col-form-label ">Conference Id <i data-toggle="tooltip" data-placement="right" title="Type/Generate Conference Id" class="fa fa-info-circle" aria-hidden="true"></i></label>
                             <div class="input-group">
                                <input type="text" readonly="" onkeypress="//return isNumberKey($(this));" class="form-control" required name="conference_id" id="conference_id" placeholder="">
                                <!-- /btn-group -->
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('conference_id').value = getConferenceId(1000,9999)">Generate</button>
                                </div>
                              </div>

                            <label for="inputEmail3" class="col-form-label ">Host Pin <i data-toggle="tooltip" data-placement="right" title="Type/Generate host pin" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-group">
                                <input type="text" readonly="" onkeypress="//return isNumberKey($(this));" class="form-control" required name="host_pin" id="host_pin" placeholder="">
                                <!-- /btn-group -->
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('host_pin').value = getHostId(1000,9999)">Generate</button>
                                </div>
                            </div>

                            <label for="inputEmail3" class="col-form-label ">Participant Pin <i data-toggle="tooltip" data-placement="right" title="Type/Generate participant pin" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-group">
                                <input type="text" readonly="" onkeypress="//return isNumberKey($(this));" class="form-control" required name="part_pin" id="part_pin" placeholder="">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('part_pin').value = getPartId(1000,9999)">Generate</button>
                                </div>
                            </div>

                            <label for="inputEmail3" class="col-form-label ">Maximum Participant <i data-toggle="tooltip" data-placement="right" title="Type maximum participant pin" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <input type="text" onkeypress="//return isNumberKey($(this));" class="form-control" required name="max_part" id="max_part" placeholder="Enter Part Pin">

                            <label for="inputEmail3" class="col-form-label ">Lock <i data-toggle="tooltip" data-placement="right" title="Select yes/no for lock" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <select class="form-control" name="locked" id="locked">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>

                            <label for="inputEmail3" class="col-form-label ">Mute <i data-toggle="tooltip" data-placement="right" title="Select yes/no for mute" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <select class="form-control" name="mute" id="mute">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>

                            <label for="" class="col-form-label" style=" margin-top: 10px;">Choose Prompt <i data-toggle="tooltip" data-placement="right" title="Choose file for prompt" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="col-sm-12" style="margin-bottom:10px;">
                                <div class="col-sm-6 radio">
                                    <label for="prompt_upload_file_input" class="col-form-label">
                                        <input type="radio" checked="checked" id="prompt_upload_file_input" class="prompt_input" name="ivr_audio_option" value="upload" onclick="selectIPromptFileOption('prompt_upload_file');" />
                                        Upload File
                                    </label>
                                </div>
                                <div class="col-sm-6 radio" style="margin-top: 10px;">
                                    <label for="prompt_text_to_speech_input" class="col-form-label">
                                        <input type="radio" id="prompt_text_to_speech_input" class="prompt_input" name="ivr_audio_option" value="text_to_speech" onclick="selectIPromptFileOption('prompt_text_to_speech');" />
                                        Convert Text to Audio
                                    </label>
                                </div>
{{--                                <div class="col-sm-4 radio" style="margin-top: 10px;">--}}
{{--                                    <label for="ivr_audio_option_audio_record" class="col-form-label">--}}
{{--                                        <input type="radio" id="ivr_audio_option_audio_record" class="prompt_input" name="ivr_audio_option" value="audio_record" onclick="selectIPromptFileOption('audio_record');" />--}}
{{--                                        Record Audio--}}
{{--                                    </label>--}}
{{--                                </div>--}}
                            </div>

                            <div id="prompt_upload_file">
                                <input type="file"  class="form-control" name="prompt_file" id="prompt" placeholder="Enter Part Pin">
                            </div>

                            <div class="form-group row" id="prompt_text_to_speech" style="display: none;">
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
                                    <audio style="margin-top:10px; display:none;" id="test_audio" controls preload ='none'>
                                        <source src="" type='audio/mp3'>
                                    </audio>
                                </div>
                                <div class="col-sm-2" style="padding-top: 30px;">
                                    <a class="btn btn-primary" href="javascript:void(0);" onclick="getAudioOnText();">Listen</a>
                                </div>
                            </div>

                            <div class="form-group row" id="record_audio" style="display: none;">
                                <div class="col-sm-12">
                                    <button type="button" id="record" class="btn"><i class="fa fa-microphone"></i></button>
                                    <button type="button" id="stopRecord" class="btn" disabled><i class="fa fa-stop"></i></button>
                                    <span class="recording-status">Voice recording...</span>
                                    <audio id=recordedAudio></audio>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="submit" class="btn btn-info btn-ok">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="delete" role="dialog">
            <!-- Modal content-->
            <div class="modal-dialog">
                <div class="modal-content" style="background-color: #d33724 !important;color:white;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                    </div>
                    <div class="modal-body">
                        <p>You are about to delete <b><i class="title"></i></b> dnc record.</p>
                        <p>Do you want to proceed?</p>
                        <input type="hidden" class="form-control" name="auto_id" value="" id="auto_id">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger btn-ok deleteConferencing">Delete</button>
                    </div>
                </div>
            </div>
        </div>
</section>
</div>

<script>
    $(document).ready(function() {
        var oTable = $('#example').dataTable({
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [6, 7, 8,9]
            }]
        });

        $('#conferencing').submit(function (e) {
            if($("#prompt_upload_file_input").prop("checked")){
                if( document.getElementById("videoUploadFile").files.length == 0){
                    toastr.error("Please select prompt file");
                    return false;
                }
            }

            if($("#prompt_text_to_speech_input").prop("checked")){
                if(($('#speech_text').val().trim().length <= 0)){
                    toastr.error("Please enter text");
                    return false;
                }

                if(!$("#test_audio").attr("src")) {
                    toastr.info("Please listen to the audio before submitting");
                    return false;
                }
            }
        });
    });

    $(".openConferenceDelete").click(function() {
        var auto_id = $(this).data('id');
        $("#delete").modal();
        $("#auto_id").val(auto_id);
    });

    $("#openIpSettingForm").click(function() {
        $("#myModal").modal();
        $("#title").val('');
        $("#conference_id").val('');
        $("#host_pin").val('');
        $("#part_pin").val('');
        $("#max_part").val('');
        $("#locked").val('1');
        $("#mute").val('1');
        $("#id").val('');
        $("#speech_text").val('');
        $(".closed").show();
        $("#add-edit").html('Add Conference Id');
    });

    $(document).on("click", ".editConference", function() {
        $("#myModal").modal();
        $("#add-edit").html('Edit Conference Id');
        var auto_id = $(this).data('auto_id');
       //alert(auto_id);

        $.ajax({
            url: 'editConferencing/' + auto_id,
            type: 'get',
            success: function(response) {
                $("#id").val(response[0].id);
                $("#title").val(response[0].title);
                $("#conference_id").val(response[0].conference_id);
                $("#host_pin").val(response[0].host_pin);
                $("#part_pin").val(response[0].part_pin);
                $("#max_part").val(response[0].max_part);
                $("#locked").val(response[0].locked);
                $("#mute").val(response[0].mute);
                $("#speech_text").val(response[0].speech_text);
                $("#language_ddl").val(response[0].language);

                if(response[0].prompt_option == 2){
                    $("#ivr_audio_option_audio_record").prop("checked", true);
                    $("#prompt_upload_file").hide();
                    $("#prompt_text_to_speech").hide();
                    $("#record_audio").show();
                } else if(response[0].prompt_option == 1){
                    selectVoiceNameOnLanugageChange(response[0].voice_name);
                    $("#prompt_text_to_speech_input").prop("checked", true);
                    $("#prompt_upload_file").hide();
                    $("#record_audio").hide();
                    $("#prompt_text_to_speech").show();
                } else{
                    $("#prompt_upload_file_input").prop("checked", true);
                    $("#record_audio").hide();
                    $("#prompt_upload_file").show();
                    $("#prompt_text_to_speech").hide();
                }
            }
        });
    });

    $(document).on("click", ".deleteConferencing", function() {
        // if(confirm("Are you sure you want to delete this?")){
        var auto_id = $("#auto_id").val();
        var el = this;
        $.ajax({
            url: 'deleteConferencing/' + auto_id,
            type: 'get',
            success: function(response) {
                window.location.reload(1);
            }
        });
        //}
        /*  else
          {
              return false;
          }*/
    });


    function selectIPromptFileOption(option) {
        if (option == 'prompt_text_to_speech') {
            $("#prompt_upload_file").hide();
            $("#record_audio").hide();
            $("#prompt_text_to_speech").show();
        } else if(option == 'audio_record'){
            getAudioRecordPermission();
            $("#prompt_upload_file").hide();
            $("#prompt_text_to_speech").hide();
            $("#record_audio").show();
        }else {
            $("#record_audio").hide();
            $("#prompt_upload_file").show();
            $("#prompt_text_to_speech").hide();
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
                'speech_text': $('#speech_text').val(),
                'prompt_for' : 'conferencing'},
            success: function (response) {
                console.log(response);
                if (typeof (response.file) != 'undefined') {
                    var file = "{{env('FILE_UPLOAD_URL')}}" + "{{env('CONFERENCE_FILE_UPLOAD_FOLDER_NAME')}}" + "/" + response.file;
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
            url: '/conferencing/save-recorded-audio',
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

<script>
function getConferenceId(min, max) {
  return Math.floor(Math.random() * (max - min)) + min;
}

function getHostId(min, max) {
  return Math.floor(Math.random() * (max - min)) + min;
}

function getPartId(min, max) {
  return Math.floor(Math.random() * (max - min)) + min;
}
</script>

<script src="{{ asset('asset/plugins/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('asset/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('asset/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>

@endsection
