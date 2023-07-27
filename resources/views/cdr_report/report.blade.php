@extends('layouts.app')
<?php
use \App\Http\Controllers\InheritApiController;
$userdetails = InheritApiController::headerUserDetails();
   

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
                     <b>Call Reports</b>
                    
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> Call Data Reports</li>

                    <li class="active"> Call Reports</li>
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
                                    <label for="validationTooltip01">Phone No <i data-toggle="tooltip" data-placement="right" title="Type phone number" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <input type="text" class="form-control" placeholder="Enter Phone Number" onkeypress="return isNumberKey(event)" type="" name="number" value="{{ request()->input('number') }}" id="mobile" maxlength="" >
                                </div>
                            </div>
                                @if(Session::get('level') >= 5)
                                <div class="col-md-4">
                                <div class="form-group">
                                    <label for="validationTooltip02">Extension List <i data-toggle="tooltip" data-placement="right" title="Select extension list from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <select class="form-control" name="extension" id="extension">
                                        <option value="">Select</option>
                                        @foreach($extension_list as $key => $extension)

                                        @if((request()->session()->get('level') > 9))
                                        @if(($extension->user_level <= 9) || ($extension->extension == request()->session()->get('extension')))
                                        <option @if($extension->extension == request()->input('extension'))  selected @endif value="{{$extension->extension}}">{{$extension->first_name}} {{$extension->last_name}} - {{$extension->extension}} @if($extension->is_deleted == 1) (Removed User) @endif</option>
                                        @endif

                                        @elseif(($extension->user_level < 9) || ($extension->extension == request()->session()->get('extension')))
                                        <option @if($extension->extension == request()->input('extension'))  selected @endif value="{{$extension->extension}}">{{$extension->first_name}} {{$extension->last_name}} - {{$extension->extension}} @if($extension->is_deleted == 1) (Removed User) @endif</option>
                                        @endif


                                        @endforeach
                                    </select>
                                </div>
                            </div>

                                <div class="col-md-4">
                                <div class="form-group">
                                    <label for="validationTooltipUsername">Campaign <i data-toggle="tooltip" data-placement="right" title="Select campaign list from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <select name="campaign" class="form-control" id="campaign" onchange="getDisposition();">
                                            <option value="">Select</option>
                                            @foreach($campaign_list as $key => $campaign)
                                            @if(!empty($campaign->title))
                                            <option @if($campaign->id == request()->input('campaign'))  selected @endif value="{{$campaign->id}}">{{$campaign->title}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                                                        </div>
                                                                    </div>
                                @else
                                <div class="col-md-4">
                                <div class="form-group">
                                    <label for="validationTooltipUsername">Campaign <i data-toggle="tooltip" data-placement="right" title="Select campaign list from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <select name="campaign" class="form-control" id="campaign" onchange="getDisposition();">
                                            <option value="">Select</option>
                                            @foreach($campaign_list as $key => $campaign)
                                            @if(!empty($campaign->title))
                                            <option @if($campaign->id == request()->input('campaign'))  selected @endif value="{{$campaign->id}}">{{$campaign->title}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        </div>
                                </div>
                                @endif
                           

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="validationTooltip01">Disposition <i data-toggle="tooltip" data-placement="right" title="Select disposition list from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <select name="disposition" class="form-control" id="disposition"  >
                                        <option value="">Select Disposition</option>
                                        <option @if(request()->input('disposition') == '0')   selected @endif  value="0">No Agent</option>
                                        <option @if(request()->input('disposition') == '101')   selected @endif  value="101">No Agent Available</option>
                                        <option @if(request()->input('disposition') == '102')   selected @endif  value="102">AMD Hangup</option>
                                        <option @if(request()->input('disposition') == '103')   selected @endif  value="103">Voice Drop</option>
                                        <option @if(request()->input('disposition') == '104')   selected @endif  value="104">Cancelled By User</option>
                                        <option @if(request()->input('disposition') == '105')   selected @endif  value="105">Channel Unavailable</option>
                                        <option @if(request()->input('disposition') == '106')   selected @endif  value="106">Congestion</option>
                                        <option @if(request()->input('disposition') == '107')   selected @endif  value="107">Line Busy</option>

                                        @foreach($disposition_list as $key => $disposition)
                                        <option @if($disposition->id == request()->input('disposition'))  selected @endif value="{{$disposition->id}}">{{$disposition->title}}</option>
                                        @endforeach



                                    </select>
                                </div>
                            </div>

                                <div class="col-md-4">
                                <div class="form-group">
                                    <label for="validationTooltip02">Route <i data-toggle="tooltip" data-placement="right" title="Select route from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <select name="route" class="form-control" id="route">
                                        <option value="">Select</option>
                                        <option @if(request()->input('route') == 'IN')  selected @endif value="IN">IN</option>
                                        <option @if(request()->input('route') == 'OUT')  selected @endif value="OUT">OUT</option>
                                        <option @if(request()->input('route') == 'TRANSFER')  selected @endif value="TRANSFER">TRANSFER</option>
                                    </select>
                                    <div class="valid-tooltip"></div>
                                </div>
                            </div>


                                <div class="col-md-4">
                                <div class="form-group">
                                    <label for="validationTooltipUsername">Type <i data-toggle="tooltip" data-placement="right" title="Select type from dowp down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <select name="type" class="form-control" id="type" onchange="getDisposition();">
                                            <option value="">Type</option>
                                            <option @if(request()->input('type') == 'dialer')  selected @endif value="dialer">Dialer</option>
                                            <option @if(request()->input('type') == 'c2c')  selected @endif value="c2c">C2C</option>
                                            <option @if(request()->input('type') == 'manual')  selected @endif value="manual">Manual</option>

                                            <option @if(request()->input('type') == 'predictive_dial')  selected @endif value="predictive_dial">Predictive Dial</option>
                                        </select>
                                        
                                </div>
                            </div>

                                @if(Session::get('level') >= 5)

 <div class="col-md-4">
                                <div class="form-group">
<div class="form-group" data-select2-id="13">
                <label for="validationTooltipUsername">DID Lists <i data-toggle="tooltip" data-placement="right" title="Select type from dowp down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                <select class="form-control select2 select2-hidden-accessible" multiple="" data-placeholder="Select a DIDs" style="width: 100%;" data-select2-id="7" tabindex="-1" aria-hidden="true" name="did_numbers[]">
                  <option value="">Select</option>
    @foreach($did_list as $key => $did)
                                            
<option @if (old("did_numbers")){{ (in_array($did->cli, old("did_numbers")) ? "selected":"") }}@endif value="{{$did->cli}}">{{$did->cli}}</option>

@endforeach
                </select>
              </div>

          </div>
      </div>

          @endif

            <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Timezone <i data-toggle="tooltip" data-placement="right" title="Select Call Report By Timezone" class="fa fa-info-circle" aria-hidden="true"></i></label>

                                        <select name="timezone_value" class="form-control">
                                            <option value="">Select TimeZone</option>
                                            @foreach($timezone_lists as $key => $zone)
                                            <option @if($zone->timezone == request()->input('timezone_value'))  selected @endif value="{{$zone->timezone}}">{{$zone->timezone_name}} ({{$zone->timezone}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


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

                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label>State / City / Area Codes <i data-toggle="tooltip" data-placement="right" title="Select Area code for call report" class="fa fa-info-circle" aria-hidden="true"></i></label>

                                        <select name="area_code[]" class="form-control select2 select2-hidden-accessible" multiple="" data-placeholder="Select State / City /Areacode" style="width: 100%;" data-select2-id="8" tabindex="-1" aria-hidden="true">
                                            <option value="">Select Area Code</option>
                                            @foreach($area_codes as $key => $area)
                                            <option @if($area->areacode == request()->input('area_code'))  selected @endif value="{{$area->areacode}}">{{$area->state_name}} - {{$area->city_name}} - {{$area->areacode}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                               
                               
                               
                            </div>

                            <div class="col-md-6"><div>

                                        </div>
                                    </div>
                                    <div style="clear: both"></div>
                                <div class="modal-footer">
                                    <!--  <input type="hidden" name="extension" value="" id="extension-edit-id"/> -->
                                    <button type="submit" name="submit" value="Search" class="btn btn btn-primary waves-effect waves-light"><i class="fa fa-search" aria-hidden="true"></i> Search</button>

                                     <button type="submit" name="submit_download" class="btn btn-danger waves-effect waves-light m-l-10" value="1"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</button>                                     
                                     <button type="submit" name="submit_download" class="btn btn-success waves-effect waves-light m-l-10" value="2"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</button>
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
                                        <th>Extension</th>
                                        <th>Campaign</th>
                                        <th>CLI</th>
                                        <th>Route</th>
                                        <th>Type</th>
                                        <th>Number</th>
                                        <th>Disposition</th>
                                        <th>Duration</th>
                                        <th>State / City</th>

                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Recording</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $k = $lower_limit;
                                    foreach ($paginator->items() as $key => $value) {

                                                $timezone = $userdetails->data->timezone;
                                                if(!empty($timezone))
                                                {
                                                    if(!empty($value->start_time))
                                                    {
                                                    $utc_start_time = $value->start_time;
                                                    $dt_start_time = new DateTime($utc_start_time);
                                                    $tz = new DateTimeZone($timezone); // or whatever zone you're after
                                                    $dt_start_time->setTimezone($tz);
                                                    $start_time = $dt_start_time->format('Y-m-d H:i:s');

                                                    }
                                                    else
                                                    {
                                                        $start_time='-';
                                                    }

                                                    if(!empty($value->end_time))
                                                    {

                                                    $utc_end_time = $value->end_time;
                                                    $dt_end_time = new DateTime($utc_end_time);
                                                    $tz = new DateTimeZone($timezone); // or whatever zone you're after
                                                    $dt_end_time->setTimezone($tz);    
                                                    $end_time = $dt_end_time->format('Y-m-d H:i:s');
                                                }
                                                else
                                                {
                                                        $end_time='-';

                                                }

                                                }
                                                else
                                                {
                                                    if(!empty($value->start_time))
                                                    {
                                                    $start_time = $value->start_time;
                                                }
                                                else
                                                {
                                                    $start_time='-';
                                                }

                                            if(!empty($value->end_time))
                                                    {
                                                    $end_time = $value->end_time;
                                                }
                                                else
                                                {
                                                    $end_time='-';
                                                }

                                                }
                                                
                                        ?>
                                        <tr>
                                            <td><?php echo ++$k; ?></td>
                                            <td>
                                            <?php 
                                            foreach($extension_list as $key => $extension)
                                            {
                                                if($extension->extension == $value->extension)
                                                {
                                                    $ext_name = $extension->first_name.' '.$extension->last_name.'-'.$value->extension;
                                                }
                                                else
                                                if($extension->alt_extension == $value->extension)
                                                {
                                                    $ext_name = $extension->first_name.' '.$extension->last_name.'-'.$value->extension;
                                                }

                                                else
                                                if($value->extension == NULL)
                                                {
                                                    $ext_name = 'N/A';
                                                }
                                                else
                                                {
                                                    $num = $value->extension;
                                                    $numlength = strlen((string)$num);
                                                    if($numlength > 9)
                                                    {
                                                        $ext_name = $value->extension;
                                                    }
                                                }

                                            }
                                            echo $ext_name;
                                            ?>
                                        </td>

                                            <td><?php
                                                if (!empty($value->campaign_id)) {
                                                    foreach ($campaign_list as $key => $campaign) {
                                                        if ($campaign->id == $value->campaign_id) {
                                                            ?>
                                                            <?php
                                                            if (!empty($campaign->title)) {
                                                                echo $campaign->title;
                                                            }
                                                            ?>
                                                            <?php
                                                        }
                                                    }
                                                } else {
                                                    echo 'N/A';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                            <?php
                                            if(!empty($value->cli)) 
                                                echo $value->cli;
                                                else
                                                echo 'N/A'; 
                                            ?>
                                            </td>
                                            <td><?php echo $value->route; ?></td>
                                            <td><?php 
                                                if($value->type == 'manual')
                                                    $dial = 'Manual';
                                                else
                                                if($value->type == 'dialer')
                                                    $dial = 'Dialer';
                                                else
                                                if($value->type == 'predictive_dial')
                                                    $dial = 'Predictive';

                                                else
                                                if($value->type == 'c2c')
                                                    $dial = 'C2C';

                                                else
                                                if($value->type == 'outbound_ai')
                                                    $dial = 'Outbound';
                                                echo $dial; ?>
                                                    
                                                </td>

                                            <td><?php echo $value->number; ?></td>
                                            <td><?php
                                                if (!empty($value->disposition_id)) {
                                                    // echo "<pre>";print_r($disposition_list);die;
                                                    foreach ($disposition_list as $key => $dispo) {
                                                        if ($dispo->id == $value->disposition_id) {
                                                            ?>
                                                            <?php echo $dispo->title; ?>
                                                            <?php
                                                        }
                                                       else
                                                        if($value->disposition_id == '101')
                                                        {
                                                            echo "No Agent Available";
                                                            break;
                                                        }
                                                    
                                                        else
                                                         if($value->disposition_id == '102')
                                                        {
                                                            echo "AMD Hangup";
                                                            break;
                                                        }
                                                    
                                                        else
                                                         if($value->disposition_id == '103')
                                                        {
                                                            echo "Voice Drop";
                                                            break;
                                                        }

                                                         else
                                                         if($value->disposition_id == '104')
                                                        {
                                                            echo "Cancelled By User";
                                                            break;
                                                        }

                                                        else
                                                            if($value->disposition_id == '105')
                                                            {
                                                                $disposition_name = "Channel Unavailable";
                                                                break;
                                                            }
                                                            else
                                                            if($value->disposition_id == '106')
                                                            {
                                                                $disposition_name = "Congestion";
                                                                break;
                                                            }
                                                            else
                                                            if($value->disposition_id == '107')
                                                            {
                                                                $disposition_name = "Line Busy";
                                                                break;
                                                            }

                                                    }
                                                } else {
                                                    echo 'N/A';
                                                }
                                                ?></td>

                                            <td><?php echo gmdate("H:i:s", $value->duration); ?></td>
                                            <td>
                                                @foreach($area_codes as $key => $area)
                                                @if($area->areacode == $value->area_code)
                                                {{$area->state_name}} / {{$area->city_name}}
                                                
                                                    @endif
                                                @endforeach
                                            </td>

                                            <td><?php echo $start_time; ?></td>
                                            <td><?php echo $end_time;?></td>
                                            <td><audio controls preload ='none'><source src="<?php echo $value->call_recording; ?>" type='audio/wav'></audio></td>

                                               <?php /*?> <td style="cursor: pointer;padding: 8px 30px;"><audio  id="myAudio" src="<?php echo $value->call_recording; ?>" type='audio/wav' preload="auto"></audio><a id="play_{{$value->id}}" onClick="togglePlay({{$value->id}})"><i class="fa fa-play" aria-hidden="true"></i></a>

                                                    <a style="display: none;" id="pause_{{$value->id}}" onClick="togglePause({{$value->id}})"><i class="fa fa-pause" aria-hidden="true"></i></a></td><?php */ ?>
                                            <!-- <td>
                                            <?php if (!empty($value->lead_id)) { ?>
                                                                                                            <a href="lead_detail.php?id=<?php echo $value->lead_id; ?>" target="_blank"><span class="glyphicon glyphicon-search"></span></a>
                                            <?php } else { ?>
                                                                                                            NA
                                            <?php } ?>
                                            </td> -->
                                            <td><a target="_blank" title="View Lead Record" href="/lead-activity?phone_number=<?php echo $value->number; ?>"><i style="margin-top: 20px;margin-left: 10px;" class="fa fa-eye" aria-hidden="true"></i></a></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                {{$paginator->appends(Request::all())->links()}}
                                <!-- <?= $paginator->render() ?> -->
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
