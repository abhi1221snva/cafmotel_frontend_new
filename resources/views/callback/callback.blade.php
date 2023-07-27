@extends('layouts.app')@section('title', 'Callback')
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <b>Callback</b>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Callback</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="callback-reminder-setup">
                                <label>
                                    <input type="checkbox"  id="enable-callback-reminder" @if( $callbackStatus->callback_reminder == 'on') checked @endif> Enable Callback Reminder Alert!
                                </label>
                            </div>
                            <form class="form-inline" method="post">
                                @csrf
                                @if(Session::get('role') == 'admin')
                                    <div class="form-group m-l-10">
                                        <label>Extension <i data-toggle="tooltip" data-placement="right"
                                                            title="Select extension" class="fa fa-info-circle"
                                                            aria-hidden="true"></i></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                                <select class="form-control" name="extension" id="extension">
                                                    <option value="">Select</option>
                                                    @foreach($arrExtensionListRekeyed as $key => $extension)
                                                        <option
                                                            value="{{$extension->extension}}">{{$extension->extension}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" value="{{Session::get('extension')}}" name="extension">
                                @endif

                                <div class="form-group m-l-10">
                                    <label>Campaign <i data-toggle="tooltip" data-placement="right"
                                                       title="Select campaign" class="fa fa-info-circle"
                                                       aria-hidden="true"></i></label>
                                    <div>
                                        <div class="input-daterange input-group" id="date-range2">
                                            <select name="campaign" class="form-control" id="campaign">
                                                <option value="">Select</option>
                                                @foreach($arrCampaignListRekeyed as $key => $campaign)
                                                    <option value="{{$campaign->id}}">{{$campaign->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group m-l-10">
                                    <label>Date Range <i data-toggle="tooltip" data-placement="right"
                                                         title="Select date range for callback report"
                                                         class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <div>
                                        <div class="input-daterange input-group datepicker" id="date-range"
                                             style="width: 270px;">
                                            <input type="text" autocomplete="off" class="form-control col-md-6"
                                                   id="start_date" name="start_date"
                                                   value="<?php $current_date = date("Y-m-d");// current date

                                                   $str_date = strtotime(date("Y-m-d", strtotime($current_date)) . " -15 day");

                                                   echo date('Y-m-d', $str_date); ?>">
                                            <span class="input-group-addon bg-primary text-white b-0">to</span>
                                            <input type="text" autocomplete="off"
                                                   class="form-control col-md-6 datepicker" id="end_date"
                                                   name="end_date" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group m-l-10">
                                    <label style="color: transparent;">Filter</label>
                                    <div>
                                        <div class="input-daterange input-group" id="date-range1">
                                            <button type="submit" name="submit"
                                                    class="btn btn-success waves-effect waves-light m-l-10"
                                                    value="Search">Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <table id="callbackTable" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Extension</th>
                                    <th>Campaign Name</th>
                                    <th>Lead Id</th>
                                    <th>Phone No</th>
                                    <th>Callback Time</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($callback))
                                    @php
                                        $lead_data_values = $lead_data_headers = [];
                                        $leadIds = array_column($callback, 'lead_id'); @endphp
                                    @foreach($callback as $key => $value)
                                        <tr>
                                            <td><?php echo $key + 1; ?></td>
                                            <td>
                                                @if(isset($arrExtensionListRekeyed[$value->extension]))
                                                    {{$arrExtensionListRekeyed[$value->extension]->first_name}} {{$arrExtensionListRekeyed[$value->extension]->last_name}} -
                                                @elseif(isset($arrExtensionListRekeyedByAltExtension[$value->extension]))
                                                    {{$arrExtensionListRekeyedByAltExtension[$value->extension]->first_name}} {{$arrExtensionListRekeyedByAltExtension[$value->extension]->last_name}} -
                                                @else
                                                    (User Deleted) -
                                                @endif
                                                <?php echo $value->extension;?></td>
                                            <td>@if(isset($arrCampaignListRekeyed[$value->campaign_id]))
                                                    {{$arrCampaignListRekeyed[$value->campaign_id]->title}}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>@php
                                                    $list_values = $lead_data_values[$value->lead_id] = explode(",",$value->list_values);
                                                    $list_headers = $lead_data_header[$value->lead_id] = explode(",",$value->list_headers);

                                                @endphp
                                                <?php echo $value->lead_id;?>
                                                <i data-toggle="tooltip" data-placement="right" title="
                                                   @foreach($list_headers as $key => $heading)@if(isset($list_values[$key])) {{$heading}} : {{$list_values[$key]}} @endif

                                                @endforeach" class="fa fa-info-circle" aria-hidden="true"></i></td>
                                            <td>@php $intPhoneNumber = '-'; @endphp
                                                @if($value->is_dialing_selected_column)
                                                    @php $isDialingSelectedColumn = $value->is_dialing_selected_column; @endphp
                                                    @php $intPhoneNumber = $value->$isDialingSelectedColumn; @endphp
                                                @endif
                                                {{$intPhoneNumber}}</td>
                                            <!-- <td class="utc-time" data-utc-time="{{$value->callback_time}}"></td> -->
                                            <td>{{$value->callback_time}}</td>
                                            <td>
                                                <a style="cursor:pointer;color:blue;"
                                                   href="{{url('/lead-activity')}}?phone_number={{$intPhoneNumber}}"
                                                   title="View Lead Activity" class=''><i
                                                        class="fa fa-eye fa-lg"></i></a>
                                                <span style="padding: 0px 2px;">|</span>
                                                <a style="cursor:pointer;color:blue;" href="#" class='editCallback'
                                                   data-window="window-{{$value->lead_id}}" title='Edit Callback'><i
                                                        class="fa fa-edit fa-lg"></i></a>
                                                <span style="padding: 0px 2px;">|</span>
                                                <input type="hidden" name="extension" class="extension" value="{{Session::get('extension')}}">
                                                <input type="hidden" name="phone_number" class="phone_number" value="{{$intPhoneNumber}}">
                                                <input type="hidden" name="alt_extension" class="alt_extension" value="{{Session::get('private_identity')}}">
                                                <a href="javascript:void(0)" style="cursor:pointer;color:green;" class="callLead" data-source="siblings"><i
                                                        class="fa fa-phone fa-lg"></i></a>
                                        <div class="modal fade bd-example-modal-lg" id="window-{{$value->lead_id}}" role="dialog" aria-hidden="false" style="">
                                            <div class="modal-dialog  modal-lg">
                                                <div class="modal-content" style="text-align: center;border-radius: 5px;padding: 5px;">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h3>Edit Callback</h3>
                                                    </div>
                                                    <div class="modal-body" style="text-align: left;">
                                                        <label>Lead Details <i data-toggle="tooltip" data-placement="right" title="View the Lead Details" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                        <table style="text-align: left;margin: 0 auto;width: 100%;" class="table  table-striped table-bordered table-hover">
                                                            @foreach($list_headers as $key => $heading)
                                                                <tr>
                                                                @if(isset($list_values[$key]))
                                                                        <td>{{$heading}}</td>
                                                                        <td>{{$list_values[$key]}}</td> @endif
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                        <div style="margin:10px 0px;">
                                                            <label>Callback Time <i data-toggle="tooltip" data-placement="right" title="Choose date and time to update callback time" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                            <div class="input-daterange input-group col-md-12">
                                                                <div class="col-md-6" style="margin-left: -14px;">
                                                                    <input type="date" class="form-control callback_date" min="{{date('Y-m-d',strtotime($value->callback_time))}}" required=""  value="{{date('Y-m-d',strtotime($value->callback_time))}}">
                                                                </div>
                                                                <div class="col-md-3 callback-time">
                                                                    <input type="time" class="form-control callback_time" required="" value="{{date('H:i:s',strtotime($value->callback_time))}}" name="callback_time" >
                                                                </div>
                                                            </div>
                                                            <span style="color:red;" id="errorsms_run_time"></span>
                                                        </div>
                                                        <div class="mark-section" style="margin: 15px 0px;">
                                                            <label>Callback Done? <i data-toggle="tooltip" data-placement="right" title="Mark it as called if callback is done" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                            <div>
                                                                <select class="form-control package-control-selected mark-as-called" name="mark-as-called">
                                                                    <option value="#" {{$value->mark_as_called}}>Select</option>
                                                                    <option value="0" @php if($value->mark_as_called == '0') echo 'selected="selected"'; @endphp>No</option>
                                                                    <option value="1" @php if($value->mark_as_called == '1') echo 'selected="selected"'; @endphp>Yes</option>
                                                                    <option value="2" @php if($value->mark_as_called == '2') echo 'selected="selected"'; @endphp>Cancel</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        @if(Session::get('level') >= 7)
                                                            <div class="assign-section" style="margin: 15px 0px;">
                                                                <label>Assign callback <i data-toggle="tooltip" data-placement="right" title="Assign callback to Agent" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                                <div>
                                                                    <select class="form-control package-control-selected reassign-callback" name="reassign-callback">
                                                                    @foreach($arrExtensionListRekeyed as $key => $extension)
                                                                                <option value="{{$extension->extension}}" @php if($extension->extension == $value->extension) echo 'selected="selected"'; @endphp>{{$extension->first_name}} {{$extension->last_name}} (Extension - {{$extension->extension}})</option>
                                                                    @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" id="submitCallback" class="btn btn-primary"
                                                                value="" data-identifier="{{$value->cdr_id}}-{{$value->extension}}-{{$value->campaign_id}}-{{$value->lead_id}}">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <style>
        #callbackTable .tooltip .tooltip-inner {
            white-space: pre-line;
            text-align: left;
        }
        .callback-reminder-setup label{
            float: right;
            display: inline-block;
        }
    </style>
    <script type="text/javascript">
        $('#callbackTable').DataTable({"drawCallback": function( settings ) {
                $(".utc-time").each(function(i, element) {
                    var utcTime = $(element).data("utc-time");
                    if (utcTime) {
                        var localTime = moment.utc(utcTime).local();
                        $(element).html(localTime.format('YYYY-MM-DD HH:mm'));
                    }
                });
            }});

        $(document).on('click', '.editCallback', function (e) {
            e.preventDefault();
            var modelToShow = $(this).data("window");
            $('#' + modelToShow).modal('show');
        });

        $(document).on('click', '#submitCallback', function (e) {
            e.preventDefault();
            var markAsCalled = $(this).parent('.modal-footer').siblings('.modal-body').find('.mark-as-called').val();
            var reassignCallback = $(this).parent('.modal-footer').siblings('.modal-body').find('.reassign-callback').val();

            var callbackDate = $(this).parent('.modal-footer').siblings('.modal-body').find('.callback_date').val();
            var callbackTime = $(this).parent('.modal-footer').siblings('.modal-body').find('.callback_time').val();
            var convertedToUtc = callbackDate+' '+callbackTime;
            //alert(convertedToUtc);
            //var convertedToUtc = moment(callbackDate+' '+callbackTime).utc().format('YYYY-MM-DD HH:mm:ss');

            var callbackIdentifier = $(this).data("identifier");

            $.ajax({
                url: '/callback/edit',
                type: 'POST',
                data: {
                    mark_as_called: markAsCalled,
                    converted_to_utc: convertedToUtc,
                    callback_identifier: callbackIdentifier,
                    reassign_callback: reassignCallback
                },
                dataType:"json",
                success: function (response)
                {
                    toastr.success(response);
                    location.reload();
                }
            });
        });

        jQuery(document).ready(function () {
            $(".callback_date").each(function(i, element) {
                var utcTime = $(element).data("utc-time");
                if (utcTime) {
                    var localTime = moment.utc(utcTime).local();
                    $(element).attr('value',localTime.format('YYYY-MM-DD'));
                    $(element).parent().siblings(".callback-time").find("input.callback_time").attr('value',localTime.format('HH:mm'));
                }
            });

            $("#enable-callback-reminder").change(function() {
                if(this.checked){
                    $.ajax({
                        url: 'callback-reminder/show',
                        type: 'GET',
                        success: function (response){
                            toastr.success(response);
                            window.location.reload();
                        },
                        error: function (response) {
                            console.log(response);
                        }
                    });
                } else {
                    $.ajax({
                        url: 'callback-reminder/stop',
                        type: 'GET',
                        success: function (response){
                            toastr.success(response);
                            window.location.reload();
                        },
                        error: function (response) {
                            console.log(response);
                        }
                    });
                }
            });

            function convertLocalToUTC(dt, dtFormat) {
                return moment(dt, dtFormat).utc().format('YYYY-MM-DD hh:mm:ss A');
            }
        });
    </script>
@endsection
