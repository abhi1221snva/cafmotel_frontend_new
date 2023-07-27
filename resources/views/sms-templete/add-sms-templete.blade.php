@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->


<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
                <h1>
                   <b>Add SMS Template</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">SMS</li>
                    
                    <li class="active">Add SMS Template</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
               <a href="{{ url('/sms-templete') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show SMS Templete</a>
           </div>
        </section>

        


    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                   
                    <div class="box-body">
                         <form method="post" name="userform" id="userform" action="">
                            @csrf
                         	
                <div class="modal-body">
                    <div class="form-group m-b-10">

                         <div class="col-md-3">
                             <label>Templete Name <i data-toggle="tooltip" data-placement="right" title="Type sms template name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <input type="text" class="form-control" name="templete_name" >
                            </div>
                        </div>
                            <div class="col-md-3">
                            <label>Labels <i data-toggle="tooltip" data-placement="right" title="Select label from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                            <select id="multiple_labels" class="select2"  multiple="multiple"  autocomplete="off" data-placeholder="Select Labels" style="width: 100%;">
                                @foreach($label_list as $list)
                                    <option value="<?php echo '{'.$list->title.'}' ?>">{{$list->title}}</option>
                                @endforeach;
                            </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Sender Details <i data-toggle="tooltip" data-placement="right" title="Select sender details" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                            <select id="multiple_names" class="select2"  multiple="multiple"  autocomplete="off" data-placeholder="Select Names" style="width: 100%;">
                                @foreach($user_column as $user_list)
                                    <option value="<?php echo '{'.$user_list.'}' ?>">{{$user_list}}</option>
                                @endforeach;
                            </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                                        <label>Custom Placeholders <i data-toggle="tooltip" data-placement="right" title="Select Sender details" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            
                                            <select id="multiple_custom_names" class="select2"  multiple="multiple"  autocomplete="off" data-placeholder="Select Names" style="width: 100%;">
                                                <option value="">Select to Insert</option>
                                                @foreach($custom_field_labels as $label_list)
                                                <option value="<?php echo '{'.$label_list->title.'}' ?>">{{$label_list->title}}</option>
                                                @endforeach;
                                            </select>
                                        </div>
                                    </div>

                       


                        <div class="col-md-12">
                             <label>Templete Preview <i data-toggle="tooltip" data-placement="right" title="Add or modify Templete Preview" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <textarea type="text" class="form-control" name="templete_desc" value="" id ="message"></textarea>
                            </div>
                        </div>


                    </div>


                    
                                              
                    </div>
                    
                  
                    <div style="clear: both"></div>
                </div>
                <div class="modal-footer">
                   <!--  <input type="hidden" name="extension" value="" id="extension-edit-id"/> -->
                    <button type="submit" name ="submit" value="add" class="btn btn btn-primary waves-effect waves-light">Save</button>
                   <!--  <button type="button" class="btn btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button> -->
                </div>
            </form>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

               
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    function displayVals() {
       // $( "#message" ).val('');
        var singleValues = $( "#message" ).val();
        var multipleValues = $( "#multiple_labels" ).val() || [];
        $( "#message" ).val(singleValues+' '+ multipleValues.join( " " ) );

        $( "#multiple_labels" ).val('');
    }
    $( "#multiple_labels" ).on('change',function(){ 
        displayVals();
    });


     function displayValsNames() {
        var singleValues = $( "#message" ).val();
        var multipleValues = $( "#multiple_names" ).val() || [];
        var input = singleValues+','+ multipleValues.join( " " );
        var splitted = input.split(',');
        var collector = {};

        for (i = 0; i < splitted.length; i++) {
            key = splitted[i].replace(/^\s*/, "").replace(/\s*$/, "");
            collector[key] = true;
        }

        var out = [];
        for (var key in collector) {
            out.push(key);
        }
        var output = out.join('');
       // alert(output);
        $( "#message" ).val(output);

        $( "#multiple_names" ).val('');

    }


    $( "#multiple_names" ).on('change',function(){ 
        displayValsNames();
    });

$('#multiple_labels').on('select2:select', function (e) {
    var data = e.params.data;
	$("#multiple_labels").val(null).trigger("change");
});

function displayCustom() {
    var singleValues = $( "#message" ).val();
    var multipleValues = $( "#multiple_custom_names" ).val() || [];
    $( "#message" ).val(singleValues+' '+ multipleValues.join( " " ) );
    $( "#multiple_custom_names" ).val('');
}

$( "#multiple_custom_names" ).on('change',function() { 
    displayCustom();
});

$('#multiple_custom_names').on('select2:select', function (e) {
    $("#multiple_custom_names").val(null).trigger("change");
});

$('#multiple_names').on('select2:select', function (e) {
   // var data = e.params.data;
	$("#multiple_names").val(null).trigger("change");
});

</script>
@endsection


