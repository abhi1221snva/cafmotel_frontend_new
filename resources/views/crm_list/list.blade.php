@extends('layouts.app')
@section('title', 'Crm List')
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
                   <b>Crm List</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Crm List</li>
                    <li class="active">list</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                   <a href="{{ url('/crm-list') }}" class="btn btn-sm btn-primary">Back</a>
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
            $paginator = new Illuminate\Pagination\LengthAwarePaginator($crm_list,$record_count,$perPage,$currentPage,['path' => url($url)]);
            $record_count = $paginator->total();
        ?>

            <div class="col-xs-12">
                <div class="box">

                    <div class="box-body">
                        <b>Total Rows :<?= $record_count ?></b>
                    <form method="GET" action="">
                    <div class="text-right"style="margin-bottom:10px;">
                            <input type="text" name="search"id="search" placeholder="title or title_url or url" value="{{$searchTerm}}">
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
                            <th>Title Url</th>
                            <th>Url</th>
                            @if((Session::get('level') > 9))
                            <th>Key</th>
                            @endif
                            <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                            $i=1
                            @endphp 
                            @if(!empty($crm_list))
                            @foreach($crm_list as $key => $label_data)
                            <tr>
                            <td>{{ ($paginator->currentPage() - 1) * $paginator->perPage() + $key + 1 }}</td>
                            <td>{{$label_data->title}}</td>
                            <td>{{$label_data->title_url}}</td>
                            <td>{{$label_data->url}}</td>
                            @if((Session::get('level') > 9))
                            <td>
                                <a id="hide_{{$i}}" onmouseover="bigImg({{$i}})"><?=str_repeat("*", strlen($label_data->key));?></a>
                                <a style="display: none;" id="show_{{$i}}" onmouseout="normalImg({{$i}})">{{$label_data->key}}</a>
                            </td>
                            @endif
                            <td>
                                <a style="cursor:pointer;color:blue;" class='editLabel' data-id="{{$label_data->id}}"><i class="fa fa-edit fa-lg"></i></a> 
                                | <a style="cursor:pointer;color:red;" class='openLabelDelete' data-id="{{$label_data->id}}"><i class="fa fa-trash fa-lg"></i></a>
                            </td>
                            </tr>
                            @php
                            $i++
                            @endphp
                            @endforeach
                            @endif
                        </tbody>
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

                    <form method="post" action="">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" class="form-control" name="crm_id" value="" id="id">

                            <label for="inputEmail3" class="col-form-label">Title <i data-toggle="tooltip" data-placement="right" title="Enter Title" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <input type="text" class="form-control" required name="title" id="title" placeholder="Title">

                             <label for="inputEmail3" class="col-form-label">Title Url <i data-toggle="tooltip" data-placement="right" title="Enter Title Url" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <input type="text" class="form-control" required name="title_url" id="title_url" placeholder="Title Url">
                            <label for="inputEmail3" class="col-form-label">Url <i data-toggle="tooltip" data-placement="right" title="Enter  Url" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <input type="text" class="form-control" required name="url" id="url" placeholder="Url">
                            <label for="inputEmail3" class="col-form-label">Key <i data-toggle="tooltip" data-placement="right" title="Enter Key" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <input type="text" class="form-control" required name="key" id="key" placeholder="Key">
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
                        <input type="hidden" class="form-control" name="crm_id" value="" id="crm_list_id">

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
  function bigImg(x)
        {
            $("#show_"+x).show();
            $("#hide_"+x).hide();
        }

        function normalImg(x)
        {
            $("#show_"+x).hide();
            $("#hide_"+x).show();
        }
   </script>
   <script>   
    $(".openLabelDelete").click(function() {
        var delete_id = $(this).data('id');
        $("#delete").modal();
        $("#crm_list_id").val(delete_id);

    });

    $("#openLabelForm").click(function() {
        $("#myModal").modal();
        $("#title").val('');
        $("#title_url").val();
        $("#url").val();
        $("#key").val();
        $("#id").val('');
        $("#add-edit").html('Add Crm List');


    });

    $(document).on("click", ".editLabel", function() {
        $("#myModal").modal();
        $("#add-edit").html('Edit Crm List');
        var edit_id = $(this).data('id');
        $.ajax({
            url: 'crm-list/' + edit_id,
            type: 'get',
            success: function(response) {
                $("#title").val(response.title);
                $("#title_url").val(response.title_url);
                $("#id").val(response.id);
                $("#url").val(response.url);
                $("#key").val(response.key);




            }
        });
    });

    $(document).on("click", ".deleteLabel", function()
    {
        var delete_id = $("#crm_list_id").val();
        var el = this;
        $.ajax({
            url: 'delete-crm-list/' + delete_id,
            type: 'get',
            success: function(response) {
                window.location.reload(1);
            }
        });
    });
</script>
@endsection