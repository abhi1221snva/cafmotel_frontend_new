@extends('layouts.app')
@section('content')
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 22px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider_button {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider_button:before {
  position: absolute;
  content: "";
  height: 15px;
  width: 15px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider_button {
  background-color: #00c0ef;
}

input:focus + .slider_button {
  box-shadow: 0 0 1px #00c0ef;
}

input:checked + .slider_button:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider_button.round {
  border-radius: 34px;
}

.slider_button.round:before {
  border-radius: 50%;
}


</style>

<style type="text/css">
.dialog-background{
    background: none repeat scroll 0 0 rgba(244, 244, 244, 0.5);
    height: 100%;
    left: 0;
    margin: 0;
    padding: 0;
    position: absolute;
    top: 0;
    width: 100%;
    z-index: 100;
}

.dialog-loading-wrapper {
    background: none repeat scroll 0 0 rgba(244, 244, 244, 0.5);
    border: 0 none;
    height: 100px;
    left: 50%;
    margin-left: -50px;
    margin-top: -50px;
    position: fixed;
    top: 50%;
    width: 100px;
    z-index: 9999999;
}
</style>

   
<div class="dialog-background" id="loading" style="display: none;">
    <div class="dialog-loading-wrapper">
        <img src="{{ asset('asset/img/lp.gif') }}">
    </div>
</div>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <b>Edit Campaign</b>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Campaign</li>
            <li class="active">Edit Campaign</li>
        </ol>
    </section>

    <section class="content-header">
        <div class="text-right mt-5 mb-3"> 
            <a href="{{ url('/campaign') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show Campaign</a>
        </div>
    </section>


    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <form method="post" action="">
                            @csrf
                            <div class="modal-body">
                                <div class="row col-md-12">
                                    
                                    <div class="form-group col-md-3">
                                        <input type="hidden" class="form-control" name="campaign_id" value="{{$campaign_list[0]->id}}" id="campaign_name" required="">
                                        <label>Name <i data-toggle="tooltip" data-placement="right" title="Type campaign name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <input type="text" class="form-control" name="title" value="{{$campaign_list[0]->title}}" id="campaign_name" required="">
                                        </div>
                                    </div>

                                     <div class="form-group col-md-3">
                                        <label>Caller Id <i data-toggle="tooltip" data-placement="right" title="Select Caller Id" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select name="caller_id" class="form-control" id="caller_id" required="">
                                                <option @if($campaign_list[0]->caller_id == 'custom') selected @endif value="custom">Custom</option>
                                                <option @if($campaign_list[0]->caller_id == 'area_code') selected @endif value="area_code">Area Code</option>
                                                <option @if($campaign_list[0]->caller_id == 'area_code_random') selected @endif value="area_code_random">AreaCode And Randomizer</option>

                                                <option @if($campaign_list[0]->caller_id == 'area_code_3') selected @endif value="area_code_3">Area Code + 3</option>

                                                <option @if($campaign_list[0]->caller_id == 'area_code_4') selected @endif value="area_code_4">Area Code + 4</option>

                                                <option @if($campaign_list[0]->caller_id == 'area_code_5') selected @endif value="area_code_5">Area Code + 5</option>


                                                
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Custom Caller Id <i data-toggle="tooltip" data-placement="right" title="Select Phone Number from the list" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <select required class="form-control" name="custom_caller_id" id="custom-caller-id" @if($campaign_list[0]->caller_id !='custom') disabled @endif>

                                            @if(count($did_list) > 0)
                                            @foreach(array_reverse($did_list) as $key => $lists)
                                            <option  data-cnam="{{$lists->cnam}}" @if($campaign_list[0]->custom_caller_id == $lists->cli) selected @endif value="<?php echo $lists->cli ?>"><?php echo $lists->cli ?> <?php if(!empty($lists->cnam)) echo '-'.$lists->cnam ?>
                                            @if($lists->dest_type>0)
                                    -{{ !empty($destTypeList[$lists->dest_type]) ? $destTypeList[$lists->dest_type] : ''  }}
                                @else
                                    @if($lists->cnam != null)
                                        -IVR
                                    @else
                                        
                                    @endif
                                @endif
                                @if($lists->dest_type > 0)
                                    @if($lists->dest_type == 1)
                                        @foreach($extension_list as $extension)
                                            @if($lists->extension == $extension->id)
                                                -{{$extension->first_name}} {{$extension->last_name}} - {{$extension->extension}} 
                                            @endif
                                        @endforeach
                                    @elseif($lists->dest_type == 2)
                                        @foreach($extension_list as $extension)
                                            @if($lists->voicemail_id == $extension->id)
                                                -{{$extension->first_name}} {{$extension->last_name}} - {{$extension->extension}} 
                                            @endif
                                        @endforeach
                                    @elseif($lists->dest_type == 8)
                                        @foreach($ring_group_list as $ring)
                                            @if($lists->ingroup == $ring->id)
                                                -{{$ring->description}} - {{$ring->title}}
                                            @endif
                                        @endforeach
                                    @elseif($lists->dest_type == 5)
                                        @foreach($conferencing as $conf)
                                            @if($lists->conf_id == $conf->id)
                                                -{{$conf->title}} - {{$conf->conference_id}}
                                            @endif
                                        @endforeach
                                    @elseif($lists->dest_type == 4)
                                        {{$lists->forward_number}}

                                        @elseif($lists->dest_type == 10)
                                        Run CNAM

                                         @elseif($lists->dest_type == 11)
                                        Voice AI
                                    @else
                                        -{{$destTypeList[$lists->dest_type]}}
                                    @endif
                                @else
                                    @foreach($ivr_list as $ivr)
                                        @if($lists->ivr_id == $ivr->ivr_id)
                                            -{{$ivr->ivr_desc}} 
                                        @endif
                                    @endforeach
                                @endif
                                            </option>
                                        @endforeach
                                    @endif
                            </select>
                        </div>

                                    <div class="form-group col-md-2">
                                        <label>Dialing Mode <i data-toggle="tooltip" data-placement="right" title="Select dialing mode" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select name="dial_mode" class="form-control" id="dial-mode" required="">
                                                 @if(!empty($campaign_type_list))
                                    @foreach($campaign_type_list as $type)
                                     <option @if($campaign_list[0]->dial_mode == $type->title_url) selected @endif value="{{$type->title_url}}">{{$type->title}}</option>
                                    @endforeach
                                    @endif
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group col-md-2" style="display: none;" id="call_ratio_div">
                            <label id="label_id">Call Ratio </label> <i data-toggle="tooltip" data-placement="right" title="Select call ratio" class="fa fa-info-circle" aria-hidden="true"></i>
                            <div class="input-daterange input-group col-md-12">
                                <select name="call_ratio" class="form-control" id="call_ratio">
                                    
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-2" style="display: none;" id="duration_div">
                            <label id="label_id_duration">Duration <i data-toggle="tooltip" data-placement="right" title="Select duration for run predictive call" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="duration" class="form-control" id="duration">
                                   




                                </select>
                            </div>
                        </div>

                                     <div class="form-group col-md-2" style="display: none;" id="redirect_to_div">
                            <label>Redirect To <i data-toggle="tooltip" data-placement="right" title="Select redirect to" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="redirect_to" class="form-control" id="redirect_to">

                                    <option value="">Select Redirect To</option>
                                    <option @if($campaign_list[0]->redirect_to == '1') selected @endif value="1">Audio Message</option>
                                    <option @if($campaign_list[0]->redirect_to == '2') selected @endif value="2">Voice Template</option>

                                    <option @if($campaign_list[0]->redirect_to == '3') selected @endif value="3">Extension</option>
                                    <option @if($campaign_list[0]->redirect_to == '4') selected @endif value="4">Ring Group</option>
                                    <option @if($campaign_list[0]->redirect_to == '5') selected @endif value="5">IVR</option>

                                </select>
                            </div>
                        </div>


                          <div class="form-group col-md-3" style="display: none;" id="audio_message">
                            <label>Audio Message <i data-toggle="tooltip" data-placement="right" title="VoiceDrop Option" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="outbound_ai_dropdown_audio_message" class="form-control" id="">
                                     @if (isset($audio_message))
                                                @foreach($audio_message as $audio_lst)
                                                <option  
                                                    value={{$audio_lst->ivr_id}}>{{$audio_lst->ivr_desc}} - {{$audio_lst->ivr_id}}
                                                </option>
                                                @endforeach
                                                @endif

                                    
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-3" style="display: none;" id="voice_message">
                            <label>Voice Template <i data-toggle="tooltip" data-placement="right" title="VoiceDrop Option" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="outbound_ai_dropdown_voice_message" class="form-control" id="">
                                     @if (isset($voice_templete_list))
                                                @foreach($voice_templete_list as $voice_lst)
                                                <option  
                                                    value={{$voice_lst->templete_id}}>{{$voice_lst->templete_name}}
                                                </option>
                                                @endforeach
                                                @endif

                                    
                                </select>
                            </div>
                        </div>



                         <div class="form-group col-md-3" style="display: none;" id="extension">
                            <label>Extension <i data-toggle="tooltip" data-placement="right" title="VoiceDrop Option" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="outbound_ai_dropdown_extension" class="form-control" id="">
                                    @foreach($extension_list as $extension)
                                            @if($extension->id)
                                                <option @if($campaign_list[0]->redirect_to_dropdown == $extension->id) selected @endif  value="{{$extension->id}}">{{$extension->first_name}} {{$extension->last_name}} - {{$extension->extension}} </option>
                                            @endif
                                        @endforeach

                                    
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-3" style="display: none;" id="ivr">
                            <label>IVR <i data-toggle="tooltip" data-placement="right" title="VoiceDrop Option" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="outbound_ai_dropdown_ivr" class="form-control" id="voicedrop_option_user_id">

                                    @if (isset($ivr_list))
                                                @foreach($ivr_list as $ivr_lst)
                                                <option @if($campaign_list[0]->redirect_to_dropdown == $ivr_lst->ivr_id) selected @endif 
                                                    value={{$ivr_lst->ivr_id}}>{{$ivr_lst->ivr_desc}} - {{$ivr_lst->ivr_id}}
                                                </option>
                                                @endforeach
                                                @endif

                                    
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-3" style="display: none;" id="ring_group">
                            <label>Ring Group <i data-toggle="tooltip" data-placement="right" title="VoiceDrop Option" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="outbound_ai_dropdown_ring_group" class="form-control" id="voicedrop_option_user_id">

                                    @if (isset($ring_group_list))
                                                @foreach($ring_group_list as $rgroup_lst)
                                                <option @if($campaign_list[0]->redirect_to_dropdown == $rgroup_lst->id) selected @endif  value={{$rgroup_lst->id}}>{{$rgroup_lst->description}} - {{$rgroup_lst->title}}
                                                </option>
                                                @endforeach
                                                @endif
                                    
                                </select>
                            </div>
                        </div>



                        <div class="form-group col-md-2" style="display: none;" id="automated_duration">
                            <label>Automated Duration <i data-toggle="tooltip" data-placement="right" title="Select yes for run duration auto predictive call" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="automated_duration" class="form-control" id="autoduration">
                                    <option @if($campaign_list[0]->automated_duration == 0) selected @endif value="0">No</option>
                                    <option @if($campaign_list[0]->automated_duration == 1) selected @endif value="1">Yes</option>
                                </select>
                            </div>
                        </div>

                         <div class="form-group col-md-2" style="display: none;" id="amd_call">
                            <label>AMD <i data-toggle="tooltip" data-placement="right" title="Select On for run AMD predictive call" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="amd" class="form-control" id="amd">
                                    <option @if($campaign_list[0]->amd == 0) selected @endif value="0">Off</option>
                                    <option @if($campaign_list[0]->amd == 1) selected @endif value="1">On</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-2" @if($campaign_list[0]->amd == 0) style="display: none;" @endif id="amd_drop">
                            <label>AMD Drop Action <i data-toggle="tooltip" data-placement="right" title="AMD Drop Action" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="amd_drop_action" class="form-control" id="amd_drop_action">
                                    <option @if($campaign_list[0]->amd_drop_action == 1) selected @endif value="1">HangUp</option>
                                    <option @if($campaign_list[0]->amd_drop_action == 2) selected @endif value="2">Audio Message</option>
                                    <option @if($campaign_list[0]->amd_drop_action == 3) selected @endif value="3">Voice Template</option>

                                </select>

                              
                            </div>
                        </div>


                         <div class="form-group col-md-3"@if($campaign_list[0]->amd == 0 || $campaign_list[0]->amd_drop_action == 2) style="display: none;" @endif id="audio_message_amd">
                            <label>Audio Message AMD <i data-toggle="tooltip" data-placement="right" title="VoiceDrop Option" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="audio_message_amd" class="form-control" id="">
                                     @if (isset($audio_message))
                                                @foreach($audio_message as $audio_lst)
                                                <option  
                                                    value={{$audio_lst->id}}>{{$audio_lst->ivr_desc}} - {{$audio_lst->ivr_id}}
                                                </option>
                                                @endforeach
                                                @endif

                                    
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-3" @if($campaign_list[0]->amd == 0 || $campaign_list[0]->amd_drop_action == 3) style="display: none;" @endif id="VoiceDrop">
                            <label>Voice Template AMD <i data-toggle="tooltip" data-placement="right" title="VoiceDrop Option" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                 <select name="voice_message_amd" class="form-control" id="">
                                     @if (isset($voice_templete_list))
                                                @foreach($voice_templete_list as $voice_lst)
                                                <option  
                                                    value={{$voice_lst->templete_id}}> {{$voice_lst->templete_name}}
                                                </option>
                                                @endforeach
                                                @endif

                                    
                                </select>
                            </div>
                        </div>


                        <div class="form-group col-md-2" style="display: none;" id="no_agent_available_action">
                            <label>No Agent available Action <i data-toggle="tooltip" data-placement="right" title="Select action from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="no_agent_available_action" class="form-control" id="no_agent_available">
                                    <option @if($campaign_list[0]->no_agent_available_action == 1) selected @endif value="1">Hang Up</option>
                                    <option @if($campaign_list[0]->no_agent_available_action == 2) selected @endif value="2">Voice Drop</option>
                                    <option @if($campaign_list[0]->no_agent_available_action == 3) selected @endif value="3 ">Inbound IVR</option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-3" style="display: none;" id="VoiceDropAction">
                            <label>Voice Drop Option <i data-toggle="tooltip" data-placement="right" title="VoiceDrop Option" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="voicedrop_no_agent_available_action" class="form-control" id="">
                                    @foreach($extension_list as $extension)
                                            @if($extension->id)
                                                <option @if($campaign_list[0]->no_agent_dropdown_action == $extension->id) selected @endif  value="{{$extension->id}}">{{$extension->first_name}} {{$extension->last_name}} - {{$extension->extension}} </option>
                                            @endif
                                        @endforeach

                                    
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-3" style="display: none;" id="IvrAction">
                            <label>Inbound IVR Option <i data-toggle="tooltip" data-placement="right" title="VoiceDrop Option" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="inbound_ivr_no_agent_available_action" class="form-control" id="voicedrop_option_user_id">

                                    @if (isset($ivr_list))
                                                @foreach($ivr_list as $ivr_lst)
                                                <option @if($campaign_list[0]->no_agent_dropdown_action == $ivr_lst->ivr_id) selected @endif 
                                                    value={{$ivr_lst->ivr_id}}>{{$ivr_lst->ivr_desc}} - {{$ivr_lst->ivr_id}}
                                                </option>
                                                @endforeach
                                                @endif

                                    
                                </select>
                            </div>
                        </div>




                                    <div class="form-group col-md-2">
                                        <label>Status <i data-toggle="tooltip" data-placement="right" title="Select status for active/inactive" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select name="status" class="form-control" id="campaign_status">
                                                <option @if($campaign_list[0]->status == 1) selected @endif  value="1">Active</option>
                                                <option @if($campaign_list[0]->status == 0) selected @endif  value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-2">
                            <label>Caller Group <i data-toggle="tooltip" data-placement="right" title="Select the group of extension you want to assign campaign to" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="group_id" class="form-control" id="group_id" required="">
                                     @foreach($group as $key => $ext_group)
                                        <option @if($campaign_list[0]->group_id == $ext_group->id) selected @endif value="{{$ext_group->id}}">{{$ext_group->title}}</option>

                                    @endforeach

                                                                    </select>
                            </div>
                        </div>

                         <div class="form-group col-md-2">
                        <label>Country Code <i data-toggle="tooltip" data-placement="right" title="Select Country Code" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-daterange input-group col-md-12">
                            <select class="form-control"  name="country_code" id="country_code">
                                                @if (is_array($phone_country))
                                                @foreach($phone_country as $code)
                                                <option @if($campaign_list[0]->country_code == $code->phonecode ) selected @endif value={{$code->phonecode}}>{{$code->name}} (+{{$code->phonecode}})
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                        </div>
                    </div>

                        <div class="form-group col-md-2">
                            <label>Time Based Calling <i data-toggle="tooltip" data-placement="right" title="Select yes/no for time based call system" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="time_based_calling" class="form-control" id="time_based_calling" required="">
                                    <option @if($campaign_list[0]->time_based_calling == 1) selected @endif value="1">Yes</option>
                                    <option @if($campaign_list[0]->time_based_calling == 0) selected @endif value="0">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-2">
                            <label>Send to crm <i data-toggle="tooltip" data-placement="right" title="CRM Integration will be enabled if you enable this feature" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="send_crm" class="form-control" id="send_crm" >
                                    <option @if($campaign_list[0]->send_crm == 0) selected @endif value="0">No</option>
                                    <option @if($campaign_list[0]->send_crm == 1) selected @endif value="1">Yes</option>
                                </select>
                            </div>
                        </div>

                                   

                        <div class="form-group col-md-2">
                            <label>Send Email <i data-toggle="tooltip" data-placement="right" title="Email Integration will be enabled if you enable this feature" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                <div class="input-daterange input-group col-md-12">
                                    <select name="email" class="form-control" id="email" >
                                        <option @if($campaign_list[0]->email == 0) selected @endif value="0">No</option>
                                        <option @if($campaign_list[0]->email == 1) selected @endif value="1">With User Email</option>
                                        <option @if($campaign_list[0]->email == 2) selected @endif value="2">With Campaign Email</option>
                                        <option @if($campaign_list[0]->email == 3) selected @endif value="3">With System Email</option>
                                    </select>
                                </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Call Time <i data-toggle="tooltip" data-placement="right" title="Choose call time to be used by the campaign" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div>
                                <div class="input-group">
                                    <input type="time" class="form-control" value="{{$campaign_list[0]->call_time_start}}" name="call_time_start" id="timepicker">
                                    <span class="input-group-addon bg-primary text-white b-0">to</span>
                                    <input type="time" class="form-control" value="{{$campaign_list[0]->call_time_end}}" name="call_time_end" id="timepicker3">
                                </div>
                            </div>
                        </div>

                        

                        


                        <div class="form-group col-md-2">
                        <label>Send Sms <i data-toggle="tooltip" data-placement="right" title="Text Integration will be enabled if you enable this feature" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-daterange input-group col-md-12">
                            <select name="sms" class="form-control" id="sms" >
                            <option @if($campaign_list[0]->sms == 0) selected @endif value="0">No</option>
                            <option @if($campaign_list[0]->sms == 1) selected @endif value="1">With User Phone No</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-2">
                        <label>Send Report <i data-toggle="tooltip" data-placement="right" title="Send reports yes/no" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-daterange input-group col-md-12">
                            <select name="send_report" class="form-control" id="campaign_send_report">
                                <option @if($campaign_list[0]->send_report == 1) selected @endif value="1">Yes</option>
                                <option @if($campaign_list[0]->send_report == 0) selected @endif value="0">No</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group col-md-6">
                        <label>Hopper Mode Type <i data-toggle="tooltip" data-placement="right" title="Select Hopper mode type" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-daterange input-group col-md-12">
                            <select name="hopper_mode" class="form-control" id="hopper_mode">
                                <option @if($campaign_list[0]->hopper_mode == 1) selected @endif value="1">Linear</option>
                                <option @if($campaign_list[0]->hopper_mode == 2) selected @endif value="2">Random</option>
                            </select>
                        </div>
                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Description <i data-toggle="tooltip" data-placement="right" title="Type description for campaign" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <textarea type="textarea" class="form-control" name="description" value="" id="campaign_description">{{$campaign_list[0]->description}}</textarea>
                                        </div>
                                    </div>

                                </div>
                    
                        


                          

 @if(!empty($voip_configurations))
                    <div class="form-group col-md-6">
                        <label>Outbound Line <i data-toggle="tooltip" data-placement="right" title="Select Voip Configuration " class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-daterange input-group col-md-12">
                        <select class="form-control" required name="voip_configurations" autocomplete="off" data-placeholder="Select Disposition" >
                            <option value="">Select VOIP</option>
                            @foreach($voip_configurations as $key => $voip)
                            <option @if($campaign_list[0]->voip_configuration_id == $voip->id) selected @endif value="{{$voip->id}}">{{$voip->name}}</option>
                            @endforeach;
                        </select>
                        </div>
                    </div>
                    @endif

                    <div class="form-group col-md-6">
                        <label>Disposition <i data-toggle="tooltip" data-placement="right" title="Select multiple disposition from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-daterange input-group col-md-12">
                        <select name="edit_disposition[]" class="select2" multiple="multiple" id="disposition-multiple-selected" required="" data-placeholder="Select Disposition" style="width: 100%;">

                            @foreach($disposition_list as $key => $dlist)
                            <option @if(in_array($dlist->id, $userDisposition))  selected  @endif value="{{$dlist->id}}">{{$dlist->title}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>




                   

                   

                    

                    
                <div class="row lead_status">
                </div>
                <div class="modal-footer">

                                    <button type="submit" name="submit" value="add" class="btn btn btn-primary waves-effect waves-light"><i class="fa fa-check-square-o fa-lg"></i> Update</button>
                                     <a onclick="window.location.reload();" type="button" class="btn btn btn-warning waves-effect waves-light" data-dismiss="modal"><i class="fa fa-refresh fa-lg"></i> Reset</a>
                    <a href="/campaign" type="button" class="btn btn btn-danger waves-effect waves-light" data-dismiss="modal"><i class="fa fa-reply fa-lg"></i> Cancel</a>
                                </div>
            </form>

            
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->


    <section class="content">
        <div class="row">

           
            <div class="col-xs-12">
                <div class="box">

                    <div class="box-body">

                        <table id="example" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>

                                    <th>Campaign Name</th>
                                    <th>List Name</th>
                                    <th>Dialled Leads/Total Leads</th>
                                    <th>Status</th>
                                    <th>Action</th>

                                    <!--<th>Action</th> -->

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($no_of_campaign_list as $key => $campaignlst)
                                <tr>

                                    <td>{{ $key+1 }}</td>
                                    <td>{{$campaignlst->title}}</td>
                                    <td>{{$campaignlst->l_title}}</td>
                                    <td>{{$campaignlst->rowLeadReport}} / {{$campaignlst->rowListData}}</td>

                                    <td>
                                        @if($campaignlst->status == '0')
                                        <span class="label label-danger">Inactive</span>
                                            
                                        @else ($campaignlst->status == '1')
                                        <span class="label label-success">Active</span>
                                        @endif

                                        {{--<a style="cursor:pointer;color:blue;" class='updateList' href="/updateCampaignList/{{$campaignlst->campaign_id}}/{{$campaignlst->list_id}}/0/5"  ><span class="label label-success">Active</span></a>--}}
                                    </td>

                                    <td>
                                        @if($campaignlst->crm_title_url == 'hubspot')
                                        @else
                                        <a style="cursor:pointer;color:red;"  
                                    class='openCampaignDelete' data-camid={{$campaignlst->campaign_id}} data-id={{$campaignlst->list_id}}  ><i class="fa fa-trash fa-lg"></i></a> | <a style="cursor:pointer;color:blue;"  class='openrecycle'  data-camid={{$campaignlst->campaign_id}} data-id={{$campaignlst->list_id}}><i class="fa fa-recycle fa-lg"></i></a> | <label class="switch"><input data-campaignid="{{$campaignlst->campaign_id}}"  data-listid="{{$campaignlst->list_id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $campaignlst->status ? 'checked' : '' }}><span class="slider_button round"></span>
                                        @endif
</label> </td>
                                    

                                </tr>
                                @endforeach
                        </table>

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

            </div>
            <!-- /.col -->


            <!---- code for recycle  -->

<div id="recycleListModal" class="modal fade" role="dialog">
    <div class="modal-dialog" >

        <!-- Modal content-->
        <div class="modal-content" style="background-color: black !important;color:white;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Recycle List</h4>
            </div>
            <form method="post" action="/campaign/list/{{$campaign_list[0]->id}}" >
                @csrf
                <div class="modal-body">
                    <div class="col-md-12 p-b-10" style="font-weight: bold"><div class="col-md-1"></div><div class="col-md-6">Disposition</div><div class="col-md-2">Records</div><div class="col-md-3">Call times</div></div>
                    <div id="disposition" style="display: inline-block">

                    </div>
                </div>
                <div class="modal-footer">
                        <input type="hidden" name="param[id]" value="" id="recycle_list_id"/>
                        <input type="hidden" name="param[cid]" value="" id="recycle_campaign_id"/>

                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" name ="submit" value="recycle" class="btn btn-danger btn-ok deleteCampaignList">Recycle</button>

                                       </div>
            </form>
        </div>

    </div>
</div>

            <!-- end code -->

           
            <div class="modal fade" id="delete" role="dialog">

              

        <div class="modal-dialog">
            <div class="modal-content" style="background-color: #d33724 !important;color:white;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                </div>
                <div class="modal-body">
                    <p>You are about to delete <b><i class="title"></i></b> List.</p>
                    <p>Do you want to proceed?</p>
                      <input type="hidden" class="form-control" name="list_id" value="" id ="list_id" >
                      <input type="hidden" class="form-control" name="camid" value="" id ="camid" >



                          
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger btn-ok deleteCampaignList">Delete</button>
                </div>
            </div>
        </div>

            </div>
        </div>
        <!-- /.row -->
    </section>
</div>
<!-- /.content-wrapper -->
<br>
<br>




<script>

    var amd_drop_action = $("#amd_drop_action").val();
  //  alert(amd_drop_action);
    if(amd_drop_action == 2)
    {
           $("#audio_message_amd").show();
           $("#VoiceDrop").hide();
    }
    else
    if(amd_drop_action == 3)
    {
           $("#audio_message_amd").hide();
           $("#VoiceDrop").show();
    }


    $(document).on("change", "#redirect_to", function ()
    {
        var redirect_to = $("#redirect_to").val();
        if(redirect_to == '3')
        {
            $("#audio_message").hide();
            $("#extension").show();
            $("#ivr").hide();
            $("#ring_group").hide();
            $("#voice_message").hide();
        }

        else
        if(redirect_to == '5')
        {
            $("#ivr").show();
            $("#extension").hide();
            $("#audio_message").hide();
            $("#ring_group").hide();
            $("#audio_message").hide();
            $("#voice_message").hide();
        }

        else
        if(redirect_to == '4')
        {
            $("#ivr").hide();
            $("#extension").hide();
            $("#audio_message").hide();
            $("#ring_group").show();
            $("#audio_message").hide();
            $("#voice_message").hide();
        }
        else
        if(redirect_to == '1')
        {
            $("#ivr").hide();
            $("#extension").hide();
            $("#audio_message").hide();
            $("#ring_group").hide();
            $("#audio_message").show();
            $("#voice_message").hide();
        }

        else
        if(redirect_to == '2')
        {
            $("#ivr").hide();
            $("#extension").hide();
            $("#audio_message").hide();
            $("#voice_message").show();

            $("#ring_group").hide();
            $("#audio_message").hide();
        }
        else
        {
            $("#VoiceDropAction").hide();
            $("#IvrAction").hide();
            $("#ivr").hide();
            $("#extension").hide();
            $("#audio_message").hide();
            $("#ring_group").hide();
            $("#audio_message").hide();
            $("#call_ratio_div").hide();
            $("#voice_message").hide();
        }
    });


      @if($campaign_list[0]->redirect_to == '5')

      $("#ivr").show();

      @endif

       @if($campaign_list[0]->redirect_to == '3')

      $("#extension").show();

      @endif

       @if($campaign_list[0]->redirect_to == '4')

      $("#ring_group").show();

      @endif


       @if($campaign_list[0]->redirect_to == '1')

      $("#audio_message").show();

      @endif

       @if($campaign_list[0]->redirect_to == '2')

      $("#voice_message").show();

      @endif



      @if($campaign_list[0]->dial_mode == 'outbound_ai')
        $("#call_ratio_div").show();

            $("#label_id").text('Simultaneous Calls');


       var html='';

            for(i=1;i<31;i++)
{

            html +='<option value="'+i+'">'+i+'</option>';
}

            $("#call_ratio").html(html);

            $("#call_ratio").val(<?php echo $campaign_list[0]->call_ratio; ?>);



         $("#duration_div").show();


            $("#label_id_duration").text('Duration In Min ');


            var duration = '';
            
             duration+='<option value="0.5">30 Sec</option><option value="1">1 Min</option><option value="2">2 Min</option><option value="5">5 Min</option><option value="10">10 Min</option><option value="20">20 Min</option><option value="30">30 Min</option>';

           

            $("#duration").html(duration);
            $("#duration").val(<?php echo $campaign_list[0]->duration; ?>);

        $("#automated_duration").hide();
        $("#amd_call").show();
        $("#no_agent_available_action").hide();

        var redirect_to = $("#redirect_to").val();
        if(redirect_to == 'extension')
        {
            $("#extension").show();
           // $("#IvrAction").hide();

        }

        else
            if(no_agent_available == 3)
        {
            $("#IvrAction").show();
            $("#VoiceDropAction").hide();

        }
        else
        {
            $("#VoiceDropAction").hide();
            $("#IvrAction").hide();

        }

            $("#redirect_to_div").show();






    @endif

    @if($campaign_list[0]->dial_mode == 'predictive_dial')
        $("#call_ratio_div").show();

            $("#label_id").text('Call Ratio');


         var html='';

            html +=' <option value="1">1</option><option value="1.5">1.5</option><option value="2">2</option><option value="2.5">2.5</option><option value="3">3</option><option value="3.5">3.5</option><option value="4">4</option><option value="4.5">4.5</option><option value="5">5</option>';

            $("#call_ratio").html(html);

            $("#call_ratio").val(<?php echo $campaign_list[0]->call_ratio; ?>);
        $("#duration_div").show();

            $("#label_id_duration").text('Duration In Sec ');


            var duration='';
            for(i=0;i<16;i++)
            {
             duration+='<option value="'+i+'">'+i+'</option>';

            }

            $("#duration").html(duration);
            $("#duration").val(<?php echo $campaign_list[0]->duration; ?>);

        $("#automated_duration").show();
        $("#amd_call").show();
        $("#no_agent_available_action").show();

        var no_agent_available = $("#no_agent_available").val();
        if(no_agent_available == 2)
        {
            $("#VoiceDropAction").show();
            $("#IvrAction").hide();

        }

        else
            if(no_agent_available == 3)
        {
            $("#IvrAction").show();
            $("#VoiceDropAction").hide();

        }
        else
        {
            $("#VoiceDropAction").hide();
            $("#IvrAction").hide();

        }

            $("#redirect_to_div").hide();




    @endif


    $(document).on("change", "#amd", function ()
    {
        var amd = $("#amd").val();
        if(amd == 0)
        {
            $("#amd_drop").hide();
            $("#VoiceDrop").hide();

        }
        else
        {
            $("#amd_drop").show();
            var amd_drop_action = $("#amd_drop_action").val();
        if(amd_drop_action == 2)
        {
            $("#VoiceDrop").show();
        }
        else
        {
            $("#VoiceDrop").hide();
        }
           
        }

    });


     $(document).on("change", "#no_agent_available", function ()
    {
        var no_agent_available = $("#no_agent_available").val();
        if(no_agent_available == 2)
        {
            $("#VoiceDropAction").show();
            $("#IvrAction").hide();

        }

        else
            if(no_agent_available == 3)
        {
            $("#IvrAction").show();
            $("#VoiceDropAction").hide();

        }
        else
        {
            $("#VoiceDropAction").hide();
            $("#IvrAction").hide();

        }

    });

   $(document).on("change", "#amd_drop_action", function ()
    {
        var amd_drop_action = $("#amd_drop_action").val();
        if(amd_drop_action == 2)
        {
            //$("#VoiceDrop").show();

            $("#audio_message_amd").show();
           $("#VoiceDrop").hide();


        }
       

       else if(amd_drop_action == 3)
        {
            $("#VoiceDrop").show();
            $("#audio_message_amd").hide();


            //$("#audio_message_amd").show();

        }
        else
        {
                        $("#VoiceDrop").hide();
            $("#audio_message_amd").hide();
        }
       

    });

    $(document).on("change", "#dial-mode", function ()
    {
        var dial_mode = $("#dial-mode").val();
        if(dial_mode == 'predictive_dial')
        {
            $("#call_ratio_div").show();

            $("#label_id").text('Call Ratio');


             var html='';

            html +=' <option value="1">1</option><option value="1.5">1.5</option><option value="2">2</option><option value="2.5">2.5</option><option value="3">3</option><option value="3.5">3.5</option><option value="4">4</option><option value="4.5">4.5</option><option value="5">5</option>';

            $("#call_ratio").html(html);


            $("#duration_div").show();

            $("#label_id_duration").text('Duration In Sec ');


            var duration='';
            for(i=0;i<16;i++)
            {
             duration+='<option value="'+i+'">'+i+'</option>';

            }

            $("#duration").html(duration);
            $("#automated_duration").show();
        $("#amd_call").show();
        $("#no_agent_available_action").show();
            $("#redirect_to_div").hide();

             $("#call_ratio_div").show();
             $("#duration_div").show();

                $("#call_ratio").show();
            $("#duration").show();





        }

        else
            if(dial_mode == 'outbound_ai')
        {
            $("#call_ratio_div").show();

             var html='';

            for(i=1;i<31;i++)
{

            html +='<option value="'+i+'">'+i+'</option>';
}

            $("#call_ratio").html(html);

            $("#label_id").text('Simultaneous Calls ');


            $("#duration_div").show();


            $("#label_id_duration").text('Time Interval ');


            var duration = '';
            
             duration+='<option value="0.5">30 Sec</option><option value="1">1 Min</option><option value="2">2 Min</option><option value="5">5 Min</option><option value="10">10 Min</option><option value="20">20 Min</option><option value="30">30 Min</option>';

           

            $("#duration").html(duration);
            $("#automated_duration").hide();
        $("#amd_call").show();
        $("#no_agent_available_action").hide();
            $("#redirect_to_div").show();

              $("#call_ratio_div").show();
             $("#duration_div").show();

              $("#call_ratio").show();
            $("#duration").show();

            $("#IvrAction").hide();
            $("#VoiceDropAction").hide();





        }

        else
        {
            $("#call_ratio").hide();
            $("#duration").hide();
            $("#automated_duration").hide();
        $("#amd_call").hide();
            $("#redirect_to_div").hide();
             $("#call_ratio_div").hide();
             $("#duration_div").hide();
             $("#ivr").hide();
             $("#amd_drop").hide();
             $("#no_agent_available_action").hide();





        }
    });

    $(".openrecycle").click(function()
    {
        $("#recycleListModal").modal();
        var list_id = $(this).data("id");
        var campaign_id = $(this).data("camid");
        $('#recycle_list_id').val(list_id);
        $('#recycle_campaign_id').val(campaign_id);
        $.ajax({
            url: '/listDisposition/'+list_id,
            type: 'get',
            success: function(response){
                $('#disposition').html("");
                 for(var i = 0; i < response.length; i++) {
                var elem = document.getElementById('disposition');
                
                  var id = response[i].id;
                  var name = response[i].name;
                  var record_count = response[i].record_count;

                  

                  elem.innerHTML = elem.innerHTML + "<div class='col-md-12 p-b-10'><div class='col-md-1'><input type='checkbox' value='"+id+"' name='param[disposition][]'/></div><div class='col-md-6'>"+name+"</div><div class='col-md-2'>"+record_count+"</div><div class='col-md-3'><select class='form-control' name='param[select_id_"+id+"]'><option value='1'>1</option><option value='2'> less than or equal to 2</option><option value='3'> less than or equal to 3</option><option value='4'>less than or equal to 4</option><option value='5'>less than or equal to 5</option><option value='6'>less than or equal to 6</option><option value='7'>less than or equal to 7</option><option value='8'> less than or equal to 8</option><option value='9'> less than or equal to 9</option><option value='10'>less than or equal to 10</option><option value='11'>less than or equal to 11</option><option value='12'>less than or equal to 12</option><option value='13'>less than or equal to 13</option><option value='14'>less than or equal to 14</option><option value='15'>less than or equal to 15</option></select></div><hr></div>";

                  

                
              }
            }
        });
        /*.done(function( response ) {
            $('#disposition').html(response);
        });*/

    });


     $(".openCampaignDelete").click(function() {
        var delete_id = $(this).data('camid');
        var list_id = $(this).data('id');
        $("#delete").modal();
        $("#camid").val(delete_id);
        $("#list_id").val(list_id);

    });

          $(document).on("click", ".deleteCampaignList" , function() {
    //if(confirm("Are you sure you want to delete this?")){
        var list_id = $('#list_id').val();
        var camid = $('#camid').val();

       //alert(group_id);
        var el = this;
        $.ajax({
            url: '/deleteListData/'+list_id+'/'+camid,
            type: 'get',
            success: function(response){
                 window.location.href = "/campaign";
            }
        });
     //   window.location.reload(1);
   
});



    
</script>

<script>
    $(document).ready(function() {
        var oTable = $('#example').dataTable({
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [2, 3]
            }]
        });
    });
</script>
<script>
    $(function()
    {
        $("#example").on("change", ".toggle-class", function ()
        {
            $('#loading').show();
            var status = $(this).prop('checked') == true ? 1 : 0; 
            var campaignid = $(this).data('campaignid');             
            var listid = $(this).data('listid');             

            $.ajax({
                type: "GET",
                dataType: "json",
                url: '/updateCampaignList/'+campaignid+'/'+listid+'/'+status+'/4',
                success: function(data)
                {
                    if(data.status == 'true')
                    {
                        toastr.success(data.message);
                    }
                    else
                    {
                        toastr.error(data.message);

                    }
                    $('#loading').show();

                    console.log(data.success);
                    window.location.reload(1);
                }
            });
        })
    })
</script>
@endsection
