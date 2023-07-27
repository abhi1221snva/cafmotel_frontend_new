@extends('layouts.app')
@section('title', 'Sip Channel Provider')
@section('content')


<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Content Header (Page header) -->

     <section class="content-header">
                <h1>
                   <b>Sip Channel Provider</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Sip Channel Provider</li>
                    <li class="active">list</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                   <a href="{{ url('/sip-channel-provider') }}" class="btn btn-sm btn-primary">Back</a>
                  <a id="openLabelForm" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add List</a>
            
               
           </div>
        </section>
    
    <!-- Main content -->
    <section class="content">
        <div class="row">
        <?php
        $url_page = explode('?',str_replace('/','',$_SERVER['REQUEST_URI']));
        $url = $url_page[0];
           
            if($page == 1)
            {
                $currentPage = 1;
            }

            else
            {
                $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            }

            $perPage = $show;
            $paginator = new Illuminate\Pagination\LengthAwarePaginator($sip_channel,$record_count,$perPage,$currentPage,['path' => url($url)]);
            $record_count = $paginator->total();
        ?>
            <div class="col-xs-12">
                <div class="box">

                    <div class="box-body">
                    <form method="GET" action="">
                        <b>Total Rows :<?= $record_count ?></b>
                        <div class="text-right"style="margin-bottom:10px;">
                            <input type="text" name="search"id="search" placeholder="title or channel provider"value="{{$searchTerm}}">
                            <button type="submit"><i class="fa fa-search"></i></button>
                    </div>
                    <label for="show">Show:</label>
                        <select name="show" onchange="this.form.submit()">
                            <option value="10" {{ request('show') == 10 ? 'selected' : '' }}selected>10</option>
                            <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('show') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <label for="entries">entries</label>
                    </form>
                 
                        <table id="example" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Channel Provider</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                                @foreach($sip_channel as $key => $label_data)
                                <tr>

                                    <td>{{++$key}}</td>

                                    <td>{{$label_data->title}}</td>
                                    <td>{{$label_data->channel_provider}}</td>
                                    <td> @if($label_data->status == '0')
                                        <span class="label label-warning">Inactive</span> 
                                        @else ($label_data->status == '1')
                                        <span class="label label-success">Active</span> @endif
                                    </td>
                                    <td><a style="cursor:pointer;color:blue;" class='editLabel' data-id={{$label_data->id}} ><i class="fa fa-edit fa-lg"></i></a> 
                                    | <a style="cursor:pointer;color:red;" class='openLabelDelete' data-id={{$label_data->id}}><i class="fa fa-trash fa-lg"></i></a>
                                   </td>

                                </tr>
                                @endforeach
                                
                        </table>
                        <div class="text-right">
                        {{$paginator->appends(Request::all())->links()}}
                       </div>
                    </div>
                    <!-- /.box-body -->
                    @if ($paginator->total() > 0)
                        <div class="text-left mt-10"style=margin-left:10px;>
                            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} entries.
                        </div>
                    @endif
                </div>
                <!-- /.box -->

            </div>
            <!-- /.col -->
            <?php  ?>
        </div>
        <!-- /.row -->

        <div class="modal fade" id="myModal" role="dialog">

            <div class="modal-dialog">
                <div class="modal-content" style="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="add-edit"></h4>
                    </div>

                    <form method="post" action=""id="myform">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" class="form-control" name="sip_id" value="" id="id">

                            <label for="inputEmail3" class="col-form-label">Title <i data-toggle="tooltip" data-placement="right" title="Enter Title" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <select name="title"id="title"class="form-control">
                                <option value="">--Select--</option>
                                @foreach ($campaignTypes as $campaignType)
                                    <option value="{{ $campaignType->title_url }}">{{ $campaignType->title }}</option>
                                @endforeach
                            </select>

                             <label for="inputEmail3" class="col-form-label">Channel Provider <i data-toggle="tooltip" data-placement="right" title="Enter Title Url" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <input type="text" class="form-control" required name="channel_provider" id="channel_provider" placeholder="Channel Provider">
                            <label for="inputEmail3" class="col-form-label">Status <i data-toggle="tooltip" data-placement="right" title="Enter  Url" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <select class="form-control" name="status" id="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>                        
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="submit" class="btn btn-info btn-ok">Save</button>
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
                        <p>You are about to delete <b><i class="title"></i></b> record.</p>
                        <p>Do you want to proceed?</p>
                        <input type="hidden" class="form-control" name="sip_id" value="" id="sip_list_id">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger btn-ok deleteLabel">Delete</button>

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
 
    $(".openLabelDelete").click(function() {
        var delete_id = $(this).data('id');
        $("#delete").modal();
        $("#sip_list_id").val(delete_id);

    });

    $("#openLabelForm").click(function() {
        $("#myModal").modal();
        $("#title").val('');
        $("#channel_provider").val();
        $("#status").val('1');      
        $("#id").val('');
        $("#add-edit").html('Add Sip List');


    });
    



    $(document).on("click", ".editLabel", function() {
        $("#myModal").modal();
        $("#add-edit").html('Edit Sip List');
        var edit_id = $(this).data('id');
        $.ajax({
            url: 'sip-channel-provider/' + edit_id,
            type: 'get',
            success: function(response) {
                $("#title").val(response.title);
                $("#channel_provider").val(response.channel_provider);
                $("#id").val(response.id);
                $("#status").val(response.status);



            }
        });
    });

    $(document).on("click", ".deleteLabel", function()
    {
        var delete_id = $("#sip_list_id").val();
        var el = this;
        $.ajax({
            url: 'delete-sip-channel-provider/' + delete_id,
            type: 'get',
            success: function(response) {
                window.location.reload(1);
            }
        });
    });
</script>

@endsection