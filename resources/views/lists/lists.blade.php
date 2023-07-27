@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 22px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider_button {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider_button:before {
  position: absolute;
  content: "";
  height: 15px;
  width: 15px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider_button {
  background-color: #00c0ef;
}

input:focus + .slider_button {
  box-shadow: 0 0 1px #00c0ef;
}

input:checked + .slider_button:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider_button.round {
  border-radius: 34px;
}

.slider_button.round:before {
  border-radius: 50%;
}


</style>

<style type="text/css">
.dialog-background{
    background: none repeat scroll 0 0 rgba(244, 244, 244, 0.5);
    height: 100%;
    left: 0;
    margin: 0;
    padding: 0;
    position: absolute;
    top: 0;
    width: 100%;
    z-index: 100;
}

.dialog-loading-wrapper {
    background: none repeat scroll 0 0 rgba(244, 244, 244, 0.5);
    border: 0 none;
    height: 100px;
    left: 50%;
    margin-left: -50px;
    margin-top: -50px;
    position: fixed;
    top: 50%;
    width: 100px;
    z-index: 9999999;
}
</style>

   
<div class="dialog-background" id="loading" style="display: none;">
    <div class="dialog-loading-wrapper">
        <img src="{{ asset('asset/img/lp.gif') }}">
    </div>
</div>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->


      <section class="content-header">
                <h1>
                   <b>List</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Lead Management</li>
                    <li class="active">List</li>
                </ol>
        </section>
        <section class="content-header">

                   <div class="text-right mt-5 mb-3">

               <a id="openListForm" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add List</a>
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
                                <th>List Name</th>
                                <th>Campaign Name</th>

                                <th>Total leads</th>
                                <th>Created Date</th>
                                <th>Status</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>



                                @if(!empty($list_details))
                                @foreach($list_details as $key => $lists)
                            <tr>

                                <td>{{++$key }}</td>
                                <td>{{$lists->list}}</td>
                                <td>@if(!empty($lists->campaign))
                                    {{$lists->campaign}}
                                    @else
                                    ----
                                    @endif</td>

                                <td>{{$lists->rowListData}}</td>

                                <td>{{ \Carbon\Carbon::parse($lists->updated_at)->format('dS M Y')}}</td>
                                <td>
                                        @if($lists->is_active == '0')
                                        <span class="label label-danger">Inactive</span>
                                            
                                        @else ($lists->is_active == '1')
                                        <span class="label label-success">Active</span>
                                        @endif
                                    </td>
                                <td>
                                    <a style="cursor:pointer;color:blue;" href="editList/{{$lists->list_id}}/{{$lists->campaign_id}}" class='editEG'  ><i class="fa fa-edit fa-lg"></i></a>
                                    
                                    @if($lists->is_active == 1)
                                    | <a style="cursor:pointer;color:blue;" class='updateList' href="updateList/{{$lists->list_id}}/0"  ><i class="fa fa-check-square-o fa-lg"></i></a>
                                    @else
                                    | <a style="cursor:pointer;color:gray;" class='updateList' href="updateList/{{$lists->list_id}}/1"  ><i class="fa fa-check-square-o fa-lg"></i></a>
                                    @endif 
                                    | <a style="cursor:pointer;color:red;" class='deleteList' data-camid={{$lists->campaign_id}} data-id={{$lists->list_id}}  ><i class="fa fa-trash fa-lg"></i></a>
                                    @php $strUrl = "list/".$lists->list_id."/content"; @endphp
                                    | <a style="cursor:pointer;color:blue;" href="{{url($strUrl)}}"><i class="fa fa-download fa-lg"></i></a> | <label class="switch"><input data-campaignid="{{$lists->campaign_id}}" data-listid="{{$lists->list_id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $lists->is_active ? 'checked' : '' }}><span class="slider_button round"></span>
</label>
                                </td>



                            </tr>

                            @endforeach
                            @endif


                        </table>



                    </div><!-- /.box-body -->
                </div><!-- /.box -->


            </div><!-- /.col -->


             <div class="modal fade" id="listModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="add-edit"></h4>
        </div>
                <div class="modal-body">
                    <form method="post" action="" enctype="multipart/form-data">
                            @csrf
                    <div class="box-body">

                         <!-- <input type="hidden" class="form-control" name="extension_group" value="" id ="id" required> -->




                         <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="inputEmail3" class="col-form-label">Title <i data-toggle="tooltip" data-placement="right" title="Type list name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <input type="text" class="form-control" required  name="title" id="title" placeholder="Enter Name">
                        </div>


                    </div>

<div class="form-group row">
                    <div class="col-md-12">
                            <label>Duplicate Check <i data-toggle="tooltip" data-placement="right" title="Choose yes/no if you want dublicate entry in excel" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="radio">
                        <label>
                          <input type="radio" name="duplicate_check" id="caller_id_yes" value="1" required>
                         Yes



                        </label>



                         <label>
                          <input type="radio" name="duplicate_check" id="caller_id_no" value="0" required>
                          No


                        </label>


                      </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="form-group col-md-12">
                            <label>File <i data-toggle="tooltip" data-placement="right" title="Upload excel file" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <input type="file" class="form-control" id="list_file" name="list_file" value="" required placeholder="Select file">
                            </div>
                        </div>


                    </div>

                     <div class="form-group row">
                        <div class="form-group col-md-12">
                            <label>Campaign <i data-toggle="tooltip" data-placement="right" title="Select campaign name from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                    <select class="select2" name="campaign" autocomplete="off" data-placeholder="Select Campaign" placeholder="Select Campaign" style="width: 100%;" required>

                                <option value="">Select Campaign</option>
                                                    <?php foreach ($campaign_list as $key=>$value){
                                                        if(!empty($value->title)){
                                                        ?>
                                                        <option value="<?php echo $value->id;?>"><?php echo $value->title;?></option>
                                                    <?php } }?>
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
                    <p>You are about to delete <b><i class="title"></i></b> List.</p>
                    <p>Do you want to proceed?</p>
                      <input type="hidden" class="form-control" name="list_id" value="" id ="list_id" >
                      <input type="hidden" class="form-control" name="camid" value="" id ="camid" >




                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger btn-ok deleteListData">Delete</button>
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
    var oTable = $('#example').dataTable( {
    "aoColumnDefs": [
        { "bSortable": false, "aTargets": [ 4 ] }
    ]
});
} );
    $("#openListForm").click(function(){
    $("#listModal").modal();
    $("#name").val('');
    $("#status").val('1');
    $("#id").val('');
    $("#add-edit").html('Add List');
});


               $(".deleteList").click(function(){
        var delete_id = $(this).data('id');
        var camid = $(this).data('camid');

        //alert(camid);

        $("#delete").modal();
        $("#list_id").val(delete_id);
        $("#camid").val(camid);


    });

                 $(document).on("click", ".deleteListData" , function() {
    //if(confirm("Are you sure you want to delete this?")){
        var list_id = $('#list_id').val();
        var camid = $('#camid').val();

       //alert(group_id);
        var el = this;
        $.ajax({
            url: 'deleteListData/'+list_id+'/'+camid,
            type: 'get',
            success: function(response){
                 window.location.reload(1);
            }
        });
     //   window.location.reload(1);

});
</script>

<script>
    $(function()
    {
        $("#example").on("change", ".toggle-class", function ()
        {
            $('#loading').show();
            var status = $(this).prop('checked') == true ? 1 : 0; 
            var listid = $(this).data('listid');    


            $.ajax({
                type: "GET",
                dataType: "json",
                url: '/updateListStatus/'+listid+'/'+status,
                success: function(data)
                {
                    if(data.status == 'true')
                    {
                        toastr.success(data.message);
                    }
                    else
                    {
                        toastr.error(data.message);

                    }
                    $('#loading').show();

                    console.log(data.success);
                    window.location.reload(1);
                }
            });
        })
    })
</script>
@endsection
