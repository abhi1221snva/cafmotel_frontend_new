@extends('layouts.app')
@section('title', 'Edit Call Timings')
@section('content')
<div class="content-wrapper">
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
    
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Call Timings</h3>
                    </div>

                    <form class="form-horizontal" method="post" action="{{url('did/save-call-timings')}}">
                        @csrf
                        <input type="hidden" name="dept_id" value="{{$dept_id}}" />
                        <div class="box-body" style="padding: 10px 160px;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group m-b-10">
                                        <div class="col-md-4">
                                            <label>Name <i data-toggle="tooltip" data-placement="right" title="Call time name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Description <i data-toggle="tooltip" data-placement="right" title="Call time description" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="padding-bottom: 5px;">
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="name" value="{{isset($departments[0]->name) ? $departments[0]->name : ''}}" />
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="description" value="{{isset($departments[0]->description) ? $departments[0]->description : ''}}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group m-b-10">
                                        <div class="col-md-4">
                                            <label>Day <i data-toggle="tooltip" data-placement="right" title="Days name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        </div>
                                        <div class="col-md-4">
                                            <label>From <i data-toggle="tooltip" data-placement="right" title="Shift Start Time" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        </div>
                                        <div class="col-md-4">
                                            <label>To <i data-toggle="tooltip" data-placement="right" title="Shift End Time" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        </div>
                                    </div>
                                    @foreach($arrDays as $key => $val)
                                    <div class="form-group m-b-10">
                                        <div class="col-md-4">
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="text" class="form-control" name="day[]" value="{{$key}}" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="time" class="form-control" name="from[]" value="{{isset($val['from_time']) ? $val['from_time'] : ''}}"  style="width:120px;" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="time" class="form-control" name="to[]" value="{{isset($val['to_time']) ? $val['to_time'] : ''}}" style="width:120px;" />
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row" >
                                <div class="col-sm-12">
                                    <div class="form-group" style="float:right;">
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    <button id="submit" class="btn btn-primary" type="submit"><i class="fa fa-check-square-o fa-lg"></i> 
                                                        Submit
                                                    </button>
                                                    &nbsp;
                                                    <a type="button" class="btn btn-warning"  onclick="window.location.reload();"><i class="fa fa-refresh fa-lg"></i> 
                                                        Reset
                                                    </a>
                                                    &nbsp;
                                                    <a type="button" class="btn btn-danger" style="margin-right: 14px;" href="{{url('/did/call-timings-listing')}}">
                                                        <i class="fa fa-close fa-lg"></i> 
                                                        Cancel
                                                    </a>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function () {
    });

</script>
@endsection