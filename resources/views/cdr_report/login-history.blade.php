@extends('layouts.app')
<?php
    if (!empty(request()->input('start_date')))
    {
        $startDate = request()->input('start_date');
    }
    else
    {
        $current_date = date("Y-m-d"); 
        $str_date = strtotime(date("Y-m-d", strtotime($current_date)) . " -15 day");
        $startDate = date('Y-m-d', $str_date);
    }

    if (!empty(request()->input('end_date')))
    {
        $endDate = request()->input('end_date');
    }
    else
    {
        $endDate = date('Y-m-d');
    }

    $url_page = explode('?',str_replace('/','',$_SERVER['REQUEST_URI']));
    $url = $url_page[0];
?>
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
<style>
    .audiojs .scrubber { 
display: none; 
} 
</style>
    <section class="content-header">
                <h1>
                     <b>Login History</b>
                    
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> Call Data Reports</li>

                    <li class="active"> Login History</li>
                </ol>
        </section>
    

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">

                        <form class="needs-validation" action="" method="post">
                            @csrf



          


                             <div class="col-md-4">
                                <div class="form-group">
                                    
                                        <label>Date range: <i data-toggle="tooltip" data-placement="right" title="Select date range for call report" class="fa fa-info-circle" aria-hidden="true"></i></label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="cdr-range">
                                            <input type="hidden" id="start_date" name="start_date" value="{{ $startDate }}">
                                            <input type="hidden" id="end_date" name="end_date" value="{{ $endDate }}">
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <!-- /.form group -->
                                </div>

                                

                               
                               
                               
                            </div>

                            <div class="col-md-6"><div>

                                        </div>
                                    </div>
                                    <div style="clear: both"></div>
                                <div class="modal-footer">
                                    <!--  <input type="hidden" name="extension" value="" id="extension-edit-id"/> -->
                                    <button type="submit" name="submit" value="Search" class="btn btn btn-primary waves-effect waves-light"><i class="fa fa-search" aria-hidden="true"></i> Search</button>

                                    <!-- <button type="submit" name="submit_download" class="btn btn-danger waves-effect waves-light m-l-10" value="1"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</button>                                    
                                     <button type="submit" name="submit_download" class="btn btn-success waves-effect waves-light m-l-10" value="2"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</button> -->
                                </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
            <?php
            if (!empty($report)) {
                if ($lower_limit == '0')
            {
                $lower_limit = 0;
            }

            if ($lower_limit > 0)
            {
                //$lower_limit = $lower_limit - 10;
            }

            if($page == 1)
            {
                $currentPage = 1;
            }

            else
            {
                $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            }

            $perPage = 10;
            $paginator = new Illuminate\Pagination\LengthAwarePaginator($report, $record_count, $perPage, $currentPage, ['path' => url($url)]);
        ?>

                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <b>Total Rows :<?= $record_count ?></b>
                            <div class="table-responsive">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User Name</th>
                                        <th>IP</th>
                                        <th>User Agent</th>
                                        <th>Logged In</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $k = $lower_limit;
                                    foreach ($paginator->items() as $key => $value) {

                                                /*$timezone = $userdetails->data->timezone;
                                                if(!empty($timezone))
                                                {
                                                    $utc_start_time = $value->start_time;
                                                    $dt_start_time = new DateTime($utc_start_time);
                                                    $tz = new DateTimeZone($timezone); // or whatever zone you're after
                                                    $dt_start_time->setTimezone($tz);
                                                    $start_time = $dt_start_time->format('Y-m-d H:i:s');

                                                    $utc_end_time = $value->end_time;
                                                    $dt_end_time = new DateTime($utc_end_time);
                                                    $tz = new DateTimeZone($timezone); // or whatever zone you're after
                                                    $dt_end_time->setTimezone($tz);    
                                                    $end_time = $dt_end_time->format('Y-m-d H:i:s');

                                                }
                                                else
                                                {
                                                    $start_time = $value->start_time;
                                                    $end_time = $value->end_time;

                                                }*/
                                                
                                        ?>
                                        <tr>
                                            <td><?php echo ++$k; ?></td>
                                            <td><?php echo $value->first_name.' '.$value->last_name; ?></td>

                                            

                                            
                                            <td>
                                            <?php
                                            if(!empty($value->ip)) 
                                                echo $value->ip;
                                                else
                                                echo 'N/A'; 
                                            ?>
                                            </td>
                                            <td>{{$value->user_agent}}</td>

                                            <td>{{$value->created_at}}</td>
                                            
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                 {{$paginator->appends(Request::all())->links()}}

                <div>Showing {{($paginator->currentpage()-1)*$paginator->perpage()+1}} to {{$paginator->currentpage()*$paginator->perpage()}} of  {{$paginator->total()}} entries </div>
                            </table>
                        </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            <?php } ?>
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{asset('asset/plugins/daterangepicker/daterangepicker-bs3.css') }}">
    <style>
        .margin-10{
            margin-right: 5%;
        }
        .marginTop-2{
            margin-top: 2%;
        }

        .width_fix{
            width: 195px;
        }
    </style>
@endpush
@push('scripts')
<script>
    var myAudio = document.getElementById("myAudio");
var isPlaying = false;

function togglePlay(valued) {
    $("#pause_"+valued).show();
    $("#play_"+valued).hide();

  isPlaying ? myAudio.pause() : myAudio.play();
};

function togglePause(valued) {
    $("#pause_"+valued).hide();
    $("#play_"+valued).show();

 myAudio.pause();
};

myAudio.onplaying = function() {
  isPlaying = true;
};
myAudio.onpause = function() {
  isPlaying = false;
};
</script>
<script src="{{asset('asset/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{asset('asset/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{asset('asset/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
    <script>
        $(document).ready(function () {
        $('#mobile').inputmask("(999) 999-9999");
    })
    </script>
    <script src="{{asset('asset/plugins/dashboard_date/moment.min.js') }}"></script>
    <script src="{{asset('asset/plugins/dashboard_date/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('asset/plugins/dashboard_date/daterangepicker.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $('#cdr-range').daterangepicker({
                locale: { format: 'YYYY-MM-DD'},
                "startDate": "{{ $startDate }}",
                "endDate": "{{ $endDate }}"
            }, function(start, end, label) {
                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                $("#start_date").val(start.format('YYYY-MM-DD'));
                $("#end_date").val(end.format('YYYY-MM-DD'));
            });
        });
    </script>
@endpush
