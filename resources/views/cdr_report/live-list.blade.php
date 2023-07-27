@extends('layouts.app')

@section('content')
<?php
use \App\Http\Controllers\InheritApiController;
$userdetails = InheritApiController::headerUserDetails();
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->


     <section class="content-header">
                <h1>
                    <b>Live Call List</b> ( <i class="fa fa-refresh" aria-hidden="true"></i> refreshing in <span id="timer"></span> sec )
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> Call Data Reports</li>
                    <li class="active"> Live Call List</li>
                </ol>
        </section>
   
   

    <!-- Main content -->
    <section class="content">
        <div class="row">
        
            <div class="col-xs-12">
                <div class="box">
                    
                    <div class="box-body">
					<form class="form-inline form-dialer" method="post">
                                @csrf
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                               
                                <th>Agent Name(Extension)</th>
                                <th>Number</th>
                                <th>Route</th>
                                <th>Campaign Name</th>
                                <th>Call Type</th>
                                <th>Start Time</th>
                                <th>Duration</th>
                                <th>Action</th>



                                
                                
                               <!-- <th>Campaign</th>
                               
                               <th>Action</th> -->


                            </tr>
                            </thead>

                            <tbody>
								<input type="hidden" name="csrf-token" id="csrf-token" value="{{ csrf_token() }}"/>
                                <?php 

                                if(!empty($report)){
                                $k=0;
                                foreach ($report as $key=>$value){
                                    $timezone = $userdetails->data->timezone;
                                    if(!empty($timezone))
                                    {
                                        $utc = $value->start_time;
                                        $dt = new DateTime($utc);
                                        $tz = new DateTimeZone($timezone); // or whatever zone you're after
                                        $dt->setTimezone($tz);
                                        $start_time = $dt->format('Y-m-d H:i:s');
                                    }
                                    else
                                    {
                                        $start_time = $value->start_time;
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo ++$k; ?></td>
                                        <td>@foreach($extension_list as $extension)
                                            @if($value->extension == $extension->extension)
                                                {{$extension->first_name}} {{$extension->last_name}}  ({{$extension->extension}}) 
                                            @elseif($value->extension == $extension->alt_extension)
                                                {{$extension->first_name}} {{$extension->last_name}}  ({{$extension->extension}}) 
                                            @endif
                                        @endforeach</td>
                                        <td><?php echo $value->number;?></td>
                                        
                                        <td><?php  if($value->route == 'IN') { echo 'Inbound'; } else if($value->route == 'OUT') { echo "Outbound"; }?></td>
                                        <td>
                                            @if(empty($value->campaign_id))
                                            N/A
                                            @else
                                            @foreach($campaign_list as $campaign)
                                            @if($campaign->id == $value->campaign_id)
                                            {{$campaign->title}}
                                            @endif
                                            @endforeach
                                            @endif
                                        </td>
                                        <td><?php echo ucfirst($value->type);?></td>
                                        
                                        <td><?php echo $start_time;?></td>


                                         <td><?php  echo substr($value->duration,1); ?></td>
                                        <td>
										<a type="button" data-barge_id='{{$value->id}}' class="btn btn-primary barge_call_btn">Barge</a> 
                                        <a type="button" data-listen_id='{{$value->id}}' data-call_type="{{$value->type}}" class="btn btn-primary listen_call_btn">Listen</a></td>
                                        
                                    </tr>
                                <?php } }

                                else { ?>

                                    <tr><td style="color:red;">No Live Calls found</td></tr>
                                <?php }?>
                            </tbody>
                        </table>
                         </form>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>
<script>
/*$('.listen_call_btn').on('click', function(){
	$.ajax({
		type: 'POST',
		url: '/listen-call',
		data: {			
			listen_id: $(this).data('listen_id'),
			call_type: $(this).data('call_type'),
			"_token": "{{ csrf_token() }}",
		},
		success: function (data) {
			var res = $.parseJSON(data);
			if (res.status == 'true') {
				$(".listen_call_btn :input").attr("disabled", true);

			}
		}
	});
});*/


$(document).on('click', '.listen_call_btn', function (e)
{
    const swalWithBootstrapButtons = Swal.mixin({
        customClass:{
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },

        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'Which phone do you want to use to make it listen',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Primary Phone',
        cancelButtonText: 'Webphone',
        reverseButtons: true
    }).then((result) =>
    {
        if (result.isConfirmed)
        {
            var extension = "<?= Session::get('extension'); ?>";
            $.ajax({
                type: 'POST',
                url: '/listen-call',
                data:
                {         
                    extension: extension,
                    listen_id: $(this).data('listen_id'),
                    call_type: $(this).data('call_type'),
                    "_token": "{{ csrf_token() }}",
                },
                success: function (data)
                {
                    var res = $.parseJSON(data);
                    if (res.status == 'true')
                    {
                        $(".listen_call_btn :input").attr("disabled", true);
                    }
                }
            });
        }

        else if(result.dismiss === Swal.DismissReason.cancel)
        {
            var extension = "<?= Session::get('private_identity'); ?>";
            $.ajax({
                type: 'POST',
                url: '/listen-call',
                data: 
                {         
                    extension: extension,
                    listen_id: $(this).data('listen_id'),
                    call_type: $(this).data('call_type'),
                    "_token": "{{ csrf_token() }}",
                },

                success: function (data)
                {
                    var res = $.parseJSON(data);
                    if (res.status == 'true')
                    {
                        $(".listen_call_btn :input").attr("disabled", true);
                    }
                    else if(res.status == false)
                    {
                        Swal.fire(res.message);
                    }
                }
            });
        }
    })
});



/*$('.barge_call_btn').on('click', function(){
	$.ajax({
		type: 'POST',
		url: '/barge-call',
		data: {			
			listen_id: $(this).data('barge_id'),
			call_type: $(this).data('call_type'),
			"_token": "{{ csrf_token() }}",
		},
		success: function (data) {
			var res = $.parseJSON(data);
			if (res.status == 'true') {
				$(".barge_call_btn :input").attr("disabled", true);

			}
		}
	});
});*/

$(document).on('click', '.barge_call_btn', function (e)
{
    const swalWithBootstrapButtons = Swal.mixin({
        customClass:{
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },

        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'Which phone do you want to use to make it barge',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Primary Phone',
        cancelButtonText: 'Webphone',
        reverseButtons: true
    }).then((result) =>
    {
        if (result.isConfirmed)
        {
            var extension = "<?= Session::get('extension'); ?>";
            $.ajax(
            {
                type: 'POST',
                url: '/barge-call',
                data: 
                {       
                    extension: extension,  
                    listen_id: $(this).data('barge_id'),
                    call_type: $(this).data('call_type'),
                    "_token": "{{ csrf_token() }}",
                },

                success: function (data)
                {
                    var res = $.parseJSON(data);
                    if (res.status == 'true')
                    {
                        $(".barge_call_btn :input").attr("disabled", true);
                    }
                }
            });
        }

        else if(result.dismiss === Swal.DismissReason.cancel)
        {
            var extension = "<?= Session::get('private_identity'); ?>";

            $.ajax(
            {
                type: 'POST',
                url: '/barge-call',
                data: 
                {       
                    extension: extension,  
                    listen_id: $(this).data('barge_id'),
                    call_type: $(this).data('call_type'),
                    "_token": "{{ csrf_token() }}",
                },

                success: function (data)
                {
                    var res = $.parseJSON(data);
                    if (res.status == 'true')
                    {
                        $(".listen_call_btn :input").attr("disabled", true);
                    }
                    else if(res.status == false)
                    {
                        Swal.fire(res.message);
                    }
                }
            });
        }
    })
});
</script>
<!-- /.content-wrapper -->

<script>
    var counter = 6;
    var interval = setInterval(function()
    {
        counter--;
        $("#timer").html(counter);
        if (counter == 0)
        {
            clearInterval(interval);
            window.location.reload();
        }
    },1000);

</script>
@endsection