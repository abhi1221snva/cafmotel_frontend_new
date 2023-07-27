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
                   <b>SMS Setting</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Configuration</li>
                    <li class="active">SMS Setting</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
               <form method="post">
                    @csrf
                    <a href="{{ url('/setting-sms') }}"  type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Sms Setting</a>
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
                                    <th>Sms Url</th>
                                    <th>Sender</th>
                                    <th>API Key</th>
                                    <th>Sender Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(!empty($sms_setting))
                                    @foreach($sms_setting as $key => $sms)

                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{$sms->sms_url}}</td>
                                            <td>{{$sms->sender_name}}</td>
                                            <td>{{$sms->api_key}}</td>
                                            <td>{{$sms->sender_type}}</td>
                                           {{-- <td>
                                                @if($sms->sender_type === "system")
                                                    {{ $sms->sender_type }}
                                                @endif
                                                @if($sms->sender_type === "campaign")
                                                    @if (isset($campaigns[$sms->campaign_id]))
                                                        {{ $campaigns[$sms->campaign_id] }}
                                                    @else
                                                        <span class="error">Invalid Campaign</span>
                                                    @endif
                                                @endif
                                                @if($sms->sender_type === "user")
                                                    @if (isset($users[$sms->user_id]))
                                                        {{ $users[$sms->user_id] }}
                                                    @else
                                                        <span class="error">Invalid User</span>
                                                    @endif
                                                @endif
                                            </td> --}}

                                            <td>
                                                @if($sms->status == '1')
                                                    <span class="label label-success">Active</span>
                                                @else ($sms->status == '0')
                                                    <span class="label label-warning">Inactive</span>
                                                @endif
                                            </td>


                                            <td>


                                                <a style="cursor:pointer;color:blue;;" href="{{url('setting-sms')}}/{{ $sms->id }}" class='editEG'><i class="fa fa-edit fa-lg"></i></a>
                                                | <a style="cursor:pointer;color:red;" href="javascript:deleteSetting({{ $sms->id }})" id='delete-{{ $sms->id }}'><i class="fa fa-trash fa-lg"></i></a>

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
                url: "/sms-delete/" + id,
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
