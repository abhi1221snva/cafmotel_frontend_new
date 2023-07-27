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
                    <b>Packages Plans ({{$package['key']}})</b>
                    
                </h1>
                <ol class="breadcrumb">
                     <li><a href="{{('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> System Configuration</li>
                     
                    <li class="active">Country Wise Rate</li>
                </ol>
        </section>
    <section class="content-header">
         <div class="text-right mt-5 mb-3"> 
            <a id="openListForms" href="{{url('/super/package/rate/add')}}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Rate </a>
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
                                    <th>Package</th>
                                    <th>Country / Code</th>
                                    <th>CallRate/Minute</th>
                                    <th>Six/Six Sec</th>

                                    <th>Rate/SMS</th>
                                    <th>Rate/DID</th>
                                    <th>Rate/FAX</th>
                                    <th>Rate/EMAIL</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($country_wise_rate))
                                @foreach(array_reverse($country_wise_rate) as $key => $plan)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$package['name']}}</td>
                                    <td><span>{{$plan['title_name']}} / ({{$plan['phone_code']}})</span></td>
                                    <td><i class="fa fa-usd" aria-hidden="true"></i> {{$plan['call_rate_per_minute']}}</td>
                                    <td><i class="fa fa-usd" aria-hidden="true"></i> {{$plan['rate_six_by_six_sec']}}</td>

                                    <td><i class="fa fa-usd" aria-hidden="true"></i> {{$plan['rate_per_sms']}}</td>
                                    <td><i class="fa fa-usd" aria-hidden="true"></i> {{$plan['rate_per_did']}}</td>
                                    <td><i class="fa fa-usd" aria-hidden="true"></i> {{$plan['rate_per_fax']}}</td>
                                    <td><i class="fa fa-usd" aria-hidden="true"></i> {{$plan['rate_per_email']}}</td>
                                    <td>
                                        <!-- <a style="cursor:pointer;color:blue;" href="{{url('/super/package/view')}}/{{$plan['id']}}" title="View Package Details" class=''  ><i class="fa fa-eye fa-lg"></i></a>
                                        | -->
                                        <a style="cursor:pointer;color:blue;" href="{{url('/super/package/rate/edit')}}/{{$plan['id']}}" title="Edit Package Details" class=''  ><i class="fa fa-edit fa-lg"></i></a>
                                        <!-- | -->
                                        <!-- <a style="cursor:pointer;color:blue;" title="Copy Package Details" href="{{url('/super/package/copy')}}/{{$plan['id']}}" class=''  ><i class="fa fa-copy fa-lg"></i></a>
                                        | -->
                                       <!--  <a style="cursor:pointer;color:blue;" title="Country Wise Rate" href="{{url('/super/package/rate')}}/{{$plan['id']}}" class=''  ><i class="fa fa-usd fa-lg"></i></a> -->
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
