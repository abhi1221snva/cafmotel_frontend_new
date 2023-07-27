@extends('layouts.app')
@section('title', 'Holidays')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

      <section class="content-header">
                <h1>
                   <b> Holidays</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Inbound Configuration</li>
                    <li class="active">Holidays</li>
                </ol>
        </section>
       

    <section class="content">
        <!--Call tming div starts-->
        <div class="row">
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add/Edit Holiday</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12 table-responsive">
                                <form class="form-horizontal" method="post" action="{{url('did/save-holiday')}}">
                                    @csrf
                                    <input type="hidden" name="dept_id" value="" />
                                    <div class="box-body" style="padding: 10px;">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group m-b-10">
                                                    <div class="col-md-5">
                                                        <label>Name <i data-toggle="tooltip" data-placement="right" title="Type holiday name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Day <i data-toggle="tooltip" data-placement="right" title="Select Day" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Month <i data-toggle="tooltip" data-placement="right" title="Select Month" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="padding-bottom: 5px;">
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="name" value="{{isset($detail['name']) ? $detail['name'] : ''}}" required=""/>
                                                <input type="hidden" class="form-control" name="holiday_id" value="{{$id}}" />
                                            </div>
                                            <div class="col-sm-3">
                                                <select class="form-control" name="date">
                                                    @foreach($arrDates as $date)
                                                    <option value="{{$date}}" {{isset($detail['date']) && $date == $detail['date'] ? "selected" : ''}}>{{$date}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <select class="form-control" name="month">
                                                    @foreach($arrMonths as $key => $val)
                                                    <option value="{{$key}}" {{isset($detail['month']) && $key == $detail['month'] ? "selected" : ''}}>{{$val}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="box-footer" >
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
                                                                    <a type="button" class="btn btn-danger" style="margin-right: 14px;" href="{{url('/did/holidays')}}">
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
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Holiday List</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12 table-responsive">
                                <table class="table table-hover table-striped">
                                    <tbody style='display:block; max-height: 500px;overflow-y:auto;background:#fff;'>
                                        <tr>
                                            <th width='50%'>Name</th>
                                            <th width='100%'>Date</th>
                                            <th width='30%'>Action</th>
                                        </tr>
                                        @if(sizeof($holidays) > 0)
                                            @foreach($holidays as $holiday)
                                                <tr id="holiday_{{$holiday['id']}}">
                                                    <td width='50%'>{{$holiday['name']}}</td>
                                                    <td width='100%'>{{$holiday['date']}} {{$arrMonths[$holiday['month']]}}</td>
                                                    <td width='30%'>
                                                        <a onclick="deleteHoliday('{{$holiday['id']}}')" href="javascript:void(0);"><i class="fa fa-trash"></i></a> &nbsp;   
                                                        <a href="{{url('/')}}/did/holidays/{{$holiday['id']}}"><i class="fa fa-edit"></i></a>    
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                        <tr><td colspan="3" class="text-center">No Call Timings Found</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Call tming div ends-->

    </section>
</div>
<script>
    $(document).ready(function () {

    });
    
    function deleteHoliday(id) {
        if(confirm("Are you sure want to delete this holiday?")) {
            $.ajax({
                url: root_url+'/did/delete-holiday/' + id,
                type: 'get',
                success: function(response) {
                    $("#holiday_"+id).remove();
                    toastr.success(response);
                },
                error: function(response) {
                    toastr.error(response);
                }
            });
        }
    }
</script>
@endsection