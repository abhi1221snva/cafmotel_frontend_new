@extends('layouts.app')
@push('styles')
    <style type="text/css">
        .centered-body {
            text-align: center;
            padding: 2px;
        }
    </style>
@endpush
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <section class="content-header">
                <h1>
                   <b>IP Approval List</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Configuration</li>
                    <li class="active">IP Approval List</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                 <a href="{{ url('/whitelist-ip') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Whitelist IP</a>
           </div>
        </section>
        <!-- Content Header (Page header) -->
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-info">
                        <div class="box-header">
                            <div class="form-group m-b-10">
                                <div class="col-md-3">
                                    <!-- IP mask -->
                                    <div class="form-group">
                                        <label>IP <i data-toggle="tooltip" data-placement="right" title="Type IP address" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-laptop"></i>
                                            </div>
                                            <input type="text" name="whitelistIp" id="whitelistIp" class="form-control" data-inputmask="'alias': 'ip'" data-mask  value="" />
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <!-- /.form group -->
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="asteriskServer">Server <i data-toggle="tooltip" data-placement="right" title="Select server from the drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <select class="form-control" name="asteriskServer" id="asteriskServer" data-placeholder="Server">
                                            <option value="" >All</option>
                                            @if(!empty($asteriskServers))
                                                @foreach($asteriskServers as $server)
                                                    <option value='{{$server->host}}' >{{$server->host}} - {{$server->location}} - {{$server->trunk}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="status">Status <i data-toggle="tooltip" data-placement="right" title="Select status from the drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <select class="form-control" name="status" id="status" data-placeholder="Status">
                                            <option value=0>Pending</option>
                                            <option value=1>Approved</option>
                                            <option value=-1>Rejected</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="status">From Web <i data-toggle="tooltip" data-placement="right" title="Select type from web " class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <select class="form-control" name="fromWeb" id="fromWeb" data-placeholder="From Web">
                                            <option value="">All</option>
                                            <option value=1>Yes</option>
                                            <option value=0>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="status"> </label>
                                    <div class="form-group">
                                        <input type="button" id="search" value="Search" class="btn btn-info" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="approval-list" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Server</th>
                                    <th>User</th>
                                    <th>IP Address</th>
                                    <th>Location</th>
                                    <th>From Web</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="result">
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
    </div>
    <!-- /.content-wrapper -->

    <div class="modal fade" id="confirm">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirm</h4>
                </div>
                <div class="modal-body">
                    <p>Please confirm you want to <span id="confirm-action" class=""></span> ip <b><span id="confirm-ip"></span></b>.</p>
                    <div id="message">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="server_ip" value="" />
                    <input type="hidden" id="whitelist_ip" value="" />
                    <button type="button" data-dismiss="modal" class="btn btn-success" id="btn-approve">Approve</button>
                    <button type="button" data-dismiss="modal" class="btn btn-danger" id="btn-reject">Reject</button>
                    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection
@push('scripts')
    <!-- InputMask -->
    <script src="{{ asset('asset/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('asset/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('asset/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-mask]').inputmask();

            var oTable = $('#approval-list').dataTable({
                "aoColumnDefs": [{
                    "bSortable": true,
                    "aTargets": [1, 2, 3]
                }]
            });

            searchIpList(1, 0, null, null);
            $("#search").click(function (e) {
                searchIpList($("#fromWeb").val(), $("#status").val(), $("#asteriskServer").val(), $("#whitelistIp").val());
            });

            //Pass false to the callback function
            $(".btn-no").click(function () {
                handler(false);
                $("#confirm").modal("hide");
            });

            $('#btn-approve').click(function (e) {
                $("#confirm").modal("hide");
                $.ajax({
                    type: "POST",
                    url: "/ip-approve-whitelist",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "serverIp": $("#server_ip").val(),
                        "whitelistIp": $("#whitelist_ip").val(),
                    },
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        if (data.success) {
                            $("#alert-success").html(data.message).show();
                            setTimeout(function(){
                                window.location.reload(1);
                            }, 3000);
                        } else {
                            $("#alert-errors").html(data.message).show();
                        }
                    },
                    error: function (xhr, status, error) {
                        $("#alert-errors").html(error).show();
                    }
                });
            });
            $('#btn-reject').click(function (e) {
                $("#confirm").modal("hide");
                $.ajax({
                    type: "POST",
                    url: "/ip-reject-whitelist",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "serverIp": $("#server_ip").val(),
                        "whitelistIp": $("#whitelist_ip").val(),
                    },
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        if (data.success) {
                            $("#alert-success").html(data.message).show();
                            setTimeout(function(){
                                window.location.reload(1);
                            }, 3000);
                        } else {
                            $("#alert-errors").html(data.message).show();
                        }
                    },
                    error: function (xhr, status, error) {
                        $("#alert-errors").html(error).show();
                    }
                });

                $("#confirm").modal("hide");
            });
        });

        function approveIp(server_ip, whitelist_ip) {
            $('#confirm-action').html("approve");
            $('#confirm-action').removeClass('text-red').addClass('text-green');
            $('#confirm-ip').html(whitelist_ip);

            $('#btn-reject').hide();
            $('#btn-approve').show();
            $('#server_ip').val(server_ip);
            $('#whitelist_ip').val(whitelist_ip);
            $('#confirm').modal('show');
        }

        function rejectIp(server_ip, whitelist_ip) {
            $('#confirm-action').html("reject");
            $('#confirm-action').addClass('text-red').removeClass('text-green');
            $('#confirm-ip').html(whitelist_ip);

            $('#btn-approve').hide();
            $('#btn-reject').show();
            $('#server_ip').val(server_ip);
            $('#whitelist_ip').val(whitelist_ip);
            $('#confirm').modal('show');
        }

        function searchIpList(fromWeb, approvalStatus, asteriskServer, whitelistIp)
        {
            $('#search').prop('disabled', true);
            $("#result").html("<td colspan='7' class='centered-body'><img src='{{ asset("asset/img/loader-30px.gif") }}' /></td>");

            var postdata = {
                "_token": "{{ csrf_token() }}",
                "fromWeb": fromWeb,
                "approvalStatus": approvalStatus
            };
            if (asteriskServer) postdata.asteriskServer = asteriskServer;
            if (whitelistIp) postdata.whitelistIp = whitelistIp;

            $.ajax({
                type: "POST",
                url: "/query-whitelist",
                data: postdata,
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        var list = "";
                        var count = 0;
                        $.each(response.data, function (index, row) {
                            console.log(row);
                            var datetime = new Date(row.created_at);
                            var created_at = datetime.toLocaleString();
                            list += "<tr>";
                            list += "<td>" + created_at + "</td>";
                            list += "<td>" + row.server_ip + "</td>";
                            list += "<td>" + row.user + "</td>";
                            list += "<td>" + row.whitelist_ip + "</td>";
                            list += "<td>" + (row.ip_location?row.ip_location:'-') + "</td>";
                            list += "<td>" + (row.from_web?'Yes':'No') + "</td>";
                            if (row.approval_status === 0) {
                                list += "<td>";
                                list += "<a style=\"cursor:pointer;color:green;\" href=\"javascript:approveIp('"+row.server_ip+"','"+row.whitelist_ip+"');\"><i class=\"fa fa-check fa-lg\"></i></a> <span style=\"padding: 0px 10px;\">|</span>";
                                list += "<a style=\"cursor:pointer;color:red;\" href=\"javascript:rejectIp('"+row.server_ip+"','"+row.whitelist_ip+"');\"><i class=\"fa fa-remove fa-lg\"></i></a>";
                                list += "</td>";
                            } else if (row.approval_status === 1) {
                                list += "<td>Approved ("+row.approvedBy+")</td>";
                            } else if (row.approval_status === -1) {
                                list += "<td>Rejected ("+row.approvedBy+")</td>";
                            } else {
                                list += "<td></td>";
                            }
                            list += "</tr>";
                            count++;
                        });
                        if (count > 0) {
                            $("#result").html(list);
                        } else {
                            $("#result").html("<td colspan='7' class='centered-body'>No data available</td>");
                        }
                    } else {
                        var errors = response.message;
                        $.each(response.errors, function (index, row) {
                            errors += "<br/>" + row;
                        });
                        $("#result").html("<td colspan='7' class='centered-body'>"+errors+"</td>");
                    }
                    $('#search').prop('disabled', false);
                },
                error: function (xhr, status, error) {
                    $("#result").html("<td colspan='7' class='centered-body'>"+error+"</td>");
                    $('#search').prop('disabled', false);
                }
            });
        }
    </script>
@endpush

