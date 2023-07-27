@extends('layouts.app')
@section('title', 'Active Plan List')
@section('content')

     <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
                <h1>
                   <b>Active Plan List</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Subscription</li>
                    
                    <li class="active">Active Plan List</li>
                </ol>
        </section>
        

    <!-- Main content -->

    <section class="content">
        <div class="row">
            @include("layouts.messaging")
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table id="example" class="table table-bordered table-hover" width="100%">
        <thead>
          <tr>
                                    <th>#</th>
                                    <th>Package Name / Billing</th>
                                    @if(Session::get('level') > 7)
                                    <th>Client Name</th>
                                    @endif
                                    <th>Licenses in Use / Total License</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Days Left</th>
                                </tr>
          
        </thead>

        
        <tbody>
            @php
                                $i=1;
                                @endphp
                                @if(!empty($active_plans))
                                @foreach($active_plans as $key => $plan)
                                

                                @foreach($plan as $subscription)
                                @php 
                                $availableSlot = count((array)$subscription->assigned);
                                @endphp

                               

                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{$subscription->package_name}} <span style="float:right;" class="badge bg-purple"> {{$subscription->billed}}</span></td>
                                    @if(Session::get('level') > 7)
                                    <td>{{$subscription->client_name}}</td>
                                    @endif
                                    <td><span class="badge bg-purple">{{$availableSlot}}</span> / <span class="badge bg-purple">{{$subscription->quantity}}</span></td>
                                    <td>{{$subscription->start_time}}</td>
                                    <td>{{$subscription->end_time}}</td>
                                    <td>
                                        @php
                                        $start_time = new DateTime(date("Y-m-d"));
                                        $end_time = new DateTime($subscription->end_time);
                                        $days = $end_time->diff($start_time)->format("%a");
                                        @endphp
                                        <span class="badge bg-purple">{{$days}}</span>
                                    </td>   

                                </tr>
                                @endforeach
                                @endforeach

                                @endif

            <?php //echo "<pre>";print_r($subscription);die; ?>

                                
                            </tbody>
      </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>


<script>
    $(document).ready(function()
    {
        var table = $('#example').DataTable(
        {
            orderCellsTop: true,
            dom: 'Bfrtip',
            scrollX: true,
            "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 2,3 ] }
            ],

            
        });
    });

</script>

@endsection
