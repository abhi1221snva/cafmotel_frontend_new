@extends('layouts.app')
@section('title', 'Subscriptions')
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height:500px !important">
        <!-- Content Header (Page header) -->

          <section class="content-header">
                <h1>
                   <b>Subscriptions</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Subscription</li>

                    <li class="active">User Packages</li>
                </ol>
        </section>
        

        <!-- Main content -->
        <section class="content">
            <div class="row">
                @include('layouts.messaging')
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <table id="user_packages" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Available Package</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($userPackages as $key => $userPackage)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$userPackage->first_name}} {{$userPackage->last_name}}</td>
                                        <td>{{$userPackage->role}}</td>
                                        <td>
                                            @if( $userPackage->package_key ) {{-- package selected --}}
                                                <select class="form-control package-control-selected" name="package" data-client="{{$userPackage->client_id}}" data-user="{{$userPackage->user_id}}">
                                                    @php
                                                        $earlier = new DateTime(date("Y-m-d"));
                                                        $later = new DateTime($userPackage->end_time);
                                                        $diff = $later->diff($earlier)->format("%a");
                                                    @endphp
                                                    <option value="{{$userPackage->package_key}}">{{$userPackage->package_name}} – {{$diff}} Days remaining</option> {{-- selected package name --}}
                                                    <option name ="package" value="{{$userPackage->package_key}}">Remove Package</option>
                                                </select>
                                            @else
                                                <select class="form-control package-control-select" name="package" data-client="{{$userPackage->client_id}}" data-user="{{$userPackage->user_id}}">
                                                    <option value="#">Assign package</option>
                                                    @foreach($availablePackages as $key => $availablePackage)
                                                        @php $availableSlot = $availablePackage->quantity - count((array)$availablePackage->assigned);
                                                        $earlier = new DateTime(date("Y-m-d"));
                                                        $later = new DateTime($availablePackage->end_time);
                                                        $diff = $later->diff($earlier)->format("%a");
                                                        @endphp
                                                                <option name ="package" value="{{$availablePackage->package_key}}">{{$availablePackage->package_name}} ({{$availableSlot}}) – {{$diff}} Days remaining</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
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

    <script type="text/javascript">
    $(document).ready(function() {
        $('#user_packages').DataTable();

        $(document).on('change', '.package-control-select', function(){
            var package_key = this.value;
            if ( package_key != "#" ){
                var client_id = $(this).data('client');
                var user_id = $(this).data('user');
                $.ajax({
                    url: '/user-package/update/' + package_key,
                    type: 'POST',
                    data: {
                        user_id: user_id,
                        client_id: client_id
                    },
                    dataType:"json",
                    success: function (response)
                    {
                        toastr.success(response);
                        location.reload();
                    }
                });
            }
        });

        $(document).on('change', '.package-control-selected', function(){
            var package_key = this.value;
                var client_id = $(this).data('client');
                var user_id = $(this).data('user');
                $.ajax({
                    url: '/user-package/delete/' + package_key,
                    type: 'POST',
                    data: {
                        user_id: user_id,
                        client_id: client_id
                    },
                    dataType:"json",
                    success: function (response)
                    {
                        toastr.success(response);
                        location.reload();
                    }
                });
        });
    });
    </script>
@endsection
