@extends('layouts.app')
@section('title', 'Add Tariff Label Values')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
                <h1>
                   <b>Tariff Labels Values</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Tariff Plans</li>
                    <li class="active">Add Tariff Labels Values</li>
                </ol>
        </section>

        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                 
            <a href="{{ url('/tariff-label-values') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Show Tariff Label Value</a>

           </div>
        </section>

    <!-- Main content -->

    <section class="content">
        <div class="row">
            <div class="col-md-12">
            <form class="form-inline"  method="post">
                @csrf
                <div class="panel panel-primary">
                    <div class="box-body">
                        <table class="table table-bordered table-striped nowrap">
                            <thead>
                                
                                <th>Tariff Plan Name <i data-toggle="tooltip" data-placement="right" title="Select Tariff Plan Title" class="fa fa-info-circle" aria-hidden="true"></i></th>
                                
                                <th>Country Name <i data-toggle="tooltip" data-placement="right" title="Select Country for set rate" class="fa fa-info-circle" aria-hidden="true"></i></th>

                                <th>Rate <i data-toggle="tooltip" data-placement="right" title="Type rate" class="fa fa-info-circle" aria-hidden="true"></i></th>
                                
                                <th>Action <i data-toggle="tooltip" data-placement="right" title="Click to action" class="fa fa-info-circle" aria-hidden="true"></i></th>
                            </thead>

                            <tbody id="ivr_level_body">
                                <tr>
                                    <td>
                                        <div class="input-group col-md-12">
                                            <select class="form-control" name='tariff_id'>
                                                @foreach($tariff_plans as $plan)
                                                    <option value="{{$plan->id}}">{{$plan->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group col-md-12">
                                            <select class="form-control" name='phone_countries_id[]'>
                                                @foreach($phone_country as $phone)
                                                    <option value="{{$phone->id}}">{{$phone->country_name}} (+{{$phone->country_code}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group col-md-12">
                                            <input onkeypress="return isNumberKey(event)"  type="text" class="form-control" name="rate[]" value="" id="campaign_name" required="">
                                        </div>
                                    </td>

                                    <td>
                                        <button type="button" class="btn btn-success"onclick="addIvrRow();" title="Add More"><i class="fa fa-plus"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                                
                            <tfoot>
                                <tr>
                                    <td>
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-check-square-o fa-lg"></i> 
                                                        Submit
                                        </button>
                                            
                                        &nbsp;
                                        
                                        <a type="button" class="btn btn-warning" style="margin-right: 5px;" onclick="window.location.reload();">
                                        <i class="fa fa-refresh fa-lg"></i> 
                                        Reset
                                        </a>
                                            
                                        &nbsp;
                                        <a type="button" class="btn btn-danger" style="margin-right: 5px;" href="{{url('/api-data')}}"><i class="fa fa-reply fa-lg"></i> 
                                        Cancel
                                        </a>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>                       
                    </div>
                </div>

                

                
            </form>
        </div>
    </div>
</section><!-- /.content -->
</div>

<script>
    
///////////////api //////////////


   $( document ).ready(function() {
       
        displayTable();
    });


    
  
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

<script type="text/javascript">
     function addIvrRow() {
        var html ='<tr>\n\
        <td></td><td><div class="input-group col-md-12"><select name="phone_countries_id[]" class="form-control"><?php foreach($phone_country as $phone){ ?><option value="{{$phone->id}}">{{$phone->country_name}} (+{{$phone->country_code}})</option><?php }?></select></div></td>\n\
<td><div class="input-group col-md-12"><input onkeypress="return isNumberKey(event)" type="text" class="form-control" name="rate[]" required=""></div></td>\n\
<td><button onclick="$(this).parent().parent().remove();" class="btn btn-danger"><i class="fa fa-trash"></i></button></td></tr>';
        $('#ivr_level_body').append(html);
    }
</script>

<SCRIPT language=Javascript>
       <!--
       function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
       //-->
    </SCRIPT>
@endsection
