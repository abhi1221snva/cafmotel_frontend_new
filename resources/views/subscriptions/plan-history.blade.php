@extends('layouts.app')
@section('title', 'Plan History List')
@section('content')

        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
 
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
     <section class="content-header">
                <h1>
                   <b>History Plan List</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Subscription</li>
                    
                    <li class="active">History Plan List</li>
                </ol>
        </section>

    <!-- Main content -->

    <section class="content">
        <div class="row">
            @include("layouts.messaging")
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table id="example" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Package Name</th>
                                    @if(Session::get('level') > 7)
                                    <th>Client Name</th>
                                    @endif
                                    <th>Total Licence</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Expiry Time</th>
                                    

                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($plan_history))
                                @foreach(array_reverse($plan_history) as $key => $plan)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>
                                        @foreach($packages as $list)
                                        @if($list->key == $plan->package_key)
                                        {{$list->name}}
                                        @endif
                                        @endforeach
                                    </td>
                                    @if(Session::get('level') > 7)
                                    <td>
                                        @foreach($clients as $clt)
                                        @if($clt->id == $plan->client_id)
                                        {{$clt->company_name}}
                                        @endif
                                        @endforeach
                                    </td>
                                    @endif
                                    <td><span class="badge bg-purple">{{$plan->quantity}}</span></td>
                                    <td>{{$plan->start_time}}</td>
                                    <td>{{$plan->end_time}}</td>
                                    <td>{{$plan->expiry_time}}</td>    
                                </tr>
                                @endforeach
                                @endif
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
        var oTable = $('#example').dataTable( {
            "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 2,3 ] }
            ]
        });
    });

</script>

@endsection
