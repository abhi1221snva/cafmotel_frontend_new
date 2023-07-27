@extends('layouts.app')
@section('title', 'Packages List')
@section('content')

        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">


    <section class="content-header">
                <h1>
                    <b>Packages Plans</b>
                    
                </h1>
                <ol class="breadcrumb">
                     <li><a href="{{('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> System Configuration</li>
                     
                    <li class="active">Packages Plans</li>
                </ol>
        </section>
    <section class="content-header">
         <div class="text-right mt-5 mb-3"> 
            <a id="openListForms" href="{{url('/super/package')}}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Package </a>
        </div>
       
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <div class="box-body">
                        <table id="example" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Currency Code</th>
                                    <th>CallRate/Minute</th>
                                    <th>Rate/SMS</th>
                                    <th>Rate/DID</th>
                                    <th>Rate/FAX</th>
                                    <th>Rate/EMAIL</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($packages))
                                @foreach(array_reverse($packages) as $key => $plan)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$plan->name}}</td>
                                    <td>
                                        @if($plan->is_active == 1)
                                        <span class="badge bg-green">Active</span>
                                        @else
                                        <span class="badge bg-red">Inactive</span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-yellow">{{$plan->currency_code}}</span></td>
                                    <td><i class="fa fa-usd" aria-hidden="true"></i> {{$plan->call_rate_per_minute}}</td>
                                    <td><i class="fa fa-usd" aria-hidden="true"></i> {{$plan->rate_per_sms}}</td>
                                    <td><i class="fa fa-usd" aria-hidden="true"></i> {{$plan->rate_per_did}}</td>
                                    <td><i class="fa fa-usd" aria-hidden="true"></i> {{$plan->rate_per_fax}}</td>
                                    <td><i class="fa fa-usd" aria-hidden="true"></i> {{$plan->rate_per_email}}</td>
                                    <td>
                                        <a style="cursor:pointer;color:blue;" href="{{url('/super/package/view')}}/{{$plan->key}}" title="View Package Details" class=''  ><i class="fa fa-eye fa-lg"></i></a>
                                        |
                                        <a style="cursor:pointer;color:blue;" href="{{url('/super/package')}}/{{$plan->key}}" title="Edit Package Details" class=''  ><i class="fa fa-edit fa-lg"></i></a>
                                        |
                                        <a style="cursor:pointer;color:blue;" title="Copy Package Details" href="{{url('/super/package/copy')}}/{{$plan->key}}" class=''  ><i class="fa fa-copy fa-lg"></i></a>
                                        |
                                        <a style="cursor:pointer;color:blue;" title="Country Wise Rate" href="{{url('/super/package/rate')}}/{{$plan->key}}" class=''  ><i class="fa fa-usd fa-lg"></i></a>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>



                    </div><!-- /.box-body -->
                </div><!-- /.box -->


            </div><!-- /.col -->







        </div><!-- /.row -->
    </section>
</div>

<script>
    $(document).ready(function()
    {
        var oTable = $('#example').dataTable( {
            "aoColumnDefs": [{
                "bSortable": false, "aTargets": [ 2,3 ] }
                ]
            });
    });

</script>

@endsection
