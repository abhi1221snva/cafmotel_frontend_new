@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <style>
        .wrap
        {
            white-space: pre-wrap;      /* CSS3 */   
            white-space: -moz-pre-wrap; /* Firefox */    
            white-space: -pre-wrap;     /* Opera <7 */   
            white-space: -o-pre-wrap;   /* Opera 7 */    
            word-wrap: break-word;      /* IE */
        }
    </style>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height:500px !important">
        <!-- Content Header (Page header) -->

        <section class="content-header">
                <h1>
                   <b>Lead Source Config</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Lead Management</li>
                    <li class="active">Lead Source Config</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                <a href="{{ url('/lead-source-config') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Lead Source Config</a>
           </div>
        </section>
   

        <!-- Main content -->
        <section class="content">
            <div class="row">
                @include('layouts.messaging')
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <table id="example" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Api Key</th>
                                    <th>List</th>
                                    
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if(!empty($leadsourceconfig))
                                @foreach($leadsourceconfig as $key => $apiurl)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$apiurl->title}}</td>
                                    <td>{{$apiurl->description}}</td>

                                    <td>{{$apiurl->api_key}}</td>


                                
                                     
                                    <td>
                                        @foreach($list_details as $list)
                                        @if($list->list_id == $apiurl->list_id)
                                        {{$list->list}}
                                        @endif
                                        @endforeach
                                    </td>
                                    
                                   
                                    <td><a style="cursor:pointer;color:blue;" onclick="makeAPI('{{$apiurl->list_id}}','{{$apiurl->api_key}}');"  class='editEG'><i class="fa fa-eye fa-lg"></i></a> | 

                                        <a style="cursor:pointer;color:red;"  data-configid={{$apiurl->id}}  class='openApiDelete'><i class="fa fa-trash fa-lg"></i></a> 
                                       </td>

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

             


            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content" style="">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="add-edit"></h4>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="inputPassword3" id="" class="col-form-label">API Url</label>
                                <h4 class="wrap"  id="api_url"></h4>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="delete" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content" style="background-color: #d33724 !important;color:white;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                    </div>
                    <div class="modal-body">
                        <p>You are about to delete <b><i class="title"></i></b> this API.</p>
                        <p>Do you want to proceed?</p>
                        <input type="hidden" class="form-control" name="config_id" value="" id="config_id">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger btn-ok deleteKey">Delete</button>  
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

@endsection
@push('scripts')

<script language="javascript"> 
    $(document).ready(function () {
            var oTable = $('#example').dataTable({
                "aoColumnDefs": [{
                    "bSortable": false,
                    "aTargets": [2,3]
                }]
            });
        });

    </script> 


    <script language="javascript">
        $(".openApiDelete").click(function() {
        var delete_id = $(this).data('configid');
        $("#delete").modal();
        $("#config_id").val(delete_id);

    });


        $(document).on("click", ".deleteKey", function() {
        var key_api = $("#config_id").val();
        //alert(key_api);
        var el = this;
        $.ajax({
            url: 'deleteLeadConfig/' + key_api,
            type: 'get',
            success: function(response) {
                window.location.reload(1);
            }
        });

        //}
        /*  else
          {
              return false;
          }*/
    });

    function makeAPI(value,api_key)
    {
        list_id = value;
        api_key = api_key;
        domain_url = "{{ env('PORTAL_NAME') }}";
        //domain_url = 'https://phone.performancemedia.cloud/';

        $.ajax({
            url: 'getLeadHeader/' +list_id,
            type: 'get',
            success: function (response)
            {
                var text = "";
                for (i = 0; i < response.length; i++)
                {
                    text += response[i].header + "=$"+response[i].header+"&";
                }

                url = domain_url+'insertLeadSource?token='+api_key+'&';
                $("#myModal").modal();
                $("#api_url").html(url+''+text.toLowerCase().split(' ').join('_').slice(0, -1));
            }
        });
    }

</script>
    @endpush