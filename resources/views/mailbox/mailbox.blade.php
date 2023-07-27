@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

     <section class="content-header">
                <h1>
                   <b>Mailbox Reports</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    
                    <li class="active"> Mailbox Reports</li>
                </ol>
        </section>
    
    <!-- Main content -->
    <section class="content">
 <div class="row">

          <div class="col-xs-12">

                
                <div class="box">

                  




                    
                    <div class="box-body">

<form class="form-inline" method="post">

      @csrf
                                   
                                     <!-- @if(Session::get('role') == 'admin')
                                        <div class="form-group m-l-10">
                                        <label>Extension</label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                                <select class="form-control" name="extension" id="extension">
                                                    <option value="">Select</option>

                                                    

                                                     @foreach($extension_list as $key => $extension)
                                                    <option value="{{$extension->extension}}">{{$extension->extension}}</option>
                                                    @endforeach

                                                   
                                                   
                                                     </select>
                                            </div>
                                        </div>
                                    </div>
                                    @endif -->
                                   

                                  
                                   
                                    <div class="form-group m-l-10">
                                        <label>Date Range <i data-toggle="tooltip" data-placement="right" title="Select date range for mailbox report" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div>
                                            <div class="input-daterange input-group datepicker" id="date-range" style="width: 270px;">
                                                <input type="text" autocomplete="off" class="form-control col-md-6" id="start_date" name="start_date" value="<?php 
                                                if(!empty(request()->input('start_date'))){
                                                    echo request()->input('start_date');
                                                    }else{
                                                        $current_date = date("Y-m-d");// current date
                                                $str_date = strtotime(date("Y-m-d", strtotime($current_date)) . " -15 day");
                                                echo  date('Y-m-d',$str_date); 
                                                    } ?>">
                                                <span class="input-group-addon bg-primary text-white b-0">to</span>
                                                <input type="text" autocomplete="off" class="form-control col-md-6 datepicker" id="end_date" name="end_date" value="<?php 
                                                if(!empty(request()->input('end_date'))){
                                                    echo request()->input('end_date');
                                                    }else{
                                                        echo  date('Y-m-d'); 
                                                    } ?>">
                                            </div>
                                        </div>
                                    </div>

                                      
                                    <div class="form-group">
                                        <label></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range1">
                                                <button type="submit" name="submit" class="btn btn-success waves-effect waves-light m-l-10" value="Search">Search
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                  <!--    <div class="form-group">
                                        <label></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range1">
                                                <button type="button" name="submit" class="btn btn-success waves-effect waves-light m-l-10" value="Search">Download
                                                </button>
                                            </div>
                                        </div>
                                    </div> -->
                                </form>

                        
                    </div>
                </div>
            </div>





           <?php  if(!empty($report)){ 


           
          if($lower_limit == '0'){
            $lower_limit =0;
          }


           if($lower_limit > 0){
            $lower_limit = $lower_limit -10;
          }

           $currentPage  = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            // Items per page
            $perPage      = 10;

// Get current items calculated with per page and current page
//$currentItems = array_slice($report, $perPage * ($currentPage - 1), $perPage);

// Create paginator
//$paginator = new Illuminate\Pagination\Paginator($report, 10, $currentPage);

$paginator = new Illuminate\Pagination\LengthAwarePaginator($report, $record_count, $perPage, $currentPage,['path'=>url('mailbox')]);

 //echo "<pre>";print_r($report);
     //       echo "<pre>";print_r($record_count);die;


            ?>



            <div class="col-xs-12">


                <div class="box">

                  




                    
                    <div class="box-body">
                        <button style="margin-bottom: 10px" class="btn btn-primary delete_all" data-url="{{ url('deleteAll') }}">Delete All Selected</button>
                        <b>Total Rows :<?= $record_count ?></b>

                         <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th width="50px"><input type="checkbox" id="master"></th>
                                <th>#</th>
                                <th>Caller</th>
                                <th>Extension</th>
                                <th>Status</th>
                               <th>Received Date</th>
                               
                              
                               <th>Recording</th>
                               <th>Action</th>



                            </tr>
                            </thead>
                            <tbody>
                               
                                <?php 


                               
                                $k=$lower_limit;
                                foreach ($paginator->items() as $key=>$value){ ?>
                                    <tr id="tr_{{$value->id}}">
                                        <td><input type="checkbox" class="sub_chk" data-id="{{$value->id}}"></td>
                                        <td><?php echo ++$k; ?></td>
                                        <td><?php echo $value->ani;?></td>
                                        <td><?php echo $value->extension;?></td>
                                         <td>
                                    @if($value->status == '1')
                                    <span class="label label-warning">Active</span>
                                    @else ($value->status == '0')
                                    <span class="label label-success">Inactive</span>
                                    @endif

                                    </td>
                                        <td><?php echo $value->date_time;?></td>
                                        
                                        <td ><audio onplay="changeStatus('{{$value->status}}','{{$value->id}}')" controls preload ='none'><source src="<?php echo $value->vm_file_location;?>" type='audio/wav'></audio></td>
                                        <td>
                                            <a style="cursor:pointer;color:red;"  class='openDeleteMailBox' data-id={{$value->id}}><i class="fa fa-trash fa-lg"></i></a>
                                         
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            {{$paginator->appends(Request::all())->links()}}
                         <!-- <?=$paginator->render()?> -->
                        </table>
                        

                      
                         
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

              
            </div><!-- /.col -->

        <?php }?>


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
                            <input type="hidden" class="form-control" name="mailbox_id" value="" id="mailbox_id">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger btn-ok deleteMailbox">Delete</button>
                        </div>
                    </div>
                </div>

            </div>




 
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>

    function changeStatus(value,id){
        if(value == 1){

            //alert(id);
            //alert(value);

            var status_id = value;
            var mailbox_id = id;

             $.ajax({
            url: 'statusMailBox/' + status_id + '/'+mailbox_id,
            type: 'get',
            success: function(response) {
                //window.location.reload(1);
            }
        });

        }
    }


    $(".openDeleteMailBox").click(function() {
        var delete_id = $(this).data('id');
        $("#delete").modal();
        $("#mailbox_id").val(delete_id);

    });


    $(document).on("click", ".deleteMailbox" , function() {
    //if(confirm("Are you sure you want to delete this?")){
        var delete_id = $("#mailbox_id").val();
      
        var el = this;
        $.ajax({
            url: 'deleteMailbox/'+delete_id,
            type: 'get',
            success: function(response){

                window.location.reload(1);
               
            }
        });
        
    // }
    // else
    // {
    //     return false;
    // }

});


       /* $(document).on("click", ".changeStatus", function() {
        // if(confirm("Are you sure you want to delete this?")){

        var delete_id = $("#dnc_number").val();

        var el = this;
        $.ajax({
            url: 'deleteDnc/' + delete_id,
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
    //});*/
</script>

<script type="text/javascript">
    $(document).ready(function ()
    {
        $('#master').on('click', function(e)
        {
            if($(this).is(':checked',true))
            {
                $(".sub_chk").prop('checked', true);
            }
            else
            {
                $(".sub_chk").prop('checked',false);  
            }
        });

        $('.delete_all').on('click', function(e)
        {
            var allVals = [];  
            $(".sub_chk:checked").each(function()
            {  
                allVals.push($(this).attr('data-id'));
            });  

            if(allVals.length <=0)  
            {  
                alert("Please select row.");  
            }
            else
            {
                var check = confirm("Are you sure you want to delete this row?");
                if(check == true)
                {
                    var join_selected_values = allVals.join(",");
                    $.ajax({
                        url: $(this).data('url'),
                        type: 'DELETE',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: 'ids='+join_selected_values,
                        success: function (data)
                        {
                            if (data['success'])
                            {
                                $(".sub_chk:checked").each(function()
                                {
                                    $(this).parents("tr").remove();
                                });
                            }
                            else if (data['error'])
                            {
                                alert(data['error']);
                            }

                            else
                            {
                                alert('Whoops Something went wrong!!');
                            }
                        },

                        error: function (data)
                        {
                            alert(data.responseText);
                        }
                    });

                    $.each(allVals, function( index, value )
                    {
                        $('table tr').filter("[data-row-id='" + value + "']").remove();
                        window.location.reload(1);
                    });
                }
            }
        });

        $(document).on('confirm', function (e)
        {
            var ele = e.target;
            e.preventDefault();
            $.ajax({
                url: ele.href,
                type: 'DELETE',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data)
                {
                    if (data['success'])
                    {
                        $("#" + data['tr']).slideUp("slow");
                        alert(data['success']);
                    }
                    else if (data['error'])
                    {
                        alert(data['error']);
                    } 
                    else
                    {
                        alert('Whoops Something went wrong!!');
                    }
                },
                error: function (data)
                {
                    alert(data.responseText);
                }
            });
            return false;
        });
    });

</script>

@endsection