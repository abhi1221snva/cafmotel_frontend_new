

@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->


     <section class="content-header">
                <h1>
                    <b>Add Campaign</b>
                </h1>
                <ol class="breadcrumb">
                     <li><a href="{{('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                     <li class="active">Campaign</li>
                    <li class="active">Add Campaign</li>
                </ol>
        </section>

    <section class="content-header">

        <div class="text-right mt-5 mb-3"> 
           
               
                 <a href="{{ url('/campaign') }}"  type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show Campaign</a>
           </div>
       
       
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <div class="box-body">
                         <form method="post" action="">
                             @csrf
                <div class="modal-body">
                    <div class="row col-md-12">
                        <div class="form-group col-md-6">
                            <label>Name <i data-toggle="tooltip" data-placement="right" title="Type campaign name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <input type="text" class="form-control" name="title" value="" id="campaign_name" required="">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Description <i data-toggle="tooltip" data-placement="right" title="Type description for campaign" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <textarea type="textarea" class="form-control" name="description" value="" id="campaign_description"></textarea>
                            </div>
                        </div>
                  
                    
                        <div class="form-group col-md-6" id="show_predictive">
                            <label>Dialing Mode <i data-toggle="tooltip" data-placement="right" title="Select dialing mode" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="dial_mode" class="form-control" id="dial-mode" required="">
                                    @if(!empty($campaign_type_list))
                                    @foreach($campaign_type_list as $type)
                                     <option value="{{$type->title_url}}">{{$type->title}}</option>
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
                            <label id="label_id_duration">Duration </label> <i data-toggle="tooltip" data-placement="right" title="Select duration for run predictive call" class="fa fa-info-circle" aria-hidden="true"></i>
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

                                    <option value="1">Audio Message</option>
                                    <option value="2">Voice Template</option>
                                    <option value="3">Extension</option>
                                    <option value="4">Ring Group</option>
                                    <option value="5">IVR</option>

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
                                                    value={{$voice_lst->templete_id}}> {{$voice_lst->templete_name}}
                                                </option>
                                                @endforeach
                                                @endif

                                    
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

                        <div class="form-group col-md-3" style="display: none;" id="extension">
                            <label>Extension <i data-toggle="tooltip" data-placement="right" title="VoiceDrop Option" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="outbound_ai_dropdown_extension" class="form-control" id="">
                                    @foreach($extension_list as $extension)
                                            @if($extension->id)
                                                <option  value="{{$extension->id}}">{{$extension->first_name}} {{$extension->last_name}} - {{$extension->extension}} </option>
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
                                                <option  
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
                                                <option  value={{$rgroup_lst->id}}>{{$rgroup_lst->description}} - {{$rgroup_lst->title}}
                                                </option>
                                                @endforeach
                                                @endif
                                    
                                </select>
                            </div>
                        </div>

                        

                        <div class="form-group col-md-2" style="display: none;" id="automated_duration">
                            <label>Automated Duration <i data-toggle="tooltip" data-placement="right" title="Select On for run duration auto predictive call" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="automated_duration" class="form-control" id="autoduration">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-2" style="display: none;" id="amd_call">
                            <label>AMD <i data-toggle="tooltip" data-placement="right" title="Select yes for run AMD call" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="amd" class="form-control" id="amd">
                                    <option value="0">Off</option>
                                    <option value="1">On</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-2" style="display: none;" id="amd_drop">
                            <label>AMD Drop Action <i data-toggle="tooltip" data-placement="right" title="AMD Drop Action" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="amd_drop_action" class="form-control" id="amd_drop_action">
                                    <option value="1">HangUp</option>
                                    <option value="2">Audio Message</option>
                                    <option value="3">Voice Template</option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-3" style="display: none;" id="VoiceDrop">
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

                          <div class="form-group col-md-3" style="display: none;" id="audio_message_amd">
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


                        <div class="form-group col-md-2" style="display: none;" id="no_agent_available_action">
                            <label>No Agent available Action <i data-toggle="tooltip" data-placement="right" title="Select action from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="no_agent_available_action" class="form-control" id="no_agent_available">
                                    <option value="1">Hang Up</option>
                                    <option value="2">Voice Drop</option>
                                    <option value="3 ">Inbound IVR</option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-3" style="display: none;" id="VoiceDropAction">
                            <label>Voice Drop Option <i data-toggle="tooltip" data-placement="right" title="VoiceDrop Option" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="voicedrop_no_agent_available_action" class="form-control" id="">
                                    @foreach($extension_list as $extension)
                                            @if($extension->id)
                                                <option value="{{$extension->id}}">{{$extension->first_name}} {{$extension->last_name}} - {{$extension->extension}} </option>
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
                                                <option 
                                                    value={{$ivr_lst->ivr_id}}>{{$ivr_lst->ivr_desc}} - {{$ivr_lst->ivr_id}}
                                                </option>
                                                @endforeach
                                                @endif

                                    
                                </select>
                            </div>
                        </div>


                        <div class="form-group col-md-6" id="show_predictive_status">
                            <label>Status <i data-toggle="tooltip" data-placement="right" title="Select status for active/inactive" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="status" class="form-control" id="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row col-md-12">
                        <div class="form-group col-md-6">
                            <label>Caller Id <i data-toggle="tooltip" data-placement="right" title="Select Caller Id" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="caller_id" class="form-control" id="caller_id" required="">
                                    <option value="custom">Custom</option>
                                    <option value="area_code">Area Code</option>
                                    <option value="area_code_random">AreaCode And Randomizer</option>
                                    <option value="area_code_3">Area Code + 3</option>
                                    <option value="area_code_4">Area Code + 4</option>
                                    <option value="area_code_5">Area Code + 5</option>


                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Custom Caller Id <i data-toggle="tooltip" data-placement="right" title="Select Phone Number from the list" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <select required class="form-control" name="custom_caller_id" value="" id="custom-caller-id">
                                <option value="">Select DID</option>
                                @if(count($did_list) > 0)
                                    @foreach(array_reverse($did_list) as $key => $lists)
                                    <option data-cnam="{{$lists->cnam}}" value="<?php echo $lists->cli ?>"><?php echo $lists->cli ?> <?php if(!empty($lists->cnam)) echo '-'.$lists->cnam ?>
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
                    </div>
                    <div class="row col-md-12">
                        <div class="form-group col-md-6">
                            <label>Call Time <i data-toggle="tooltip" data-placement="right" title="Choose call time to be used by the campaign" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div>
                                <div class="input-group">
                                    <input type="time" class="form-control" value="09:30" name="call_time_start" id="timepicker">
                                    <span class="input-group-addon bg-primary text-white b-0">to</span>
                                    <input type="time" class="form-control" value="21:30" name="call_time_end" id="timepicker3">
                                </div>
                            </div>
                        </div>
                       
                        @if(!empty($group))
                                        <div class="form-group col-md-6">
                                            <label>Caller Group <i data-toggle="tooltip" data-placement="right" title="Select the group the extension is associated with " class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="select2" required="" multiple="multiple" name="group_id[]" autocomplete="off" data-placeholder="Select Group" style="width: 100%;">
                                                    @foreach($group as $group_ext)
                                                        <option value="{{$group_ext->id}}">{{$group_ext->title}}</option>
                                                    @endforeach;
                                                </select>
                                            </div>
                                        </div>
                                        @else

                                        <div class="form-group col-md-6">
                                            <label>Caller Group <i data-toggle="tooltip" data-placement="right" title="Select the group the extension is associated with " class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span><span style="color:green" id="successGroup"></span></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="select2" id="groups" required="" multiple="multiple" name="group_id[]" autocomplete="off" data-placeholder="Select Group" style="width: 100%;">
                                                </select>
                                            @if(empty($group))
                                                <div id="hiddenDivGrp" class="input-group-btn">
                                                  <a id="openGrpForm" style="float:right;" type="submit" class="btn btn-danger"><i class="fa fa-plus"></i> Add Group</a>
                                                </div>
                                            @endif
                                            </div>
                                        </div>

                                        @endif
                                        </div>
                            <div class="row col-md-12">                
                                    <div class="form-group col-md-6">
                                        <label>Country Code <i data-toggle="tooltip" data-placement="right" title="Select Country Code" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select class="form-control"  name="country_code" id="country_code">
                                                                @if (is_array($phone_country))
                                                                @foreach($phone_country as $code)
                                                                <option  value={{$code->phonecode}}>{{$code->name}} (+{{$code->phonecode}})
                                                                </option>
                                                                @endforeach
                                                                @endif
                                            </select>
                                        </div>
                                    </div>
                    
                                <div class="form-group col-md-6">
                                    <label>Time Based Calling <i data-toggle="tooltip" data-placement="right" title="Select yes/no for time based call system" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <div class="input-daterange input-group col-md-12">
                                        <select name="time_based_calling" class="form-control" id="time_based_calling" required="">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                <div class="row col-md-12">  
                    @if(!empty($crm_lists))
                     <div class="form-group col-md-6">
                        <label>CRM Integration <i data-toggle="tooltip" data-placement="right" title="CRM Integration will be enabled if you enable this feature" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-daterange input-group col-md-12">
                            <select name="crm_title_url" class="form-control" id="select_crm" >
                                <option >Select CRM</option>
                                <option value="0">No CRM</option>
                                    @foreach($crm_lists as $list)
                                        <option value="{{$list->title_url}}">{{$list->title}}</option>
                                    @endforeach
                                
                            </select>
                        </div>
                    </div>

                    @endif


                    
                    @if(!empty($hubspot_lists))
                    <div class="form-group col-md-6"  style="display: none;" id="show_select_crm">
                        <label>Lists <i data-toggle="tooltip" data-placement="right" title="Select multiple disposition from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-daterange input-group col-md-12">
                        <select class="select2" required multiple="multiple" name="hubspot_lists[]" autocomplete="off" data-placeholder="Select Lists" style="width: 100%;">
                            <option value="">select Lists</option>
                            
                                @foreach($hubspot_lists as $lst)
                                <option value="{{$lst->listId}}">{{$lst->name}}</option>
                                @endforeach
                                
                        </select>
                        </div>
                    </div>

                    @endif

                    <div class="form-group col-md-6"  style="display: none;" id="send_to_crm">
                        <label>Send to crm <i data-toggle="tooltip" data-placement="right" title="CRM Integration will be enabled if you enable this feature" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-daterange input-group col-md-12">
                            <select name="send_crm" class="form-control" id="send_crm" >
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-6" style="display: none;" id="show_send_to_crm">
                        <label>API Template <i data-toggle="tooltip" data-placement="right" title="API Template For Send To CRM" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-daterange input-group col-md-12">
                            <select name="api_id" class="form-control" required id="api_id" >
                                @if(!empty($api_list))
                        @foreach($api_list as $key => $api)
                         @if($api->is_default == '1')
                                <option value="{{$api->id}}">{{$api->title}} ({{$api->url}})</option>
                        @endif

                                 @endforeach   
                        @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Send Email <i data-toggle="tooltip" data-placement="right" title="Email Integration will be enabled if you enable this feature" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-daterange input-group col-md-12">
                            <select name="email" class="form-control" id="email" >
                                <option value="0">No</option>
                                <option value="1">With User Email</option>
                                <option value="2">With Campaign Email</option>
                                <option value="3">With System Email</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row col-md-12">  
                    <div class="form-group col-md-6">
                        <label>Send Sms <i data-toggle="tooltip" data-placement="right" title="Text Integration will be enabled if you enable this feature" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-daterange input-group col-md-12">
                            <select name="sms" class="form-control" id="sms" >
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group col-md-6">
                        <label>Send Report <i data-toggle="tooltip" data-placement="right" title="Send reports yes/no" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-daterange input-group col-md-12">
                        <select name="send_report" class="form-control" id="campaign_send_report">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                </div>
                  

                <div class="row col-md-12">  
                    @if(!empty($disposition_list))
                    <div class="form-group col-md-6">
                        <label>Disposition <i data-toggle="tooltip" data-placement="right" title="Select multiple disposition from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-daterange input-group col-md-12">
                        <select class="select2" required multiple="multiple" name="disposition[]" autocomplete="off" data-placeholder="Select Disposition" style="width: 100%;">
                            <option value="">select disposition</option>
                            @foreach($disposition_list as $key => $disposition)
                            <option value="{{$disposition->id}}">{{$disposition->title}}</option>
                            @endforeach;
                        </select>
                        </div>
                    </div>
                    @else

                    <div class="col-md-6">
                        <label>Disposition <i data-toggle="tooltip" data-placement="right" title="Select multiple disposition from drop down" class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span><span style="color:green" id="successGroup"></span></label>
                                <div class="input-daterange input-group col-md-12">
                                                <select class="select2" multiple="multiple" name="disposition[]" autocomplete="off" id="disposition_add" data-placeholder="Select Disposition" style="width: 100%;">
                                                    <option value="">select disposition</option>
                                                    @foreach($disposition_list as $key => $disposition)
                                                    <option value="{{$disposition->id}}">{{$disposition->title}}</option>
                                                    @endforeach;
                                                </select>
                                            @if(empty($disposition_list))
                                                <div id="hiddenDiv" class="input-group-btn">
                                                  <a id="openDispositionForm" style="float:right;" type="submit" class="btn btn-danger"><i class="fa fa-plus"></i> Add Disposition</a>
                                                </div>
                                            @endif
                                </div>
                    </div>                                

                    @endif

                    <div class="form-group col-md-6">
                        <label>Hopper Mode Type <i data-toggle="tooltip" data-placement="right" title="Select Hopper mode type" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-daterange input-group col-md-12">
                            <select name="hopper_mode" class="form-control" id="hopper_mode">
                                <option value="1">Linear</option>
                                <option value="2">Random</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row col-md-12">  
                    @if(!empty($voip_configurations))
                    <div class="form-group col-md-6">
                        <label>Outbound Line <i data-toggle="tooltip" data-placement="right" title="Select Voip Configuration " class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-daterange input-group col-md-12">
                        <select class="form-control" required name="voip_configurations" autocomplete="off" data-placeholder="Select Disposition" >
                            <option value="">Select VOIP</option>
                            @foreach($voip_configurations as $key => $voip)
                            <option value="{{$voip->id}}">{{$voip->name}}</option>
                            @endforeach;
                        </select>
                        </div>
                    </div>
                    @endif
                </div>







                  <!--   <div class="form-group col-md-6">
                        <label>Disposition</label>
                        <div class="input-daterange input-group col-md-12">
                            <span class="multiselect-native-select">
                                <select name="disposition[]" class="form-control" multiple="multiple" id="disposition-multiple-selected" required="">

                                            <option value="">select disposition</option>


                                            @foreach($disposition_list as $key => $disposition)

                                            <option value="{{$disposition->id}}">{{$disposition->title}}</option>

                                            @endforeach
                                </select>
                        </div>
                    </div> -->
                </div>

                <div class="row col-md-12">
                     <div class="form-group col-md-6">
                        <label></label>
                        <div class="input-daterange input-group col-md-12">
                        </div>
                    </div>
                        <!-- <div class="form-group col-md-4">
                            <label>Max Lead Temp</label>
                            <div class="input-daterange input-group col-md-12">
                                <input type="text" onkeypress="return isNumberKey(event)" class="form-control" name="max_lead_temp" value="" id="max_lead_temp" required="">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Min Lead Temp</label>
                            <div class="input-daterange input-group col-md-12">
                                <input type="text" onkeypress="return isNumberKey(event)" class="form-control" name="min_lead_temp" value="" id="min_lead_temp" required="">
                            </div>
                        </div> -->
                    </div>
                <div class="row lead_status">
                </div>
                <div class="modal-footer">
                    <input type="hidden" class="form-control" name="username" value="" id ="first-name" required>
                    <button type="submit" name="submit" value="Save" class="btn btn btn-primary waves-effect waves-light">Save</button>
                </div>
            </form>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>

<div class="modal fade" id="myModalGrp" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content" style="">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="add-edit-Grp"></h4>
                            </div>

                            <form method="post" action="">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" class="form-control" name="id" value="" id="edit-group-id" required>
                                    <div class="form-group">
                                    <label for="inputEmail3" class="col-form-label">Name</label>
                                    <input type="text" class="form-control" required name="title" id="title_grp" placeholder="Enter Name" value="" />
                                     </div>
                                    <span id="errorGroup" style="color:red;"></span>
                                    <div class="form-group">
                                    <label for="inputPassword3" id="" class="col-form-label">Extension <i data-toggle="tooltip" data-placement="right" title="select multiple extension from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    
                                    <select class="select2" multiple="multiple"  name="extensions[]" id="extensions" autocomplete="off" data-placeholder="Select Extension" style="width: 100%;">
                                        
                                    </select>
                                    </div>
                                    <input type="hidden" class="form-control" name="status" value="1" id="status" >

                                </div>
                                <div class="modal-footer">
                                    <a href="/extension-group" type="button" class="btn btn btn-danger waves-effect waves-light" data-dismiss="modal"><i class="fa fa-reply fa-lg"></i> Cancel</a>

                                     <a onclick="window.location.reload();" type="button" class="btn btn btn-warning waves-effect waves-light" data-dismiss="modal"><i class="fa fa-refresh fa-lg"></i> Reset</a>
                                    <button type="button" id="submitGroup" name="submit" class="btn btn btn-primary waves-effect waves-light"><i class="fa fa-check-square-o fa-lg"></i> Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="add-edit"></h4>
        </div>
                <div class="modal-body">
                    <form method="post" action="">
                                @csrf
                        <div class="box-body">

                                <input type="hidden" class="form-control" name="id" value="" id ="id" required>


                                    <input type="hidden" class="form-control" name="username" value="" id ="first-name" required>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label for="inputEmail3" class="col-form-label">Name</label>
                                    <input type="text" class="form-control" required  name="title" id="title" placeholder="Enter Name">
                                    <span id="errorDisposition" style="color:red;"></span>
                                </div>

                                <div class="col-sm-12">
                                    <label for="inputEmail3" class="col-form-label">State</label>
                                    <select name="d_type" class="form-control" id="d_type" >
                                    <option value="1">Status</option>
                                    <option value="2">Do Not Call</option>
                                    <option value="3">Callback</option>
                                    </select>
                                </div>
                                <div class="col-sm-12">
                            <label for="inputEmail3" class="col-form-label">Enable SMS Pop Up <i data-toggle="tooltip" data-placement="right" title="Select Enable Sms" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <select name="enable_sms" class="form-control" id="enable_sms" >
                            <option value=""Selected>--Select--</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                            </select>
                        </div>
                                
                            </div>


                        



                            <button type="button" id="submitDisposition" name ="submit"  class="btn btn btn-primary waves-effect waves-light">Save</button>
                        

                        </div><!-- /.box-body -->
                    </form>
               </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> -->
      </div>
      
    </div>
</div>
<!-- /.content-wrapper -->
<script>
     $(document).on("click", "#openGrpForm", function () {
    $("#add-edit-Grp").html('Add Extension Group');
    $("#edit-group-id").val('');
    $("#title_grp").val('');
    loadExtensionOptions([], null);
    $("#myModalGrp").modal();
});

function loadExtensionOptions(selectedExtensions, selectedGroupID) {
    $.ajax({
        url: 'mapExtensionGroup/',
        type: 'get',
        success: function (response) {
            var options = '';

            if (response.success && response.data && response.data.length > 0) {
                var uniqueExtensions = [];
                response.data.forEach(function (extension) {
                    if (extension.is_deleted === 0 && !uniqueExtensions.includes(extension.extension)) {
                        uniqueExtensions.push(extension.extension);
                        options += '<option value="' + extension.extension + '">' + extension.first_name + ' ' + extension.last_name + '-' + extension.extension + '</option>';
                    }
                });
            }

            $("#extensions").html(options);

            if (selectedGroupID) {
                loadSelectedExtensions(selectedGroupID, selectedExtensions);
            } else {
                $('#extensions').val(selectedExtensions).trigger('change');
            }
        }
    });
}

function loadSelectedExtensions(group_id, selectedExtensions) {
    $.ajax({
        url: 'mapExtensionGroup/',
        type: 'get',
        success: function (response) {
            var extensions = [];

            if (response.success && response.data && response.data.length > 0) {
                response.data.forEach(function (extension) {
                    if (extension.group_id === group_id && extension.is_deleted === 0) {
                        extensions.push(extension.extension);
                    }
                });
            }

            if (selectedExtensions) {
                selectedExtensions.forEach(function (extension) {
                    if (!extensions.includes(extension)) {
                        extensions.push(extension);
                    }
                });
            }

            $('#extensions').val(extensions).trigger('change');
        }
    });
}
    $(document).on("change", "#send_crm", function ()
    {
        var send_crm = $("#send_crm").val();

        if(send_crm == 1)
        {
            $("#show_send_to_crm").show();
        }
        else
        {
            $("#show_send_to_crm").hide();

        }


    });

    $(document).on("change", "#select_crm", function ()
    {
        var send_crm = $("#select_crm").val();


        if(send_crm == '0') //no crm
        {
            $("#send_to_crm").hide();
        }

       else if(send_crm == 'hubspot') //hubspot
        {
            $("#show_select_crm").show();
            $("#send_to_crm").show();

        }

      else  if(send_crm == 'mca_crm') //hubspot
        {
            $("#send_to_crm").show();

        }
        else
        {
            $("#show_select_crm").hide();

        }


    });


    $(document).on("change", "#redirect_to", function ()
    {
        var redirect_to = $("#redirect_to").val();

        if(redirect_to == '1')
        {
            $("#audio_message").show();
            $("#extension").hide();
              $("#ivr").hide();
              $("#voice_message").hide();
              $("#ring_group").hide();



        }

         if(redirect_to == '2')
        {
            $("#voice_message").show();
            $("#extension").hide();
              $("#ivr").hide();
              $("#audio_message").hide();
              $("#ring_group").hide();



        }
        if(redirect_to == '3')
        {
            $("#audio_message").hide();
            $("#extension").show();
              $("#ivr").hide();
              $("#audio_message").hide();
              $("#ring_group").hide();
              $("#voice_message").hide();




        }

        else
            if(redirect_to == '5')
        {
              $("#ivr").show();
            $("#extension").hide();
              $("#audio_message").hide();
              $("#audio_message").hide();
              $("#ring_group").hide();
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




        }
        else
        {
            $("#VoiceDropAction").hide();
            $("#IvrAction").hide();

        }

    });


    $("#openDispositionForm").click(function(){
        $("#myModal").modal();
        $("#name").val('');
        $("#status").val('1');
        $("#id").val('');
        $("#add-edit").html('Add dispositon');
    });

    $(document).on("change", "#amd", function ()
    {
        var amd = $("#amd").val();
        if(amd == 0)
        {
            $("#amd_drop").hide();
            $("#VoiceDrop").hide();
            $("#audio_message_amd").hide();
            

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
            $("#label_id").text('Call Ratio ');
            $("#call_ratio_div").show();
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





            $('#show_predictive').removeClass('col-md-6');
            $('#show_predictive').addClass('col-md-3');

            $('#show_predictive_status').removeClass('col-md-6');
            $('#show_predictive_status').addClass('col-md-3');

        }

        else
            if(dial_mode == 'outbound_ai')
        {



            $("#call_ratio_div").show();

            $("#label_id").text('Simultaneous Calls ');

            var html='';

            for(i=1;i<31;i++)
{

            html +='<option value="'+i+'">'+i+'</option>';
}

            $("#call_ratio").html(html);
            $("#duration_div").show();


            $("#label_id_duration").text('Time Interval ');


            var duration = '';
            
             duration+='<option value="0.5">30 Sec</option><option value="1">1 Min</option><option value="2">2 Min</option><option value="5">5 Min</option><option value="10">10 Min</option><option value="20">20 Min</option><option value="30">30 Min</option>';

           
            $("#duration").html(duration);
            $("#automated_duration").hide();
            $("#amd_call").show();
            $("#no_agent_available_action").hide();
            $("#redirect_to_div").show();
            $("#IvrAction").hide();
            $("#VoiceDropAction").hide();

            $('#show_predictive').removeClass('col-md-6');
            $('#show_predictive').addClass('col-md-3');

            $('#show_predictive_status').removeClass('col-md-6');
            $('#show_predictive_status').addClass('col-md-3');

        }

        else
        {
             $("#call_ratio_div").hide();
            $("#duration_div").hide();
            $("#automated_duration").hide();
            $("#amd_call").hide();
            $("#no_agent_available_action").hide();
            $("#redirect_to_div").hide();
            $("#amd_drop").hide();
            $("#VoiceDrop").hide();
            $("#audio_message_amd").hide();
            $("#IvrAction").hide();







            

            $('#show_predictive').addClass('col-md-6');
            $('#show_predictive').removeClass('col-md-3');

            $('#show_predictive_status').addClass('col-md-6');
            $('#show_predictive_status').removeClass('col-md-3');

        }
    });

    $(document).on("click", "#submitDisposition", function () {
            var title = $('#title').val();
            var d_type = $('#d_type').val();
            var enable_sms = $('#enable_sms').val();

            /* alert(title);
            alert(d_type);*/
            
            
            if(title == ""){
                $("#errorDisposition").html("Please enter title");
            }
            $.ajax({
                url: 'addDisposition/'+title+'/'+d_type+'/'+enable_sms,
                type: 'get',
                success: function (json)
                {
                    for(var i = 0; i < json.length; i++)
                    {
                        var obj = json[i];
                        optionText = obj.title;
                        optionValue = obj.id;
                        $('#disposition_add').append(new Option(optionText, optionValue));
                    }
                    $("#successGroup").html("Disposition added successful! Please select");
                    $("#hiddenDiv").hide();
                    $('#myModal').modal('hide');                    
                }
            });
        });
        
        $(document).on("click", "#submitGroup", function () {
            console.log('hi');
            var title = $('#title_grp').val();
            var extensions = $('#extensions').val();
            if(title == ""){
                $("#errorGroup").html("Please enter group title");
            }
            $.ajax({
                url: 'campaignAddGroup/'+title+'/'+extensions,
                type: 'get',

                success: function (json)
                {
                    for(var i = 0; i < json.length; i++)
                    {
                        var obj = json[i];
                        optionText = obj.title;
                        optionValue = obj.id;
                        $('#groups').append(new Option(optionText, optionValue));
                    }
                    $("#successGroup").html("Group added successful! Please select");
                    $("#hiddenDivGrp").hide();
                    $('#myModalGrp').modal('hide');
                }
            });
        });
        
</script>
@endsection
