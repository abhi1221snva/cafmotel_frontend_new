@extends('layouts.app')
@section('title', 'Modules List')
@section('content')

        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
    <!-- Content Header (Page header) -->


         <section class="content-header">
                <h1>
                   <b>Modules List</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> System Configuration</li>
                    
                    <li class="active">Modules List</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
              <a id="openListForms" href="{{url('super/module')}}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Modules </a>
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
                                    <th>Name</th>
                                    <th>Components</th>
                                    <th>Attributes</th>
                                    <th>Status</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($modules))
                                @foreach(array_reverse($modules) as $key => $module)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$module->name}}</td>
                                    <td>
                                        @php
                                        $components = implode(',',$module->components);
                                        @endphp
                                        {{$components}}

                                    </td>

                                    <td>
                                        @php
                                        $attributes = implode(',',$module->attributes);
                                        @endphp
                                        {{$attributes}}

                                    </td>



                                    <td>
                                        @if($module->is_active == 1)
                                        <span class="badge bg-green">Active</span>
                                        @else
                                        <span class="badge bg-red">Inactive</span>
                                        @endif
                                    </td>

                                    <td>
                                        <a style="cursor:pointer;color:blue;" href="{{url('super/module')}}/{{$module->key}}" title="Edit Package Details" class=''  ><i class="fa fa-edit fa-lg"></i></a>

                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>


<script>
    $(document).ready(function()
    {
        var oTable = $('#example').dataTable( {
            "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 2,3 ] }
            ]
        });
    });

</script>

@endsection
