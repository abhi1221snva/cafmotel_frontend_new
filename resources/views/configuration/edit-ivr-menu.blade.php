

@extends('layouts.app')
@section('title', 'Edit Ivr Menu List')

@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

      <section class="content-header">
                <h1>
                   <b>Ivr Menu List</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Inbound Configuration</li>
                    <li class="active">Ivr Menu List</li>
                </ol>
        </section>
       
    <!-- Content Header (Page header) -->
    

    <!-- Main content -->
    <section class="content">
        <div class="row">

         <form class="form-inline"  method="post">
             @csrf
                        <div class="panel panel-primary">
                               <div class="box-body">

@php




$labelStr = "<tr><td><input type='text' class='form-control col-md-6' name='para_dtmf[]' value='' class='param' required></td><td><select name='dest_type[]' class='form-control' required='required'><option>Select Dest Type</option>";

foreach($dest_list  as $key=> $dest_type){
                  $labelStr .="<option value=".$dest_type->dest_id.">".$dest_type->dest_type."</option>";
                                                       }

$labelStr .="</select></td><td><input type='button' value='Remove' class='btn  btn-info m-l-10 btn-remove'></td></tr>";

$constantStr = "<tr><td><input type='text' class='form-control' name='para_constant[]' value='' class='param' required></td><td><input type='text' name='constant[]' class='form-control' value='' required='required'>";
$constantStr .="</td><td><input type='button' value='Remove' class='btn  btn-info m-l-10 btn-remove'></td></tr>";


@endphp
                                   <div class="form-group col-md-2">
                                        <label>Dest</label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                                  <input type="text" class="form-control" name="dest" value="{{$dest}}" id="campaign_name" required="">
                                            </div>
                                        </div>
                                    </div>

                                  
                                   

           


                <!-- /.form-group -->
           
                <!-- /.form-group -->
             

                                    
                                   
                                    
                                    <div class="form-group col-md-2">
                                        <label></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range1">
                                                 <button type="submit" name="submit" class="btn btn-success waves-effect waves-light m-l-10" value="Search">Update
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                              

                        
                    </div>
                    </div>



                            <div class="col-xs-12" style="overflow-x: auto;">
                                <a href="#" class="col-sm-2 btn btn-info pull-right add-list" data-toggle="modal" data-target="#addRow">Add Parameter</a>
                                <table class="table" id="api_table">
                                    <thead>
                                    <tr>
                                        <td>Dtmf</td>
                                        <td>Dest Type</td>
                                        <td>Remove</td>
                                    </tr>
                                    </thead>
                                    <tbody id="param_label">

                                       <?php foreach($mapping as $parameter){ ?>
                                       
                                     

                                        <tr><td><input type='text' class='form-control' name='para_dtmf[]' value='{{$parameter->dtmf}}' class='param' required></td><td>
                                            <select name='dest_type[]' class='form-control' required='required'>
                                                @foreach($dest_list  as $key=> $dest_type)


                                                <option @if($parameter->dest_type == $dest_type->dest_id) selected @endif value="{{$dest_type->dest_type}}">{{$dest_type->dest_type}}</option>
                                                @endforeach

                                                
                                            </select></td><td><input type='button' value='Remove' class='btn  btn-info m-l-10 btn-remove'></td></tr>
                                       
                                        
                                       
 
                                     <?php    }
                                        ?>
                                    </tbody>
                                </table>
                            </div>



                            <div id="addRow" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Row</h4>
            </div>
          
                <div class="modal-body" style="display:flex;">
                     <div class="form-group m-l-10 col-md-6">
                            <label>IVR ID</label>
                            <div class="input-daterange input-group col-md-12">

                                <select name="row_type" class="form-control" id="row_type" required>
                                   
                                   
                                    @foreach($ivr_list as $list)
                                    <option @if($ivr_id == $list->ivr_id) selected @endif value="{{$list->ivr_id}}">{{$list->ivr_id}}</option>

                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-l-10 col-md-4">
                            <label>Number Of Rows</label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="row_count" class="form-control" id="row-count" required>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" name ="submit" value="add" class="btn btn btn-dark waves-effect waves-light" onclick="addRow()">Continue</button>
                    <button type="button" class="btn btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button>
                </div>
           
        </div>

    </div>
</div>

</form>
                       


                 


        </div><!-- /.row -->
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    
///////////////api //////////////


   $( document ).ready(function() {
       
        displayTable();
    });


    function addRow() {
        $('#addRow .close').trigger( "click" );
        var typ = $('#row_type').val();
        var num = $('#row-count').val();
        /*if(typ == "label")
        {*/
            for(var i = 0; i < num; i++)
            {
                $("#param_label").append("<?php echo $labelStr;?>");
            }
       /* }
        else if(typ == "constant")
        {
            for(var i = 0; i < num; i++) {
                $("#param_label").append("<?php echo $constantStr;?>");
            }
        }*/
        displayTable();
        return false;
    }
  
    function displayTable() {
        var rowCount = $('#param_label tr').length;
        if(rowCount > 0)
        {
            $('#api_table').removeClass('hidden');
        }
        else
        {
            $('#api_table').addClass('hidden');
        }

    }
    $('#api_table').on('click', '#param_label .btn-remove', function() {
        $(this).closest("tr").remove();
        displayTable();
    });



    $(document).on("click", ".deleteApi" , function() {
    if(confirm("Are you sure you want to delete this record?")){
        var api = $(this).data('api');
        // alert(api);
        //var account_no = $(this).data('account_no');
        //alert(account_no);
        var el = this;
        $.ajax({
            url: 'deleteApi/'+api,
            type: 'get',
            success: function(response){
                alert(response);
            }
        });
        window.location.reload(1);
    }
    else{
        return false;
    }
});


    $("#openLabelForm").click(function(){
        $("#myModal").modal();
        $("#name").val('');
        $("#status").val('1');
        $("#id").val('');
        $("#add-edit").html('Add Label');
    });


    $(document).on("click", ".editLabel" , function() {
    $("#myModal").modal();
    $("#add-edit").html('Edit Label');
    var edit_id = $(this).data('id');
    $.ajax({
        url: 'editLabel/'+edit_id,
        type: 'get',
        success: function(response){
            $("#name").val(response.name);
            $("#status").val(response.status);
            $("#id").val(response.id);
        }
    });
});


    $(document).on("click", ".deleteLabel" , function() {
    if(confirm("Are you sure you want to delete this?")){
        var delete_id = $(this).data('id');
        var el = this;
        $.ajax({
            url: 'deleteLabel/'+delete_id,
            type: 'get',
            success: function(response){
                $(el).closest( "tr" ).remove();
            }
        });
        window.location.reload(1);
    }
    else
    {
        return false;
    }
});


//////////////close api ///////////////


</script>
@endsection
