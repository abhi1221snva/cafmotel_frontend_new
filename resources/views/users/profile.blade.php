@extends('layouts.app')
@section('content')

<?php

use \App\Http\Controllers\InheritApiController;
$userdetails = InheritApiController::headerUserDetails();
//echo $userdetails->data->last_name;die;
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->

     <section class="content-header">
                <h1>
                   <b>Profile</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Profile</li>
                </ol>
        </section>




    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">

                        <!-- 
                         <img src="{{ asset('profile-pic') }}/{{$userdetails->data->profile_pic}}" class="profile-user-img img-responsive img-circle" alt="User profile picture">
                        -->   
                        <div class="container">








                            <?php
                if(!empty($userdetails->data->profile_pic)){
                if (file_exists(public_path() . '/profile-pic/' . $userdetails->data->profile_pic)) { ?>
                            <img src="{{ asset('profile-pic') }}/{{$userdetails->data->profile_pic}}" alt="Avatar" class="profile-user-img img-responsive img-circle image">
                            <?php }else {?>
                            <img src="{{ asset('asset/img/user-128x128.png') }}" alt="Avatar" class="profile-user-img img-responsive img-circle image">
                            <?php }
                        }?>

                            <div class="overlay">
                                <form id="form" action="{{ route('image.upload.post') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="icon"> <i class="fa fa-camera upload-button"></i></div>
                                    <input id="html_btn" name="image" type='file'" /><br>
                                </form>
                            </div>

                        </div> 







                        <h3 class="profile-username text-center">{{$userdetails->data->first_name}} {{$userdetails->data->last_name}}</h3>
                        <p class="text-muted text-center">{{ $userdetails->data->company_name }}</p>
                        <p class="text-muted text-center">Member since {{ \Carbon\Carbon::parse($userdetails->data->created_at)->format('dS M Y')}}</p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Email</b> <a class="pull-right">{{$userdetails->data->email}}</a>
                            </li>
                            <li class="list-group-item ">
                                <b>Phone No</b> <a class="pull-right phonehtml">{{$userdetails->data->mobile}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Address</b> <br><a class="">{{$userdetails->data->address_1}}, {{$userdetails->data->address_2}}</a>
                            </li>

                             <li class="list-group-item">
                                <b>Extension</b> <br><a class="">{{$userdetails->data->extension}}</a>
                                <br>
                                <div class="form-group">
                                    <label><b>Password</b></label>
                                    <div class="input-group">
                                        <input style="border: none;margin-left:-11px;border-color: transparent;"type="password" class="form-control" id="secret" placeholder="Password" name="password" value="{{$userdetails->data->user_extension->secret}}" required />

                                <div class="input-group-addon" style="border: none;border-color: transparent;">
                                    <i title="View Password" style="cursor: pointer;" onclick="showPassword()" class="fa fa-eye fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                        <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

                <!-- About Me Box -->
                <!-- /.box -->
            </div><!-- /.col -->
                <div class="col-md-9">

                   

                 


                    @if (count($errors) > 0)


                </div>


                @endif
            <!-- {{Session::get('tab3')}} -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <!-- <li class="active"><a href="#activity" data-toggle="tab">Plan</a></li> -->
                    <li class="active"><a href="#timeline" data-toggle="tab">Edit Profile</a></li>
                    <li class=""><a href="#settings" data-toggle="tab">Change Password</a></li>
                    <li class=""><a href="#voicemail" data-toggle="tab">Voicemail Drop</a></li>

                </ul>
                <div class="tab-content">

                    <!-- voice mail drop -->
                    <div class="tab-pane" id="voicemail">
                        <!-- Post -->

                        
                        <div class="post">
                            @if(!empty($voicemail))
                            <table class="table table-bordered">
                                <tbody><tr>
                                        <th style="width: 10px">#</th>
                                        <th>Extension</th>
                                        <th>File Name</th>
                                        <th style="width: 40px">Action</th>
                                    </tr>
                                    @foreach($voicemail as $mail)
                                    <tr>
                                        <td>1</td>
                                        <td>{{$userdetails->data->extension}}</td>
                                      <td>
                                        <audio controls preload ='none'><source src="{{env('FILE_UPLOAD_URL')}}{{env('IVR_FILE_UPLOAD_FOLDER_NAME')}}/{{$mail->ivr_id}}.wav" type='audio/wav'></audio>
                                        </td> 

                                        <td> 
                                            <a href="/profile/edit-voicemail/{{$mail->id}}" style="cursor:pointer;color:blue;"  ><i class="fa fa-edit fa-lg"></i></a>
                                            | 
                                            <a style="cursor:pointer;color:red;" class='voicemailDelete' data-voicemailid = {{$mail->id}} data-id={{$userdetails->data->id}} ><i class="fa fa-trash fa-lg"></i></a>
                                        </td>
                                        
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>


                            {{--@if(!empty($userdetails->data->vm_drop_location))
                            <table class="table table-bordered">
                                <tbody><tr>
                                        <th style="width: 10px">#</th>
                                        <th>Extension</th>
                                        <th>File Name</th>
                                        <th style="width: 40px">Action</th>
                                    </tr>

                                    <tr>
                                        <td>1</td>
                                        <td>{{$userdetails->data->extension}}</td>
                                       <!--  <td><audio controls preload ='none'><source src="{{$userdetails->data->vm_drop_location}}" type='audio/wav'></audio></td> -->

                                            <td><audio controls preload ='none'><source src="{{env('FILE_UPLOAD_URL')}}{{env('IVR_FILE_UPLOAD_FOLDER_NAME')}}/{{$userdetails->data->vm_drop_location}}.wav" type='audio/wav'></audio></td>


                                        <td>
                                            <a style="cursor:pointer;color:red;" class='voicemailDelete' data-id={{$userdetails->data->id}} ><i class="fa fa-trash fa-lg"></i></a>
                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                            --}}
                            @else

                            
                        


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

                        <!-- <form id="form_voiemail" action="{{ route('voice.mail.post') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label>Please Select Wav file</label>
                                <input class="form-control" type="file" id="voicemail"  name="voice_mail" placeholder="Type a comment">
                            </form> -->
                            @endif

                        </div><!-- /.post -->

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


                    </div><!-- /.tab-pane -->
                    <!-- end voice mail drop -->
                    <div class=" tab-pane" id="activity">
                        <!-- Post -->
                        <div class="post">

                            <p>
                                Lorem ipsum represents a long-held tradition for designers,
                                typographers and the like. Some people hate it and argue for
                                its demise, but others ignore the hate as they create awesome
                                tools to help create filler text for everyone from bacon lovers
                                to Charlie Sheen fans.
                            </p>


                            <input class="form-control input-sm" type="text" placeholder="Type a comment">
                        </div><!-- /.post -->


                    </div><!-- /.tab-pane -->
                    <div class="active tab-pane" id="timeline">
                        <!-- The timeline -->
                        <form enctype="multipart/form-data" class="form-horizontal" method="post" action="{{url('updateProfile')}}">
                            @csrf
                            <input type="hidden" name="user_id" id="user_id" value="{{$userdetails->data->id}}" />
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="inputEmail3" class="col-form-label">First Name</label>
                                    <input type="text" class="form-control" value="{{$userdetails->data->first_name}}" name="first_name" id="first_name" required placeholder="">
                                </div>

                                <div class="col-sm-6">
                                    <label for="inputPassword3" class="col-form-label">Last Name</label>

                                    <input type="text" class="form-control" required name="last_name" value="{{$userdetails->data->last_name}}" id="last_name" placeholder="">
                                </div>
                            </div>


                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="inputPassword3" class="col-form-label">Email</label>

                                    <input readonly="readonly" type="email" class="form-control" required name="email" value="{{$userdetails->data->email}}" id="email" placeholder="">
                                </div>

                                <div class="col-sm-6">
                                    <label for="inputPassword3" class="col-form-label">Company Name</label>

                                    @if($userdetails->data->role == 'admin')

                                    <input type="text"  class="form-control"  name="company_name" value="{{$userdetails->data->company_name}}" id="company_name" placeholder="">
                                    @else
                                    <input type="text"  readonly="readonly" class="form-control"  name="company_name" value="{{$userdetails->data->company_name}}" id="company_name" placeholder="">
                                    @endif

                                </div>





                            </div>


                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="inputName" class="control-label">Address 1</label>

                                    <textarea class="form-control" id="address_1" name="address_1">{{$userdetails->data->address_1}}</textarea>
                                </div>

                                <div class="col-sm-6">
                                    <label for="inputName" class="control-label">Address 2</label>


                                    <textarea class="form-control" id="address_2" name="address_2">{{$userdetails->data->address_2}}</textarea>
                                </div>
                            </div>


                            <div class="form-group row">


                                <div class="col-sm-6">
                                    <label for="inputPassword3" class="col-form-label">Phone No</label>

                                    <input type="text" class="form-control phone" required name="phone" value="{{$userdetails->data->mobile}}" id="phone" placeholder="">
                                </div>


                                <!--  <div class="col-sm-6">
                                    <label for="inputPassword3" class="col-form-label">Profile Image</label>
                          
                               <input type="file" name="image" id="file"/>
                                </div> -->


                                <div class="col-sm-6">
                                    <label for="inputPassword3" class="col-form-label">Timezone</label>

                                   <select class="form-control" name="timezone" required>
                                    <option value="">Select Timezone</option>
                                    <option @if($userdetails->data->timezone == 'Pacific/Midway') selected @endif value="Pacific/Midway">(GMT-11:00) Midway Island, Samoa</option>

                                    <option @if($userdetails->data->timezone == 'America/Adak') selected @endif value="America/Adak">(GMT-10:00) Hawaii-Aleutian</option>

                                    <option @if($userdetails->data->timezone == 'Etc/GMT+10') selected @endif value="Etc/GMT+10">(GMT-10:00) Hawaii</option>

                                    <option @if($userdetails->data->timezone == 'Pacific/Marquesas') selected @endif value="Pacific/Marquesas">(GMT-09:30) Marquesas Islands</option>

                                    <option @if($userdetails->data->timezone == 'Pacific/Gambier') selected @endif value="Pacific/Gambier">(GMT-09:00) Gambier Islands</option>

                                    <option @if($userdetails->data->timezone == 'America/Anchorage') selected @endif value="America/Anchorage">(GMT-09:00) Alaska</option>

                                    <option @if($userdetails->data->timezone == 'America/Ensenada') selected @endif value="America/Ensenada">(GMT-08:00) Tijuana, Baja California</option>

                                    <option @if($userdetails->data->timezone == 'Etc/GMT+8') selected @endif value="Etc/GMT+8">(GMT-08:00) Pitcairn Islands</option>

                                    <option @if($userdetails->data->timezone == 'America/Los_Angeles') selected @endif value="America/Los_Angeles">(GMT-08:00) Pacific Time (US & Canada)</option>

                                    <option @if($userdetails->data->timezone == 'America/Denver') selected @endif value="America/Denver">(GMT-07:00) Mountain Time (US & Canada)</option>

                                    <option @if($userdetails->data->timezone == 'America/Chihuahua') selected @endif value="America/Chihuahua">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>

                                    <option @if($userdetails->data->timezone == 'America/Dawson_Creek') selected @endif value="America/Dawson_Creek">(GMT-07:00) Arizona</option>

                                    <option @if($userdetails->data->timezone == 'America/Belize') selected @endif value="America/Belize">(GMT-06:00) Saskatchewan, Central America</option>

                                    <option @if($userdetails->data->timezone == 'America/Cancun') selected @endif value="America/Cancun">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>

                                    <option @if($userdetails->data->timezone == 'Chile/EasterIsland') selected @endif value="Chile/EasterIsland">(GMT-06:00) Easter Island</option>

                                    <option @if($userdetails->data->timezone == 'America/Chicago') selected @endif value="America/Chicago">(GMT-06:00) Central Time (US & Canada)</option>

                                    <option @if($userdetails->data->timezone == 'America/New_York') selected @endif value="America/New_York">(GMT-05:00) Eastern Time (US & Canada)</option>

                                    <option @if($userdetails->data->timezone == 'America/Havana') selected @endif value="America/Havana">(GMT-05:00) Cuba</option>

                                    <option @if($userdetails->data->timezone == 'America/Bogota') selected @endif value="America/Bogota">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>

                                    <option @if($userdetails->data->timezone == 'America/Caracas') selected @endif  value="America/Caracas">(GMT-04:30) Caracas</option>

                                    <option @if($userdetails->data->timezone == 'America/Santiago') selected @endif  value="America/Santiago">(GMT-04:00) Santiago</option>

                                    <option @if($userdetails->data->timezone == 'America/La_Paz') selected @endif  value="America/La_Paz">(GMT-04:00) La Paz</option>

                                    <option @if($userdetails->data->timezone == 'Atlantic/Stanley') selected @endif  value="Atlantic/Stanley">(GMT-04:00) Faukland Islands</option>

                                    <option @if($userdetails->data->timezone == 'America/Campo_Grande') selected @endif  value="America/Campo_Grande">(GMT-04:00) Brazil</option>

                                    <option @if($userdetails->data->timezone == 'America/Goose_Bay') selected @endif  value="America/Goose_Bay">(GMT-04:00) Atlantic Time (Goose Bay)</option>

                                    <option @if($userdetails->data->timezone == 'America/Glace_Bay') selected @endif  value="America/Glace_Bay">(GMT-04:00) Atlantic Time (Canada)</option>

                                    <option @if($userdetails->data->timezone == 'America/St_Johns') selected @endif  value="America/St_Johns">(GMT-03:30) Newfoundland</option>

                                    <option @if($userdetails->data->timezone == 'America/Araguaina') selected @endif  value="America/Araguaina">(GMT-03:00) UTC-3</option>

                                    <option @if($userdetails->data->timezone == 'America/Montevideo') selected @endif  value="America/Montevideo">(GMT-03:00) Montevideo</option>

                                    <option @if($userdetails->data->timezone == 'America/Miquelon') selected @endif  value="America/Miquelon">(GMT-03:00) Miquelon, St. Pierre</option>

                                    <option @if($userdetails->data->timezone == 'America/Godthab') selected @endif  value="America/Godthab">(GMT-03:00) Greenland</option>

                                    <option @if($userdetails->data->timezone == 'America/Argentina/Buenos_Aires') selected @endif  value="America/Argentina/Buenos_Aires">(GMT-03:00) Buenos Aires</option>

                                    <option @if($userdetails->data->timezone == 'America/Sao_Paulo') selected @endif  value="America/Sao_Paulo">(GMT-03:00) Brasilia</option>

                                    <option @if($userdetails->data->timezone == 'America/Noronha') selected @endif  value="America/Noronha">(GMT-02:00) Mid-Atlantic</option>

                                    <option @if($userdetails->data->timezone == 'Atlantic/Cape_Verde') selected @endif  value="Atlantic/Cape_Verde">(GMT-01:00) Cape Verde Is.</option>

                                    <option @if($userdetails->data->timezone == 'Atlantic/Azores') selected @endif  value="Atlantic/Azores">(GMT-01:00) Azores</option>

                                    <option @if($userdetails->data->timezone == 'Europe/Belfast') selected @endif value="Europe/Belfast">(GMT) Greenwich Mean Time : Belfast</option>

                                    <option @if($userdetails->data->timezone == 'Europe/Dublin') selected @endif value="Europe/Dublin">(GMT) Greenwich Mean Time : Dublin</option>

                                    <option @if($userdetails->data->timezone == 'Europe/Lisbon') selected @endif value="Europe/Lisbon">(GMT) Greenwich Mean Time : Lisbon</option>

                                    <option @if($userdetails->data->timezone == 'Europe/London') selected @endif value="Europe/London">(GMT) Greenwich Mean Time : London</option>

                                    <option @if($userdetails->data->timezone == 'Africa/Abidjan') selected @endif value="Africa/Abidjan">(GMT) Monrovia, Reykjavik</option>

                                    <option @if($userdetails->data->timezone == 'Europe/Amsterdam') selected @endif value="Europe/Amsterdam">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>

                                    <option @if($userdetails->data->timezone == 'Europe/Belgrade') selected @endif value="Europe/Belgrade">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>

                                    <option @if($userdetails->data->timezone == 'Europe/Brussels') selected @endif value="Europe/Brussels">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>

                                    <option @if($userdetails->data->timezone == 'Africa/Algiers') selected @endif value="Africa/Algiers">(GMT+01:00) West Central Africa</option>

                                    <option @if($userdetails->data->timezone == 'Africa/Windhoek') selected @endif value="Africa/Windhoek">(GMT+01:00) Windhoek</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Beirut') selected @endif value="Asia/Beirut">(GMT+02:00) Beirut</option>

                                    <option @if($userdetails->data->timezone == 'Africa/Cairo') selected @endif value="Africa/Cairo">(GMT+02:00) Cairo</option>

                                    <option  @if($userdetails->data->timezone == 'Asia/Gaza') selected @endif value="Asia/Gaza">(GMT+02:00) Gaza</option>

                                    <option  @if($userdetails->data->timezone == 'Africa/Blantyre') selected @endif value="Africa/Blantyre">(GMT+02:00) Harare, Pretoria</option>

                                    <option  @if($userdetails->data->timezone == 'Asia/Jerusalem') selected @endif value="Asia/Jerusalem">(GMT+02:00) Jerusalem</option>

                                    <option  @if($userdetails->data->timezone == 'Europe/Minsk') selected @endif value="Europe/Minsk">(GMT+02:00) Minsk</option>

                                    <option  @if($userdetails->data->timezone == 'Asia/Damascus') selected @endif value="Asia/Damascus">(GMT+02:00) Syria</option>

                                    <option  @if($userdetails->data->timezone == 'Europe/Moscow') selected @endif value="Europe/Moscow">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>

                                    <option @if($userdetails->data->timezone == 'Africa/Addis_Ababa') selected @endif value="Africa/Addis_Ababa">(GMT+03:00) Nairobi</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Tehran') selected @endif value="Asia/Tehran">(GMT+03:30) Tehran</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Dubai') selected @endif value="Asia/Dubai">(GMT+04:00) Abu Dhabi, Muscat</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Yerevan') selected @endif value="Asia/Yerevan">(GMT+04:00) Yerevan</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Kabul') selected @endif value="Asia/Kabul">(GMT+04:30) Kabul</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Yekaterinburg') selected @endif value="Asia/Yekaterinburg">(GMT+05:00) Ekaterinburg</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Tashkent') selected @endif value="Asia/Tashkent">(GMT+05:00) Tashkent</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Kolkata') selected @endif value="Asia/Kolkata">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Katmandu') selected @endif value="Asia/Katmandu">(GMT+05:45) Kathmandu</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Dhaka') selected @endif value="Asia/Dhaka">(GMT+06:00) Astana, Dhaka</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Novosibirsk') selected @endif value="Asia/Novosibirsk">(GMT+06:00) Novosibirsk</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Rangoon') selected @endif value="Asia/Rangoon">(GMT+06:30) Yangon (Rangoon)</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Bangkok') selected @endif value="Asia/Bangkok">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Krasnoyarsk') selected @endif value="Asia/Krasnoyarsk">(GMT+07:00) Krasnoyarsk</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Hong_Kong') selected @endif value="Asia/Hong_Kong">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi
                                    </option>

                                    <option @if($userdetails->data->timezone == 'Asia/Irkutsk') selected @endif  value="Asia/Irkutsk">(GMT+08:00) Irkutsk, Ulaan Bataar</option>

                                    <option @if($userdetails->data->timezone == 'Australia/Perth') selected @endif  value="Australia/Perth">(GMT+08:00) Perth</option>

                                    <option @if($userdetails->data->timezone == 'Australia/Eucla') selected @endif  value="Australia/Eucla">(GMT+08:45) Eucla</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Tokyo') selected @endif  value="Asia/Tokyo">(GMT+09:00) Osaka, Sapporo, Tokyo</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Seoul') selected @endif  value="Asia/Seoul">(GMT+09:00) Seoul</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Yakutsk') selected @endif  value="Asia/Yakutsk">(GMT+09:00) Yakutsk</option>

                                    <option @if($userdetails->data->timezone == 'Australia/Adelaide') selected @endif  value="Australia/Adelaide">(GMT+09:30) Adelaide</option>

                                    <option @if($userdetails->data->timezone == 'Australia/Darwin') selected @endif  value="Australia/Darwin">(GMT+09:30) Darwin</option>

                                    <option @if($userdetails->data->timezone == 'Australia/Brisbane') selected @endif  value="Australia/Brisbane">(GMT+10:00) Brisbane</option>

                                    <option @if($userdetails->data->timezone == 'Australia/Hobart') selected @endif  value="Australia/Hobart">(GMT+10:00) Hobart</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Vladivostok') selected @endif  value="Asia/Vladivostok">(GMT+10:00) Vladivostok</option>

                                    <option @if($userdetails->data->timezone == 'Australia/Lord_Howe') selected @endif  value="Australia/Lord_Howe">(GMT+10:30) Lord Howe Island</option>

                                    <option @if($userdetails->data->timezone == 'Etc/GMT-11') selected @endif  value="Etc/GMT-11">(GMT+11:00) Solomon Is., New Caledonia</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Magadan') selected @endif  value="Asia/Magadan">(GMT+11:00) Magadan</option>

                                    <option @if($userdetails->data->timezone == 'Pacific/Norfolk') selected @endif value="Pacific/Norfolk">(GMT+11:30) Norfolk Island</option>

                                    <option @if($userdetails->data->timezone == 'Asia/Anadyr') selected @endif value="Asia/Anadyr">(GMT+12:00) Anadyr, Kamchatka</option>

                                    <option @if($userdetails->data->timezone == 'Pacific/Auckland') selected @endif value="Pacific/Auckland">(GMT+12:00) Auckland, Wellington</option>

                                    <option @if($userdetails->data->timezone == 'Etc/GMT-12') selected @endif value="Etc/GMT-12">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>

                                    <option @if($userdetails->data->timezone == 'Pacific/Chatham') selected @endif value="Pacific/Chatham">(GMT+12:45) Chatham Islands</option>

                                    <option @if($userdetails->data->timezone == 'Pacific/Tongatapu') selected @endif value="Pacific/Tongatapu">(GMT+13:00) Nuku'alofa</option>

                                    <option @if($userdetails->data->timezone == 'Pacific/Kiritimati') selected @endif value="Pacific/Kiritimati">(GMT+14:00) Kiritimati</option>

                                   </select>
                                </div>


                            </div>







                            <div class="card-footer">
                                <button type="submit" class="btn btn-info">Update</button>
                            </div>
                        </form>
                    </div><!-- /.tab-pane -->

                    <div class="tab-pane" id="settings">
                        <form class="form-horizontal form-label-left" method="post"   >
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="user_id" value="{{$userdetails->data->id}}">


                            <input type="hidden" name="tab" value="active">


                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="inputPassword3" class="col-form-label">Old Password</label>

                                    <input type="password" required class="form-control" name="old_password" id="inputEmail" autocomplete="off" placeholder="Enter Old Password">
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="inputPassword3" class="col-form-label">Password</label>

                                    <input type="password" required class="form-control" name="password" id="inputEmail" autocomplete="off" placeholder="Enter Password">
                                </div>

                            </div>

                            <div class="form-group row">


                                <div class="col-sm-6">
                                    <label for="inputPassword3" class="col-form-label">Confirm Password</label>

                                    <input type="Password" required class="form-control" name="password_confirmation" id="inputName" placeholder="Confirm Password">
                                </div>
                            </div>


                            <div class="card-footer">
                                <button type="submit" class="btn btn-info">Update</button>
                            </div>
                        </form>
                    </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
            </div><!-- /.nav-tabs-custom -->
        </div><!-- /.col -->
</div><!-- /.row -->

</section><!-- /.content -->
</div><!-- /.content-wrapper -->


<div class="modal fade" id="edit" role="dialog">

    <!-- Modal content-->

    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #d33724 !important;color:white;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="{{url('/ivr')}}" enctype="multipart/form-data" class="edit_ivr_form">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-ok deleteVoiceMail">Delete</button>

            </div>
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
                <p>You are about to delete <b><i class="title"></i></b> voicemail drop record.</p>
                <p>Do you want to proceed?</p>
                <input type="hidden" class="form-control" name="auto_id" value="" id="auto_id">
                <input type="hidden" class="form-control" name="voicemail_id" value="" id="voicemail_id">


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-ok deleteVoiceMail">Delete</button>

            </div>
        </div>
    </div>

</div>
<script>
    function showPassword()
    {
        var x = document.getElementById("secret");
        if (x.type === "password")
        {
            x.type = "text";
        }
        else
        {
            x.type = "password";
        }
    }
</script>

<script>
    document.getElementById("html_btn").onchange = function () {
        document.getElementById("form").submit();
    }

    document.getElementById("voicemail").onchange = function () {
        document.getElementById("form_voiemail").submit();
    }
</script>

<script>
    $(document).on("click", ".deleteVoiceMail", function () {
        var auto_id = $("#auto_id").val();
        var voicemail_id = $("#voicemail_id").val();

        var el = this;
        $.ajax({
            url: 'deleteVoiceMail/' + auto_id+'/'+voicemail_id,
            type: 'get',
            success: function (response) {
                window.location.reload(1);
            }
        });
    });
</script>



<script>

      $(".voicemailEdit").click(function () {
        var voicemailid = $(this).data('voicemailid');

        $("#edit").modal();

         $.ajax({
            url: 'editVoiceMail/'+voicemailid,
            type: 'get',
            success: function (response) {



                $("#ivr_desc").val(response[0].ivr_desc);
                //window.location.reload(1);
            }
        });


        $("#auto_id").val(auto_id);
        $("#voicemail_id").val(voicemailid);


    });

    $(".voicemailDelete").click(function () {
        var auto_id = $(this).data('id');
        var voicemailid = $(this).data('voicemailid');

        $("#delete").modal();
        $("#auto_id").val(auto_id);
        $("#voicemail_id").val(voicemailid);


    });


    $('.icon').on("click", function () {
        $('#html_btn').click();
    });

    $(document).ready(function () {




        var phonehtml = $('.phonehtml').html().split("-").join(""); // remove hyphens

        // alert(phonehtml);
        phonehtml = phonehtml.match(new RegExp('.{1,4}$|.{1,3}', 'g')).join("-");
        $('.phonehtml').html(phonehtml);


        var phone_format = $('.phone').val().split("-").join(""); // remove hyphens
        phone_format = phone_format.match(new RegExp('.{1,4}$|.{1,3}', 'g')).join("-");
        $('.phone').val(phone_format);
        /* $('.phone').keyup(function() {
         var phone_format = $(this).val().split("-").join(""); // remove hyphens
         phone_format = phone_format.match(new RegExp('.{1,4}$|.{1,3}', 'g')).join("-");
         $(this).val(phone_format);
         });*/

        /*$('.icon').bind("click" , function () {
         $('#html_btn').click();
         });
         });*/


    });

</script>

<style>

    #html_btn {
        display:none;
    }
    .container {
        position: relative;
        width: 100%;
        max-width: 400px;
    }

    .image {
        display: block;
        width: 100%;
        height: auto;
    }

    .overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100%;
        width: 100%;
        opacity: 0;
        transition: .3s ease;
        background-color: red;
    }

    .container:hover .overlay {
        opacity: 1;
    }

    .icon {
        color: black;
        font-size: 100px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        text-align: center;
        cursor: pointer;
    }

    .fa-camera:hover {
        color: #black;
    }
</style>



@endsection