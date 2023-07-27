@extends('layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

         <section class="content-header">
                <h1>
                   <b>Add Marketing Campaign Schedule</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Marketing Campaigns</li>
                    
                    <li class="active">Add Marketing Campaign Schedule</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                <a href="{{ url('/marketing-campaign-schedules') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show Marketing Campaign Schedule</a>
           </div>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <form method="post" name="userform" id="userform" action="">
                            @csrf
                            <div class="box-body">
                                <div class="modal-body">
                                    <div class="form-group m-b-10">

                                        <input type="hidden" name="created_by" value="{{Session::get('id')}}" />


                                        <div class="col-md-4">
                                            <label>Marketing Campaign</label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control" name="campaign_id" id="campaign_id">
                                                    <option value="">Select Campaign</option>
                                                    @if(!empty($campaign))
                                                        @foreach($campaign as $skey => $clists)
                                                            <option value="{{$clists['id']}}" >{{$clists['title']}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Lists</label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control" name="list_id" id="list_id">
                                                    <option value="">Select List</option>
                                                    @if(!empty($list))
                                                        @foreach($list as $skey => $clists)
                                                            <option value="{{$clists->list_id}}" >{{$clists->list}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                         <div class="col-md-4">
                                            <label>Send</label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control" name="send" id="send">
                                                    <option value="">Send Type</option>
                                                    <option value="1">Email</option>
                                                    <option value="2">Text</option>
                                                </select>
                                            </div>
                                        </div>

                                         <div class="col-md-4">
                                            <label>Email Template</label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control" name="email_template_id" id="email_template_id">
                                                    <option value="">Select Template</option>
                                                    @if(!empty($email_templates))
                                                        @foreach($email_templates as $skey => $clists)
                                                            <option value="{{$clists['id']}}" >{{$clists['template_name']}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                         <div class="col-md-4">
                                            <label>Lists</label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control" name="email_setting_id" id="email_setting_id">
                                                    <option value="">Email Setting</option>
                                                    @if(!empty($smtp_setting))
                                                        @foreach($smtp_setting as $skey => $clists)
                                                            <option value="{{$clists['id']}}" >{{$clists['mail_host']}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                         <div class="col-md-4">
                                            <label>Run Time</label>
                                            <div class="input-daterange input-group col-md-12">
                    <input autocomplete="off" type="datetime-local" class="form-control" required=""  id="run_time" name="run_time" >
                </div>
            </div>






                                    </div>
                                </div>

                                </div>
                                <div style="clear: both"></div>
                                <div class="modal-footer">
                                    <button type="submit" name="submit" value="add" class="btn btn btn-primary waves-effect waves-light">Save</button>
                                </div>

                            </div><!-- /.box-body -->
                        </form>
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection



