@extends('layouts.app')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper dialer">
    <!-- Content Header (Page header) -->
    <section class="content-header">
                <h1>
                   <b>Edit Lead </b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Lead Management</li>
                    <li class="active">Edit Lead </li>
                </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row" >
            <div class="col-xs-12" >
                <div class="box">
                    <div class="box-body">
                        <div class="row-fluid">
                            <div class="form-group" style="margin-left: 15%;">
                                <div class="row text-danger text-center" id="form_error">
                                    @if(!empty($message))
                                    {{ $message }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.row -->

        <div class="row">
            <form action="{{url('/')}}/update-lead-data" method="POST">
                @csrf
                <input value="{{$id}}" name="lead_id" type="hidden" />
                <input value="{{$number}}" name="number" type="hidden" />
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                @foreach($leadData as $lead)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{$lead->title}}:</label>
                                        @if($lead->id == 16 && $lead->value == '')
                                            <input value="{{$number}}" name="label_value[]" type="text" class="form-control" readonly="readonly"/>
                                        @else
                                            <input value="{{$lead->is_dialing == 1 ? $number : trim($lead->value)}}" {{$lead->is_dialing == 1 ? "readonly" : "" }} name="label_value[]" type="text" class="form-control" />
                                        @endif
                                        <input value="{{$lead->id}}" name="label_id[]" type="hidden"  />
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="panel-footer" style="text-align: right;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="Submit" type="button" class="btn btn-success" />Submit</button>
                                        <a href="{{url('/')}}/lead-activity?phone_number={{$number}}" value="Cancel" type="button" class="btn btn-info" />Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
@push('styles')

@endpush
@push('scripts')

<script language="javascript">
    $(function () {});
</script>
@endpush
