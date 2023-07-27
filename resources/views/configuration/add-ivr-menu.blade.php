

@extends('layouts.app')
@section('title', 'Add Api List')

@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
                <h1>
                   <b>IVR Menu</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Inbound Configuration</li>
                    <li class="active">IVR Menu</li>
                </ol>
        </section>
   
    <!-- Main content -->
    <section class="content">
        <div class="row">

         <form class="form-inline"  method="post">
             @csrf
                        <div class="panel panel-primary">
                              <!--  <div class="box-body"> -->

@php


 


$labelStr = "<tr><td><input type='text' class='form-control col-md-6' name='para_dtmf[]' value='' class='param' required></td><td><select name='dest_type[]'  class='form-control destType' required='required' id=''><option value=''>Select Dest Type</option>";

foreach($dest_list  as $key=> $dest_type){
                 $labelStr .="<option value=".$dest_type->dest_id.">".$dest_type->dest_type."</option>";
                }



@endphp

                                

                                  <!--   <div class="form-group col-md-2">
                                        <label>Dest</label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                                  <input type="text" class="form-control" name="dest" value="" id="campaign_name" required="">
                                            </div>
                                        </div>
                                    </div>
                                   


                                    
                                   
                                    
                                    <div class="form-group col-md-2">
                                        <label></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range1">
                                                 <button type="submit" style="margin: 3px;" name="submit" class="btn btn-success waves-effect waves-light m-l-10" value="Search">Save
                                                </button>
                                            </div>
                                        </div>
                                    </div> -->
                              

                        
                   <!--  </div> -->

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

                                        
                                    </tbody>
                                </table>



                            </div>


                             <div>
                                <select name="" class="form-control" id="myselect" required>
                                   
                                </select></div>



                            <div id="addRow" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Row</h4>
            </div>
          
                <div class="modal-body" style="display:flex;">
                    <div class="col-md-12">
                        <div class="form-group m-l-10 col-md-6">
                            <label>IVR ID</label>
                            <div class="input-daterange input-group col-md-12">

                                <select name="row_type" class="form-control" id="row_type" required>
                                    <option value="">Select Ivr Id</option>
                                    @foreach($ivr_list as $list)
                                    <option value="{{$list->ivr_id}}">{{$list->ivr_id}}</option>

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
                                    <!-- <option value="10">10</option> -->
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
            labelStr = '';
            for(var i = 0; i < num; i++)
            {


                  var date = new Date();
        var timestamp = date.getTime();

       var idd =  $(this).attr('id',timestamp);


labelStr +="</select> <div><select id='user_"+idd+"' ></select></div>";
labelStr +="</td><td><input type='button' value='Remove_"+idd+"' class='btn  btn-info m-l-10 btn-remove'></td></tr>";

//alert(labelStr);

                $("#param_label").append("<?php echo $labelStr; ?>");
                $("#param_label").append(labelStr);

            }
      
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


     $(document).on("change", ".destType" , function() {

         var dest_id = $(this).val();
         var myDropDownList = $('#user_'+dest_id);
         console.log($(this).siblings("select"));
         $.ajax({ 
            url: 'checkDestType/'+dest_id,
            type: 'get',
            success: function(response){
                console.log(response);
                //var myJSON = JSON.stringify(response);



                if(response.length>0){
                    for(var i = 0; i < response.length; i++) {
                        var obj = response[i];
                        console.log(obj.id);
                        myDropDownList.append($("<option></option>").val(obj.id).html(obj.ivr_id));
                    }

                    // $.each(jQuery.parseJSON(data.d), function () {
                        
                    // });
                }
            }

         });


      // alert(dest_id);


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
