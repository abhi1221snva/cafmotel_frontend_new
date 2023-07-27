@extends('layouts.app')
@section('title', 'Add Office Hours')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

     <section class="content-header">
                <h1>
                   <b> Call Times</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Inbound Configuration</li>
                    <li class="active"> Call Times</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                 <a href="{{url('/')}}/did/call-timings/0" type="button" class="btn btn-sm btn-primary">Add Call Timings</a>
           </div>
        </section>
    
    <section class="content">
        <!--Call tming div starts-->
        @foreach($arrDep as $name => $department)
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary box-solid collapsed-box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Call Timings For {{$name}} : {{$department['description']}}</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>

                        <div class="box-body" style="display: none;">
                            <div class="row">
                                <div class="col-sm-12 table-responsive">
                                    <table class="table table-hover table-striped">
                                        <tbody>
                                            <tr>
                                                <th>Day</th>
                                                <th>From</th>
                                                <th>To</th>
                                            </tr>
                                            @if($arrDays > 0)
                                                @foreach($arrDays as $day => $val)
                                                    <tr>
                                                        <td><b>{{$day}}</b></td>                    
                                                        <td>{{isset($arrResult[$name][$day]['from_time']) ? $arrResult[$name][$day]['from_time'] : '-NA-'}}</td>
                                                        <td>{{isset($arrResult[$name][$day]['to_time']) ? $arrResult[$name][$day]['to_time'] : '-NA-'}}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td colspan="3" class="text-center">No Call Timings Found</td></tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row" >
                                <div class="col-sm-12">
                                    <div class="form-group" style="float:right;">
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    <a href="{{url('/')}}/did/call-timings/{{$department['id']}}" class="btn btn-primary" type="button"><i class="fa fa-check-square-o fa-lg"></i> 
                                                        Edit
                                                    </a>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <!--Call tming div ends-->
        
    </section>
</div>
<script>
    $(document).ready(function () {

    });

</script>
@endsection