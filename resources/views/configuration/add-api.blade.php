@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <b>List Campaign</b>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Configuration</li>
            <li class="active">List Campaign</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
            <form class="form-inline"  method="post">
                @csrf
                <div class="panel panel-primary">
                    <div class="box-body">
                        @php
                        $labelStr = "<tr><td><input type='text' class='form-control col-md-6' name='para_label[]' value='' class='param' required></td><td><select name='label[]' class='form-control' required='required'><option>Select Label</option>";

                            foreach($label_list  as $label)
                            {
                                $labelStr .="<option value=".$label->id.">".$label->title."</option>";
                            }

                        $labelStr .="</select></td><td><button class='btn btn-danger btn-remove'><i class='fa fa-trash'></i></button></td></tr>";

                        $constantStr = "<tr><td><input type='text' class='form-control' name='para_constant[]' value='' class='param' required></td><td><input type='text' name='constant[]' class='form-control' value='' required='required'>";

                        $constantStr .="</td><td><button class='btn btn-danger btn-remove'><i class='fa fa-trash'></i></button></td></tr>";
                        @endphp

                        <div class="row">
                            <div class="col-md-3">
                                <label>Name <i data-toggle="tooltip" data-placement="right" title="Type API name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <div>
                                        <div class="input-daterange input-group col-md-12" id="date-range2">
                                            <input  type="text" class="form-control" name="title" value="" id="campaign_name" required="">
                                        </div>
                                    </div>
                            </div>

                            <div class="col-md-6">
                                <label>Url <i data-toggle="tooltip" data-placement="right" title="Type url" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                <div>
                                    <div class="input-daterange input-group col-md-12" id="date-range2">
                                        <input type="text" class="form-control" name="url" value="" id="campaign_name" required="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label>Method <i data-toggle="tooltip" data-placement="right" title="Select method type : GET/POST from the drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                <div>
                                    <div class="input-daterange input-group col-md-12" id="date-range2">
                                        <select class="form-control" name='method'>
                                            <option value="get">GET</option>
                                            <option value="post">POST</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-md-3">
                                <label>Campaign <i data-toggle="tooltip" data-placement="right" title="Select campaign from the drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <select class="form-control"  name="campaign_id" autocomplete="off" data-placeholder="Select Group" style="width: 100%;">
                                        @foreach($campaign_list  as $camp)
                                            @if(!empty($camp->title))
                                                <option  value="{{$camp->id}}">{{$camp->title}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                            </div>

                            <div class="col-md-6">
                                <label>Disposition <i data-toggle="tooltip" data-placement="right" title="Select disposition from the drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <select class="select2" multiple="multiple" name="disposition_id[]" autocomplete="off" data-placeholder="Select Disposition" style="width: 100%;">

                                    @foreach($disposition_list as $dispo)
                                        <option  value="{{$dispo->id}}">{{$dispo->title}}</option>
                                    @endforeach;
                                    </select>
                            </div>

                              <div class="col-md-3">
                                <label>Set API Template <i data-toggle="tooltip" data-placement="right" title="Select yes/no for set API Template" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                <select class="form-control"  name="is_default" autocomplete="off" data-placeholder="Select Group" style="width: 100%;">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            
                        </div>

                        <br>

                        <div class="modal-footer">
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
                        </div>
                    </div>
                </div>

                 <div class="col-xs-12" style="overflow-x: auto;">
                    <a href="#" class="col-sm-2 btn btn-info pull-right add-list" data-toggle="modal" data-target="#addRow">Add Parameter
                    </a>

                    <table class="table table-bordered table-striped nowrap" id="api_table">
                        <thead>
                            <tr>
                                <td>Parameter</td>
                                <td>Data</td>
                                <td>Remove</td>
                            </tr>
                        </thead>
                        <tbody id="param_label">
                        </tbody>
                    </table>
                </div>

                <div id="addRow" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Add Row</h4>
                            </div>
                            <div class="modal-body" style="display:flex;">
                                <div class="col-md-12">
                                    <div class="form-group m-l-10 col-md-6">
                                        <label>Type <i data-toggle="tooltip" data-placement="right" title="Select type" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select name="row_type" class="form-control" id="row_type" required>
                                                <option value="label">Parameter & Label</option>
                                                <option value="constant">Parameter & constant</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group m-l-10 col-md-4">
                                        <label>Number Of Rows <i data-toggle="tooltip" data-placement="right" title="Select number of rows" class="fa fa-info-circle" aria-hidden="true"></i></label>
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
        </div>
    </section>
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
        if(typ == "label")
        {
            for(var i = 0; i < num; i++)
            {
                $("#param_label").append("<?php echo $labelStr;?>");
            }
        }
        else if(typ == "constant")
        {
            for(var i = 0; i < num; i++) {
                $("#param_label").append("<?php echo $constantStr;?>");
            }
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
