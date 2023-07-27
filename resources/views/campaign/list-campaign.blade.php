

@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
                <h1>
                    <b>List Campaign</b>
                    
                </h1>
                <ol class="breadcrumb">
                      <li><a href="{{('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                      <li class="active">Campaign</li>
                    <li class="active">List Campaign</li>
                </ol>
        </section>
        
   

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <form class="form-inline"  method="post">
 @csrf
                                   <input type="hidden" class="form-control" name="campaign_id" value="{{$campaign_data->id}}" id="campaign_name" required="">
                                    <div class="form-group col-md-2">
                                        <label>Name</label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                                <input type="text" class="form-control" name="name" value="{{$campaign_data->name}}" id="campaign_name" required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Description</label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                                <textarea type="textarea" class="form-control" name="description" id="campaign_description" style="height: 38px;">{{$campaign_data->description}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Dial Mode</label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                                <select name="dial_mode" class="form-control" id="dial-mode" required="">
                                    @foreach($dailingMode as $mode)
                                    <option @if($campaign_data->dial_mode == $mode->mode_name) selected @endif value="{{$mode->mode_name}}">{{$mode->name}}</option>
                                    @endforeach
                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-l-10">
                                        <label>Status</label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                                 <select name="status" class="form-control" id="campaign_status">
                                    <option @if($campaign_data->status == 1) selected @endif  value="1">Active</option>
                                    <option @if($campaign_data->status == 0) selected @endif  value="0">Inactive</option>
                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-l-10">
                                        <label>Caller Id</label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                               <select name="caller_id" class="form-control" id="caller_id" required="">
                                    <option @if($campaign_data->caller_id == 'custom') selected @endif value="custom">Custom</option>
                                    <option @if($campaign_data->caller_id == 'area_code') selected @endif value="area_code">Area Code</option>
                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-l-10" id="custom-input">
                                        <label>Custom Caller Id</label>
                                        <div>
                                            <div class="input-daterange input-group col-md-12">
                                <input type="text" class="form-control" name="custom_caller_id" value="{{$campaign_data->custom_caller_id}}" id="custom-caller-id" required="">
                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-l-10">
                                        <label>Caller Group</label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range1">
                                                <select name="group_id" class="form-control" id="group_id" required="">
                                    @foreach($extension_group as $group)
                                        <option @if($campaign_data->group_id == $group->id) selected @endif value="<?php echo $group->id; ?>"><?php echo $group->name; ?></option>
                                        
                                    @endforeach
                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-l-10">
                                        <label>Call Time</label>
                                        <div>
                                            <div class="input-daterange input-group" style="width: 270px;">
                                                <input type="text" class="form-control" value="{{$campaign_data->call_time_start}}" name="call_time_start" id="timepicker">
                                                <span class="input-group-addon bg-primary text-white b-0">to</span>
                                                <input type="text" class="form-control" value="{{$campaign_data->call_time_end}}" name="call_time_end" id="timepicker3">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-l-10">
                                        <label>Time Based Calling</label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range1">
                                                 <select name="time_based_calling" class="form-control" id="time_based_calling" required="">
                                <option @if($campaign_data->time_based_calling == 1) selected @endif value="1">Yes</option>
                                <option @if($campaign_data->time_based_calling == 0) selected @endif value="0">No</option>
                            </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-l-10">
                                        <label>Disposition</label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range1">
                                                <span class="multiselect-native-select"><select name="disposition[]" class="form-control" multiple="multiple" id="disposition-multiple-selected" required="">

                                @foreach($disposition_list as $key => $dlist)
                                <option @if(in_array($dlist->id, $mapping))  selected  @endif value="{{$dlist->id}}">{{$dlist->name}}</option>
                                @endforeach
                            </select></span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range1">
                                                <input type="hidden" class="form-control" name="username" value="{{$userdetails->username}}" id ="first-name" required>
                                                <button type="submit" name="submit" class="btn btn-success waves-effect waves-light m-l-10" value="Search">Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div> <!-- panel-body -->
                        </div> <!-- panel -->
                    </div>


                    <div class="col-md-12">
                        <h2>List Associated :</h2>

                     
                        

                        @foreach($list as $list_data)
                        <div class="panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-campaignid={{$campaign_data->id}} class="selectDespositioncount" href="#collapse{{$list_data->id}}">{{$list_data->name}}</a>
                                    </h4>
                                </div>
                                <div id="collapse{{$list_data->id}}" class="panel-collapse collapse">

                                    <ul class="list-group">
                                        @foreach($disposition_list  as $disposition)

                                        

                                        <li class="list-group-item">{{$disposition->name}} <b style="float:right">@php
    echo App\Http\Controllers\CampaignController::countLeadReport($disposition->id,$list_data->id);
   @endphp</b></li>
                                        @endforeach
                                    </ul>
                                    <!-- <div class="panel-footer">Footer</div> -->
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>


        </div><!-- /.row -->
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
