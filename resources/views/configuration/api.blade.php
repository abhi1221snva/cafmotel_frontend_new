@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
                <h1>
                   <b>API</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Configuration</li>
                    <li class="active">API</li>
                </ol>
        </section>

        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                 
           <a href="{{ url('/add-api') }}"  type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add API</a> 

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
                                <th>Campaign</th>
                                <th>URL</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>API Template</th>

                                <th>Created</th>
                               
                                
                               <th>Action</th>
                                


                                
                            </tr>
                            </thead>
                            <tbody>

                                @if(!empty($api_list))
                        @foreach($api_list as $key => $api)
                            <tr>

                                 <td>{{++$key}}</td>
                                <td>{{$api->title}}</td>
                               
                                <td>{{$api->campaign }}</td>
                                <td>{{$api->url }}</td>
                                <td>{{$api->method }}</td>
                                <td>
                                    @if($api->is_deleted == '0')
                                    <span class="label label-success">Active</span>
                                    @else ($api->is_deleted == '1')
                                    <span class="label label-warning">Inactive</span>
                                    @endif

                                    </td>

                                     <td>
                                    @if($api->is_default == '0')
                                    <span class="label label-warning">No</span>
                                    @else ($api->is_default == '1')
                                    <span class="label label-success">Yes</span>
                                    @endif

                                    </td>
                                <td>{{ \Carbon\Carbon::parse($api->updated_at)->format('dS M Y')}}</td>

                               <td>  
                                   <a style="cursor:pointer;color:blue;" href="{{url('edit-api')}}/{{$api->id}}" class='' data-id={{$api->id}} ><i class="fa fa-edit fa-lg"></i></a> 
                                   | <a style="cursor:pointer;color:blue;" href="{{url('copy-api')}}/{{$api->id}}" class='' data-id={{$api->id}} ><i class="fa fa-copy fa-lg"></i></a> 
                                   | <a style="cursor:pointer;color:red;"  class='openApiDelete' data-api={{$api->id}} ><i class="fa fa-trash fa-lg"></i></a></td>
                                
                               

                                
                            </tr>
                        @endforeach   
                        @endif
                        </table>

                       
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

              
            </div><!-- /.col -->







 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="add-edit"></h4>
        </div>
                <div class="modal-body">
                    <form method="post" action="">
                            @csrf
                    <div class="box-body">

                         <input type="hidden" class="form-control" name="disposition" value="" id ="id" required>


                            <input type="hidden" class="form-control" name="username" value="" id ="first-name" required>

                         <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="inputEmail3" class="col-form-label">Name</label>
                            <input type="text" class="form-control" required  name="name" id="name" placeholder="Enter Name">
                        </div>

                        
                    </div>


                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="inputPassword3"  id="" class="col-form-label">Status</label>
                    
                      <select class="form-control" required id="status" name="status" >

                          
                          <option value="1">Active</option>
                          <option value="0">Inactive</option>

                      </select>
                        </div>

                       
                    </div>



                    <button type="submit" name ="submit"  class="btn btn btn-primary waves-effect waves-light">Save</button>
                  

                    </div><!-- /.box-body -->
                </form>
        </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> -->
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
                    <p>You are about to delete <b><i class="title"></i></b> Api.</p>
                    <p>Do you want to proceed?</p>
                      <input type="hidden" class="form-control" name="api_id" value="" id ="api_id" >


                          
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

                    <button type="button" class="btn btn-danger btn-ok deleteApi">Delete</button>
                </div>
            </div>
        </div>
       
      
      
    
  </div>
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    $(document).ready(function() {
        var oTable = $('#example').dataTable({
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [2, 3]
            }]
        });
    });

    $(".openApiDelete").click(function(){
        var delete_id = $(this).data('api');
        $("#delete").modal();
        $("#api_id").val(delete_id);
       
    });

     $(document).on("click", ".deleteApi" , function() {
    //if(confirm("Are you sure you want to delete this record?")){
        var api = $("#api_id").val();
       
        var el = this;
        $.ajax({
            url: 'deleteApi/'+api,
            type: 'get',
            success: function(response){
        window.location.reload(1);
               
            }
        });
    // }
    // else{
    //     return false;
    // }
});
    </script>
@endsection