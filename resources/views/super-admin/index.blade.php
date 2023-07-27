@extends('layouts.app')

@section('content')
<?php $userIdForMainOwner = array('73'); //main super admin ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height:500px !important">
        <!-- Content Header (Page header) -->

        <section class="content-header">
                <h1>
                    <b>Super Admin Lists</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Super Admins</li>
                    <li class="active">Super Admin List</li>
                </ol>
        </section>
        

       {{--<section class="content-header">
            
            <form method="post">
                @csrf
                <div class="text-right mt-5 mb-3">
                    <button  name="xml"  type="submit" value="xml" class="btn btn-sm btn-warning"><i class="fa fa-file-text fa-lg"></i> XML</button>
                    <a href="{{ url('/add-extension') }}"  class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Extension</a>
                </div>
            </form>
        </section>--}}

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
                                    <th>Extension</th>
                                    <th>Name</th>
                                    <th>Voicemail (PIN)</th>
                                    <th>Public IP</th>
                                    <th>Local IP:Port</th>
                                    <th>Call Forward</th>
                                    <th>Twinning</th>
                                    <th>Follow Me</th>


                                    <!-- <th>Status</th> -->
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php 
                                $i=1
                                @endphp    
                                @foreach($extension_list as $key => $extensions)
                                @if(($extensions->user_level == '9'))
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$extensions->extension}}</td>
                                        <td>{{$extensions->first_name}} {{$extensions->last_name}}</td>
                                        <td>
                                            @if ($extensions->voicemail_greeting != '')
                                                <span class="badge bg-green">Yes</span>
                                            @else
                                                <span class="badge bg-red">NO</span>
                                            @endif
                                            &nbsp;&nbsp;
                                            <span class="badge bg-purple">{{$extensions->vm_pin}}</span>
                                        </td>
                                        
                                        <td>
                                            @isset($extensions->ipaddr)
                                                {{$extensions->ipaddr}}
                                            @endisset
                                        </td>
                                        <td>
                                            @isset($extensions->fullcontact)
                                                @php
                                                    $udata = explode('@', substr(trim($extensions->fullcontact), 0, 30));
                                                    print_r(end($udata));
                                                @endphp
                                            @endisset
                                        </td>
                                        <td>
                                            @if ($extensions->call_forward == '1')
                                                <span class="badge bg-green">YES</span>
                                            
                                            @elseif ($extensions->call_forward == '2')
                                                <span class="badge bg-red">NO</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($extensions->twinning == '1')
                                                <span class="badge bg-green">YES</span>
                                            
                                            @elseif ($extensions->twinning == '2')
                                                <span class="badge bg-red">NO</span>
                                            @endif
                                        </td>

                                         <td>
                                            @if ($extensions->follow_me == '1')
                                                <span class="badge bg-green">YES</span>
                                            
                                            @elseif ($extensions->follow_me == '2')
                                                <span class="badge bg-red">NO</span>
                                            @endif
                                        </td>

                                        <!-- <td>{{$extensions->status}}</td> -->
                                        <td>
                                            <a style="cursor:pointer;color:blue;" href="{{url('super-admin')}}/{{ $extensions->id}}" class='editEG'  title='Edit Extension'><i class="fa fa-edit fa-lg"></i></a>
                                            | <a style="cursor:pointer;color:red;" class='openExtensionDelete' data-id={{$extensions->id}}><i class="fa fa-trash fa-lg" title='Delete Extension'></i></a></td>
                                    </tr>
                                    @php
                                    $i++
                                    @endphp
                                    @endif

                                @endforeach
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->


                <div class="modal fade" id="hangupConferencesModal" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content" style="">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="add-edit"></h4>
                            </div>
                            <form method="post" name="add-ip-form" action="">
                                @csrf
                                <div class="modal-body">
                                    <!--  -->
                                    <label for="inputEmail3" class="col-form-label">Extension Number</label>
                                    <input type="text" readonly class="form-control" minlength="7" placeholder="" name="hangupConferences_id" id="hangupConferences_id">
                                    <!-- pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" -->
                                    <div id="hangup-conference"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" id="hangupConferences-cancel" data-dismiss="modal">Cancel</button>
                                    <button type="submit" name="submit" id="hangupConferences" class="btn btn-info btn-ok">Execute</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="addIpModal" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content" style="">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="add-edit"></h4>
                            </div>
                            <form method="post" name="add-ip-form" action="">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" class="form-control" name="ext_ip_id" value="" id="ext_ip_id" required>
                                    <input type="hidden" class="form-control" name="ip_name" value="ip" id="" required>
                                    <label for="inputEmail3" class="col-form-label">Add Ip</label>
                                    <input type="text" class="form-control" minlength="7" placeholder="xxx.xxx.xxx.xxx" name="allowed_ip" id="allowed_ip">
                                    <!-- pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <button type="submit" name="submit" class="btn btn-info btn-ok">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content" style="">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="add-edit"></h4>
                            </div>
                            <form method="post" name="edit-extention" action="">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" class="form-control" name="ext_id" value="" id="ext_id" required>
                                    <label for="inputEmail3" class="col-form-label">New Password</label>
                                    <input type="text" class="form-control" required name="password" id="password" placeholder="Enter New Password">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <button type="submit" name="submit" class="btn btn-info btn-ok">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="changePermissionModal" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content" style="">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="change-permission-title">Change permission</h4>
                            </div>
                            <form method="post" name="save-user-roles-form" id="save-user-roles-form" action="{{ route('saveUserRoles') }}">
                                <div class="modal-body">
                                    <div id="role-modal-loader" style="display:none; text-align: center;">
                                        <img src="asset/img/loader.gif" alt="loading..." height=100 width=100 />
                                    </div>
                                    <input type="hidden" class="form-control" name="role_ext_id" value="" id="role_ext_id" required>
                                    <div id="change-permission-body"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" id="role-modal-close" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-info btn-ok" id="role-modal-save" style="display: none;" >Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="delete" role="dialog">
                    <!-- Modal content-->
                    <div class="modal-dialog">
                        <div class="modal-content" style="background-color: #d33724 !important;color:white;">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                            </div>
                            <div class="modal-body">
                                <p>You are about to delete <b><i class="title"></i></b> Extension.</p>
                                <p>Do you want to proceed?</p>
                                <input type="hidden" class="form-control" name="label_id" value="" id="extension_id">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger btn-ok deleteExtension">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <script>
        $(document).ready(function () {
            var oTable = $('#example').dataTable({
                "aoColumnDefs": [{
                    "bSortable": false,
                    "aTargets": [3, 4, 6]
                }]
            });
        });


    </script>
@endsection
