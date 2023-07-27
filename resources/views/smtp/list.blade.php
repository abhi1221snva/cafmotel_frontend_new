@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<!-- Content Wrapper. Contains page content -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height:500px !important">
        <!-- Content Header (Page header) -->
       

        <section class="content-header">
                <h1>
                   <b>SMTP</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Configuration</li>
                    <li class="active">SMTP</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
               <form method="post">
                    @csrf
                    <a href="{{ url('/smtp') }}"  type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Smtp</a>
                </form>
           </div>
        </section>
           

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <table id="example" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Sender</th>
                                    <th>Mail Driver</th>
                                    <th>Host</th>
                                    <th>Port</th>
                                    <th>Username</th>
                                    <th>Encryption</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(!empty($smtps))
                                    @foreach($smtps as $key => $smtp)

                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{$smtp->sender_type}}</td>
                                            <td>
                                                @if($smtp->sender_type === "system")
                                                    {{ $smtp->from_name }}
                                                @endif
                                                @if($smtp->sender_type === "campaign")
                                                    @if (isset($campaigns[$smtp->campaign_id]))
                                                        {{ $campaigns[$smtp->campaign_id] }}
                                                    @else
                                                        <span class="error">Invalid Campaign</span>
                                                    @endif
                                                @endif
                                                @if($smtp->sender_type === "user")
                                                    @if (isset($users[$smtp->user_id]))
                                                        {{ $users[$smtp->user_id] }}
                                                    @else
                                                        <span class="error">Invalid User</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{$smtp->mail_driver}}</td>

                                            <td>{{$smtp->mail_host}}</td>
                                            <td>{{$smtp->mail_port}}</td>
                                            <td>{{$smtp->mail_username}}</td>
                                            <td>{{$smtp->mail_encryption}}</td>
                                            <td>
                                                @if($smtp->status == '1')
                                                    <span class="label label-success">Active</span>
                                                @else ($smtp->status == '0')
                                                    <span class="label label-warning">Inactive</span>
                                                @endif
                                            </td>


                                            <td>


                                                <a style="cursor:pointer;color:blue;;" href="{{url('smtp')}}/{{ $smtp->id }}" class='editEG'><i class="fa fa-edit fa-lg"></i></a>
                                                | <a style="cursor:pointer;color:blue;" href="{{url('copy-smtp/')}}/{{$smtp->id}}" class="" data-id="{{$smtp->id}}"><i class="fa fa-copy fa-lg"></i></a>
                                                | <a style="cursor:pointer;color:red;" href="javascript:deleteSetting({{ $smtp->id }})" id='delete-{{ $smtp->id }}'><i class="fa fa-trash fa-lg"></i></a>

                                        </tr>

                                    @endforeach
                                @endif
                                </tbody>

                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection
@push('scripts')
    <script language="javascript">
        $(document).ready(function () {
            var oTable = $('#example').dataTable({
                "aoColumnDefs": [
                    {"bSortable": false, "aTargets": [2, 3]}
                ]
            });
        });
        function deleteSetting(id)
        {
            $("#delete-" + id).hide();
            postData = {
                "_token": "{{ csrf_token() }}"
            };
            $.ajax({
                type: "POST",
                url: "/smtp-delete/" + id,
                data: postData,
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    if (data.success) {
                        $("#alert-success").html(data.message).show();
                        setTimeout(function(){
                            window.location.reload(1);
                        }, 2000);
                    } else {
                        $("#alert-errors").html(data.message).show();
                    }
                },
                error: function (xhr, status, error) {
                    $("#alert-errors").html(error).show();
                }
            });
        }
    </script>
@endpush
