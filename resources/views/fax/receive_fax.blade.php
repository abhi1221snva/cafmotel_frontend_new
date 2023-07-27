@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

     <section class="content-header">
                <h1>
                   <b>Receive Fax</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Fax</li>
                    
                    <li class="active">Receive Fax</li>
                </ol>
        </section>
     

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-3">
                @include('fax.menu')         
            </div>

            <!-- /.col -->
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Inbox </h3>

                        <div class="box-tools pull-right">
                            <div class="has-feedback">
<!--                                <input type="text" class="form-control input-sm" placeholder="Search Mail">
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>-->
                            </div>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="mailbox-controls">
                            <!-- Check all button -->

                            <!-- /.pull-right -->
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <table id="faxListTable" class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Sender</th>
                                        <th>Inbound Fax No</th>
                                        <th>Date</th>
                                        <th>Download</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($receiveFax as $key => $fax)
                                    <tr>
                                        <td><?php echo ++$key; ?></td>
                                        <td class="mailbox-name">{{$fax->callerid}}</td>
                                        <td class="mailbox-subject"><b>{{$fax->dialednumber}}</b></td>                    
                                        <td class="mailbox-date">
                                            {{ date('Y-m-d h:i A',strtotime($fax->start_time)) }}
                                        </td>
                                        <td class="mailbox-attachment"><a href="{{'fax-list'}}/{{$fax->id}}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a></td>
                                    </tr>
                                    @endforeach 

                                    @if(empty($receiveFax))
                                    <tr><td colspan="5" class="text-center">Record not found </td></tr>
                                    @endif


                                </tbody>
                            </table>
                            <!-- /.table -->
                        </div>
                        <!-- /.mail-box-messages -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-padding">
                        <div class="mailbox-controls">
                            <!-- Check all button -->

                            <!-- /.btn-group -->

                            <!-- /.pull-right -->
                        </div>
                    </div>
                </div>
                <!-- /. box -->
            </div>
            <!-- /.col -->
        </div>
    </section>
</div>
<script language="javascript">
    $(document).ready(function() {
        $('#faxListTable').dataTable();
    });
</script>
@endsection