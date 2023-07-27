@extends('layouts.app')
@push('styles')
    <style>
        .status-label-container {
            margin-top: 5px;
        }
        .status-label {
            font-size: 100%;
            padding: 6px;
        }
    </style>
@endpush
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height:500px !important">
        <!-- Content Header (Page header) -->

         <section class="content-header">
                <h1>
                    <b>Clients</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> System Configuration</li>
                    <li class="active">Clients</li>
                </ol>
        </section>

        <section class="content-header">
          
              <div class="text-right mt-5 mb-3"> 

                <form method="post">
                    @csrf
                    <a href="{{ url('/client') }}"  type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Client</a>
                </form>

           </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <table id="changeClient" class="table table-bordered table-hover" width="100%">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($clients as $key => $client)
                                 @if(in_array($client->company_name, $mapping))
                                    <tr>
                                        <td>{{$client->id}}</td>
                                        <td>{{$client->company_name}}</td>
                                        <td>{{$client->address_2}}</td>
                                        <td>
                                            @if($client->stage < 5)
                                                <span class="label label-warning status-label"><i class="icon fa fa-ban"></i> Pending ({{$client->stage}}/5)</span>
                                            @else
                                                <span class="label label-success status-label"><i class="icon fa fa-check"></i> Ready</span>
                                            @endif
                                            /
                                            @if($client->is_deleted == 1)
                                                <span class="label label-danger status-label"><i class="icon fa fa-close"></i> Inactive</span>
                                            @else
                                                <span class="label label-success status-label"><i class="icon fa fa-check"></i> Active</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a style="cursor:pointer;color:blue;" href="{{url('client')}}/{{ $client->id}}" class='editEG'><i class="fa fa-edit fa-lg"></i></a>
                                            @if ($client->id != Session::get('parentId'))
                                            | <a href="#" class="dropdown-item user-clients" data-client-id="{{$client->id}}"><span class="badge bg-green">Switch To</span></a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </table>
                            <br/>
                            <br/>
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

    <script>
    $(document).ready(function()
    {
        var table = $('#changeClient').DataTable(
        {
            orderCellsTop: true,
            dom: 'Bfrtip',
            scrollX: true,
            "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 1 ] }
            ],
        });
    });

</script>
@endsection
