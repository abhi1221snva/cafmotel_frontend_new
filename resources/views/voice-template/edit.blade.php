@extends('layouts.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <b>Edit Voice Template</b>
        </h1>

        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Voice</li>
            <li class="active">Edit Voice Template</li>
        </ol>
    </section>

    <section class="content-header">
        <div class="text-right mt-5 mb-3"> 
            <a href="{{ url('/voice-templete') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show Voice Templete</a>
        </div>
    </section>

    <section class="content">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <form method="post" name="userform" id="userform" action="">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="col-form-label">Language</label>
                                        <select id="language_ddl" name="language" class="form-control" onchange="selectVoiceNameOnLanugageChange();">
                                        @foreach($arrLang as $key => $val)
                                            <option @if($sms_templete[0]->language == $key) selected @endif value="{{$key}}">{{base64_decode($key)}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="col-form-label">Voice Name</label>
                                        <select id="voice_name_ddl" name="voice_name" class="form-control">
                                            <option value="">--Select Voice Name--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" value="{{$sms_templete[0]->templete_id}}" name="templete_id" >
                                        <label>Templete Name <i data-toggle="tooltip" data-placement="right" title="Write Voice template name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <input type="text" class="form-control" value="{{$sms_templete[0]->templete_name}}" name="templete_name" >
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                    <label>Labels <i data-toggle="tooltip" data-placement="right" title="Select label from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select id="multiple_labels" class="select2"  multiple="multiple"  autocomplete="off" data-placeholder="Select Labels" style="width: 100%;">
                                            @foreach($label_list as $list)
                                                <option value="<?php echo '{'.$list->title.'}' ?>">{{$list->title}}</option>
                                            @endforeach;
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Sender Details <i data-toggle="tooltip" data-placement="right" title="Select sender details" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select id="multiple_names" class="select2"  multiple="multiple"  autocomplete="off" data-placeholder="Select Names" style="width: 100%;">
                                            @foreach($user_column as $user_list)
                                                <option value="<?php echo '{'.$user_list.'}' ?>">{{$user_list}}</option>
                                            @endforeach;
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label>Custom Placeholders <i data-toggle="tooltip" data-placement="right" title="Select Sender details" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <div class="input-daterange input-group col-md-12">
                                        <select id="multiple_custom_names" class="select2"  multiple="multiple"  autocomplete="off" data-placeholder="Select Names" style="width: 100%;">
                                            <option value="">Select to Insert</option>
                                            @foreach($custom_field_labels as $label_list)
                                            <option value="<?php echo '{'.$label_list->title.'}' ?>">{{$label_list->title}}</option>
                                            @endforeach;
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" value ="{{$sms_templete[0]->pitch}}" id="pitch_value" name="pitch_value">
                            <input type="hidden" value ="{{$sms_templete[0]->speed}}" id="speed_value" name="speed_value">


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Speed</label><span style="float:right;">Value: <span id="speed_id"></span></span>
                                        <div class="input-daterange input-group col-md-12">
                                            <input style="cursor: pointer;" type="range" min="0.25" max="4" value="0.00" class="slider" id="mySpeed">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pitch</label><span style="float:right">Value: <span id="pitch_id"></span></span>
                                        <div class="input-daterange input-group col-md-12">
                                            <input style="cursor: pointer;" type="range" min="-20" max="20" value="0.00" class="slider" id="myPitch">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                    <label>Templete Preview <i data-toggle="tooltip" data-placement="right" title="Add or modify Templete Preview" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <textarea type="text" class="form-control" name="templete_desc" value="" id ="speech_text">{{$sms_templete[0]->templete_desc}}</textarea>
                                            <audio style="display:none;" id="test_audio" controls preload ='none'>
                                                <source src="" type='audio/mp3'>
                                            </audio>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a class="btn btn btn-primary waves-effect waves-light" href="javascript:void(0);" onclick="getAudioOnText();">Listen</a>
                            <button type="submit" name ="submit" value="add" class="btn btn btn-primary waves-effect waves-light">Update</button>
                        </div>
                    </form>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script>
var slider1 = document.getElementById("mySpeed");
var output1 = document.getElementById("speed_id");
output1.innerHTML = slider1.value;

slider1.oninput = function() {
  output1.innerHTML = this.value;
document.getElementById("speed_value").value = this.value;


}

var slider2 = document.getElementById("myPitch");
var output2 = document.getElementById("pitch_id");
output2.innerHTML = slider2.value;

slider2.oninput = function() {
output2.innerHTML = this.value;
document.getElementById("pitch_value").value = this.value;

}
</script>

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
                    var optionView = value.voice_name + " (" + value.ssml_gender+") ";

                    html += '<option value="'+ optionValue +'" ';
                    html += (defaultSelected == optionValue) ? 'selected >' : '>';
                    html += optionView + '</option>';
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
            //toastr.error("Please Select Voice Name");
            return;
        }

        $("#test_audio").attr('src', "");
        $.ajax({
            url: root_url+'/get-audio-on-text',
            type: 'post',
            data: {'language': $('#language_ddl').val(),
                'voice_name_ddl': $('#voice_name_ddl').val(),
                'speed': $('#speed_value').val(),
                'pitch': $('#pitch_value').val(),
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

<script>


    function displayVals() {
        //$( "#speech_text" ).val('');
        var singleValues = $( "#speech_text" ).val();
        var multipleValues = $( "#multiple_labels" ).val() || [];
        $( "#speech_text" ).val(singleValues+' '+ multipleValues.join( ", " ) );
    }
    $( "#multiple_labels" ).on('change',function(){ 
        displayVals();
    });


     function displayValsNames() {
        var singleValues = $( "#speech_text" ).val();
        var multipleValues = $( "#multiple_names" ).val() || [];
        var input = singleValues+','+ multipleValues.join( ", " );
        var splitted = input.split(',');
        var collector = {};

        for (i = 0; i < splitted.length; i++) {
            key = splitted[i].replace(/^\s*/, "").replace(/\s*$/, "");
            collector[key] = true;
        }

        var out = [];
        for (var key in collector) {
            out.push(key);
        }
        var output = out.join(',');
       // alert(output);
        $( "#speech_text" ).val(output);
    }

    $( "#multiple_names" ).on('change',function(){ 
        displayValsNames();
    });


$('#multiple_labels').on('select2:select', function (e) {
    var data = e.params.data;
	$("#multiple_labels").val(null).trigger("change");
});

$('#multiple_names').on('select2:select', function (e) {
   // var data = e.params.data;
	$("#multiple_names").val(null).trigger("change");
});


function displayCustom() {
    var singleValues = $( "#speech_text" ).val();
    var multipleValues = $( "#multiple_custom_names" ).val() || [];
    $( "#speech_text" ).val(singleValues+' '+ multipleValues.join( " " ) );
    $( "#multiple_custom_names" ).val('');
}

$( "#multiple_custom_names" ).on('change',function() { 
    displayCustom();
});

$('#multiple_custom_names').on('select2:select', function (e) {
    $("#multiple_custom_names").val(null).trigger("change");
});



</script>
@endsection


