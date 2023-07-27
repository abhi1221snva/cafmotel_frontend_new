@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height:500px !important">
        <!-- Content Header (Page header) -->

         <section class="content-header">
                <h1>
                   <b>Marketing schedules for {{$marketing_campaign->title}}</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Marketing Campaigns</li>

                    <li class="active">Marketing schedules for {{$marketing_campaign->title}}</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                <form method="post">
                    @csrf
                    <a href="{{ url('/marketing-campaigns') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Marketing Campaigns</a>
                </form>
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
                                    <th>List</th>
                                    <!-- <th>Column</th> -->
                                    <th>Template</th>
                                    <th>Account</th>
                                    <th>Type</th>
                                    <th>Scheduled Time</th>
                                    <th>Complete Time</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Sent</th>
                                    <th>Failed</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if(!empty($schedules))
                                @foreach($schedules as $key => $schedule)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>
                                        @foreach($list as $key => $lists)
                                        @if($lists->list_id == $schedule->list_id)
                                        {{$lists->list}}
                                        @endif
                                        @endforeach
                                    </td>
                                    {{-- <td>{{ $listHeaders[$schedule->list_id][$schedule->list_column_name]->header}}</td> --}}

                                    <td>
                                        @if($schedule->send == 1)
                                        @foreach($email_templates as $key => $template)
                                        @if($template->id == $schedule->email_template_id)
                                        {{$template->template_name}}
                                        @endif
                                        @endforeach
                                        @elseif($schedule->send == 2)
                                        @foreach($sms_templates as $key => $template)
                                        @if($template->templete_id == $schedule->sms_template_id)
                                        {{$template->templete_name}}
                                        @endif
                                        @endforeach
                                        @endif
                                    </td>



                                     <td>
                                        @if($schedule->send == 1)
                                        @foreach($smtp_setting as $key => $smtp)
                                        @if($smtp->id == $schedule->email_setting_id)
                                        {{$smtp->mail_host}}
                                        @endif
                                        @endforeach
                                        @elseif($schedule->send == 2)
                                        @foreach($did as $key => $dids)
                                        @if($dids->id == $schedule->sms_setting_id)
                                        {{$dids->cli}}
                                        @endif
                                        @endforeach
                                        @endif
                                    </td>

                                    <td>
                                        @if($schedule->send == 1)
                                        Email
                                        @elseif($schedule->send == 2)
                                        Text
                                        @endif
                                    </td>
                                    <td class="utc-time" data-utc-time="{{$schedule->run_time}}"></td>
                                    <td class="utc-time" data-utc-time="{{$schedule->complete_time}}"></td>

                                    <td>
                                        @if($schedule->status == 1)
                                        <span class="label label-info">Planned</span>
                                        @elseif($schedule->status == 2)
                                        <span class="label label-success">Processing</span>

                                        @elseif($schedule->status == 3)
                                        <span class="label label-danger">Failed</span>

                                        @elseif($schedule->status == 4)
                                        <span class="label label-success">Queued</span>

                                        @elseif($schedule->status == 5)
                                        <span class="label label-success">Executing</span>

                                        @elseif($schedule->status == 6)
                                        <span class="label label-success">Completed</span>

                                        @elseif($schedule->status == 7)
                                        <span class="label label-warning">Aborted</span>

                                        @endif
                                    </td>
                                    <td><a href="{{ url("/marketing-campaign/{$schedule->campaign_id}/schedule/{$schedule->id}/logs") }}">{{$schedule->scheduled_count}}</a></td>
                                    <td><a href="{{ url("/marketing-campaign/{$schedule->campaign_id}/schedule/{$schedule->id}/logs?run_status=3") }}">{{$schedule->sent_count}}</a></td>
                                    <td><a href="{{ url("/marketing-campaign/{$schedule->campaign_id}/schedule/{$schedule->id}/logs?run_status=5") }}">{{$schedule->failed_count}}</a></td>
                                    <td>
                                        <a style="cursor:pointer;color:blue;;" title='Edit only when campaign schedule is in PLANNED status'  @if($schedule->status == 1) href="{{url('marketing-campaign-schedule')}}/{{ $schedule->id}}"  @endif class='editEG'><i class="fa fa-edit"></i></a>
                                        | <a style="cursor:pointer;color:red;" title='Delete only when campaign schedule is in PLANNED status' @if($schedule->status == 1)  class='deleteSchedule' @endif data-scheduleid={{$schedule->id}} ><i class="fa fa-trash"></i></a>
                                        | <a style="cursor:pointer;color:red;"  title='Abort only when campaign schedule is not in PLANNED & ABORT status'
                                         @if($schedule->status != 1 && $schedule->status != 7) class='abortSchedule' @endif data-scheduleid={{$schedule->id}} ><i class="fa fa-stop"></i></a>
                                     </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>

                        </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->

            </div>
            <!-- /.row -->


            <div class="modal fade" id="deleteScheduleModal" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content"  style="background-color: #d33724 !important;color:white;">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="add-edit"></h4>
                            </div>
                            <form method="post" name="add-ip-form" action="">
                                @csrf

                                <div class="modal-body">
                                <p>You are about to delete <b><i class="title"></i></b> Schedule Campaign.</p>
                                <p>Do you want to proceed?</p>
                                <input type="hidden" readonly class="form-control" minlength="7" placeholder="" name="scheduled_id" id="scheduled_id">
                                    <!-- pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" -->
                                    <div id="schedule-delete"></div>
                            </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <button type="submit" name="submit" id="deleteSchedule" class="btn btn-danger btn-ok">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="abortScheduleModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content"  style="background-color: #d33724 !important;color:white;">
                        <div class="modal-header">
                            <button type="button" class="close"data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="add-edit">Abort Marketing Schedule Campaign</h4>
                            </div>
                            <form method="post" name="add-ip-form" action="">
                                @csrf
                                <div class="modal-body">
                                    <p>You are about to <b>ABORT</b> <b><i class="title"></i></b> Schedule Campaign.</p>
                                    <p>Do you want to proceed?</p>
                                    <input type="hidden" readonly class="form-control" minlength="7" placeholder="" name="schedule_id" id="schedule_id">
                                    <div id="schedule-delete"></div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <button type="submit" name="submit" id="abortSchedule" class="btn btn-danger btn-ok">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <!-- /.content -->


    </div>
    <!-- /.content-wrapper -->

@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script language="javascript">
    $(document).ready(function() {

        $(".utc-time").each(function(i, element) {
            var utcTime = $(element).data("utc-time");
            if (utcTime) {
                var localTime = moment.utc(utcTime).local();
                $(element).html(localTime.format('YYYY-MM-DD HH:mm'));
            }
        });

        var oTable = $('#example').dataTable({
            "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 2,3 ] }
            ]
        });
    });

    $(document).on("click", ".abortSchedule", function () {
        $("#abortScheduleModal").modal();
        var scheduled_id = $(this).data('scheduleid');
        $("#schedule_id").val(scheduled_id);
    });

    $(document).on("click", "#abortSchedule", function (e) {
        e.preventDefault();
        postData = {
            "_token": $("#user-role-csrf").val(),
            "scheduleId": parseInt($("#schedule_id").val())
        };
        console.log(postData);
        $.ajax({
            type: "POST",
            url: "{{ route('abortSchedule') }}",
            data: postData,
            success: function(data){
                console.log(data);
                $("#schedule-cancel").click();
                location.reload();
            },
            error: function(error){
                console.log(error.responseJSON);
                $("#schedule-delete").html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
                $("#schedule-delete").show();
            }
        });
    });

    $(document).on("click", ".deleteSchedule", function () {
        $("#deleteScheduleModal").modal();
        $("#add-edit").html('Schedule Campaign');
        var scheduled_id = $(this).data('scheduleid');
        $("#scheduled_id").val(scheduled_id);
    });

    $(document).on("click", "#deleteSchedule", function (e) {
        e.preventDefault();
        postData = {
            "_token": $("#user-role-csrf").val(),
            "scheduleId": parseInt($("#scheduled_id").val())
        };
        console.log(postData);
        $.ajax({
            type: "POST",
            url: "{{ route('deleteSchedule') }}",
            data: postData,
            success: function(data){
                console.log(data);
                $("#schedule-cancel").click();
                location.reload();
            },
            error: function(error){
                console.log(error.responseJSON);
                $("#schedule-delete").html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
                $("#schedule-delete").show();
            }
        });
    });

</script>

@endpush
