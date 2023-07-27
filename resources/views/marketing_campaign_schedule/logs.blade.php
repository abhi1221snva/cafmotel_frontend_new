@extends('layouts.app')
@push('styles')
    <style type="text/css">
        .centered-body {
            text-align: center;
            padding: 2px;
        }
    </style>
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <section class="content-header">
                <h1>
                   <b>Schedule Logs</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Marketing Campaigns</li>
                    
                    <li class="active">Schedule Logs</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                 <a href="{{ url("/marketing-campaign/{$schedule->campaign_id}/schedules") }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Back to schedules</a>
           </div>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-info">
                        <div class="box-header">
                            <form method="GET" action="" id="search" name="search">
                            <div class="form-group m-b-10">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        @if($schedule->send == 1)
                                            <label>Email</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-envelope"></i>
                                                </div>
                                                <input type="text" class="form-control" placeholder="Email" name="send_to" value="{{ request()->input('send_to') }}" id="send_to" />
                                            </div>
                                            <!-- /.input group -->
                                        @elseif ($schedule->send == 2)
                                            <label>Phone</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-phone"> {{$schedule->sms_country_code}}</i>
                                                </div>
                                                <input type="hidden" name="country_code" value="{{$schedule->sms_country_code}}" />
                                                <input type="text" class="form-control" placeholder="Phone" name="send_to" value="{{ request()->input('send_to') }}" id="send_to" />
                                            </div>
                                            <!-- /.input group -->
                                        @endif
                                    </div>
                                    <!-- /.form group -->
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="run_status">Status</label>
                                        <div class="input-group">
                                            <select name="run_status" class="form-control" id="run_status">
                                                <option value="">Select</option>
                                                <option @if(request()->input('run_status') == 1)  selected @endif value="1">Queued</option>
                                                <option @if(request()->input('run_status') == 2)  selected @endif value="2">Processing</option>
                                                <option @if(request()->input('run_status') == 3)  selected @endif value="3">Sent</option>
                                                <option @if(request()->input('run_status') == 4)  selected @endif value="4">Aborted</option>
                                                <option @if(request()->input('run_status') == 5)  selected @endif value="5">Failed</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                </div>
                                <div class="col-md-2">
                                </div>
                                <div class="col-md-2">
                                    <label for="status"> </label>
                                    <div class="form-group">
                                        <input type="submit" name="search" id="search" value="Search" class="btn btn-info" />
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="schedule-runs" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@if($schedule->send == 1) Email @elseif ($schedule->send == 2) Phone @endif</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Attempted At</th>
                                    <th>Sent At</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="result">
                                @php $count = $records->from @endphp
                                    @forelse($records->data as $record)
                                        <tr>
                                            <td>{{ $count++ }}</td>
                                            <td>{{ substr($record->send_to, strlen($schedule->sms_country_code), strlen($record->send_to)) }}</td>
                                            <td class="utc-time" data-utc-time="{{$record->scheduled_time}}"></td>
                                            <td>
                                                @if($record->status == 1)
                                                    <span class="label label-info">Queued</span>
                                                @elseif($record->status == 2)
                                                    <span class="label label-info">Processing</span>
                                                @elseif($record->status == 3)
                                                    <span class="label label-success">Sent</span>
                                                @elseif($record->status == 4)
                                                    <span class="label label-warning">Aborted</span>
                                                @elseif($record->status == 5)
                                                    <span class="label label-danger">Failed</span>
                                                @endif
                                            </td>
                                            <td class="utc-time" data-utc-time="{{$record->start_time}}"></td>
                                            <td class="utc-time" data-utc-time="{{$record->sent_time}}"></td>
                                            <td>
                                                @if(in_array($record->status, [2, 4, 5]))
                                                    <a style="cursor:pointer;color:green;" href="javascript:retry('{{ $record->id }}', '{{ $record->send_to}}');" title="Retry"><i class="fa fa-repeat fa-lg"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">No entries found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            @php
                                $path = \Request::getRequestUri();
                                $paths = explode("?", $path);
                                $paginator = new Illuminate\Pagination\LengthAwarePaginator($records, $records->total, $records->per_page, $records->current_page, ['path' => $paths[0]]);
                            @endphp
                            @if($records->total > 0)
                                <div>
                                    <span style="float: left;"><br/>Showing {{$records->from}} to {{$records->to}} of {{$records->total}} records</span>
                                    <span style="float: right">{{$paginator->appends(Request::all())->links()}}</span>
                                </div>
                            @endif
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
    </div>
    <!-- /.content-wrapper -->

    <div class="modal fade" id="confirm">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirm</h4>
                </div>
                <div class="modal-body">
                    <p>Please confirm you want to retry <b><span id="record-sent-to"></span></b>.</p>
                    <div id="message">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="record-id" value="" />
                    <button type="button" data-dismiss="modal" class="btn btn-success" id="btn-retry">Retry</button>
                    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            $(".utc-time").each(function(i, element) {
                var utcTime = $(element).data("utc-time");
                if (utcTime) {
                    var localTime = moment.utc(utcTime).local();
                    $(element).html(localTime.format('YYYY-MM-DD HH:mm'));
                }
            });

            //Pass false to the callback function
            $(".btn-no").click(function () {
                handler(false);
                $("#confirm").modal("hide");
            });

            $('#btn-retry').click(function (e) {
                $("#confirm").modal("hide");
                $.ajax({
                    type: "POST",
                    url: "/marketing-campaign/{{$schedule->campaign_id}}/schedule/{{$schedule->id}}/log/" + $('#record-id').val() + "/retry",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        if (data.success) {
                            $("#alert-success").html(data.message).show();
                            setTimeout(function(){
                                window.location.reload(1);
                            }, 2000);
                        } else {
                            var message = data.message;
                            $.each(data.errors, function( key, value ) {
                                message = message + "<br/>" + value;
                            });
                            $("#alert-errors").html(message).show();
                        }
                    },
                    error: function (xhr, status, error) {
                        $("#alert-errors").html(error).show();
                    }
                });
            });
        });

        function retry(recordId, recordSendTo) {
            $('#btn-retry').show();
            $('#record-id').val(recordId);
            $('#record-sent-to').html(recordSendTo);
            $('#confirm').modal('show');
        }
    </script>
@endpush

