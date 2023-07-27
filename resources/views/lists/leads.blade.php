@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
                <h1>
                   <b>Manage Leads</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Lead Management</li>
                    <li class="active">Manage Leads</li>
                </ol>
    </section>
        

    <!-- Main content -->
    <section class="content">

        <div class="row">


        <div class="alert alert-danger" id="errorMsg" style="display: none;">

                <strong id="mes"></strong>

                <ul>

                   

                </ul>

            </div>
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                               
                                    <form class="form-inline" method="post">

                                        @csrf
                                      

                                        <div class="form-group m-l-10">
                                            <label>List <i data-toggle="tooltip" data-placement="right" title="Select multiple lists" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div>
                                                <div class="input-daterange input-group" id="date-range1">

                                                    <select class="select2" required="required" multiple="multiple" name="list_id[]" id="list_id" autocomplete="off" data-placeholder="Select List" style="width: 100%;">
                                                         <option value="0">All</option> 
                   

                    @foreach($list_details as $key => $lists)
                                                            <option value="{{$lists->list_id}}" >{{$lists->list}}</option>
                                                        @endforeach

                  </select>
                                                   
                                                        
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group m-l-10" id="hiddenHeader" style="display: none;">
                                            <label>Search By <i data-toggle="tooltip" data-placement="right" title="Select search from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div>
                                                <div class="input-daterange input-group" id="date-range1">
                                                   
                                                           <select  required="required" name="header_column" class="form-control" id="header_column">
                                                            

                                                        
                                                    </select>
                                                    
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group m-l-10">
                                            <label>Value <i data-toggle="tooltip" data-placement="right" title="Type value which you search" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div>
                                                <div class="input-daterange input-group" id="date-range1">
                                                   
                                                 <input type="text" required="required" name="header_value" class="form-control" id="header_value">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="form-group m-l-10">
                                            <label></label>
                                            <div>
                                                <div class="input-daterange input-group" id="date-range1">
                                                    <button type="submit" id="submit_searssch"
                                                            class="btn btn-success waves-effect waves-light m-l-10"
                                                            value="Search">Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>


                                    </form>
                                
                            </div> <!-- panel-body -->
                        </div> <!-- panel -->
                    </div> <!-- col -->


                 @isset($leads)
        
            <div class="col-xs-12">
                <div class="box">
                   
                    <div class="box-body">
                        
                             
                        <table id="example" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                 <th>Lead Id</th>
                                <?php 
                                $in_array_heaader = array();
                                $in_array =array('Email','First Name','Last Name','Mobile','State','Legal Company Name');?>
                                <?php foreach($lists_header_array as $header){
                                if(in_array($header->title,$in_array)) { 

                                    $in_array_heaader[] = $header->column_name;
                                    ?>

                                <th>
                                    {{$header->title}}
                                </th>
                                <?php } }?>
                                
                                

                                
                                <th>Action</th>
                               
                            </tr>
                            </thead>
                            <tbody>


                              
                                
                                <?php foreach($leads as $key => $lead){
                                    //echo "<pre>";print_r($in_array_heaader);die;
                                        ?>
                                
                                 <tr>
                                <td><?php echo ++$key; ?></td>

                                <td><a  target="_blank" style="cursor:pointer;color:blue;" href="call-report/{{$lead->id}}"  class='editEG'  >{{$lead->id}}</a> </td>
                                <?php foreach($lists_header_array  as $key1 => $header) {

                                  //  echo "<pre>";print_r($header);die;



                                   $variableName = $header->column_name;

                                    if(in_array($variableName, $in_array_heaader)){
                                        if($header->title == 'Mobile'){
                                            $option = $header->column_name;
                                            $view_report = $lead->$option;

                                        }

                                    ?>
                                
                                <td><?php echo $lead->$variableName; ?></td>

                                <?php } }?>

                                <!-- 

                                <td>{{$lead->option_1}}</td>
                                <td>{{$lead->option_2}}</td>
                                <td>{{$lead->option_3}}</td>
                                <td>{{$lead->option_4}}</td> -->

                                
                               <td><a  target="_blank" style="cursor:pointer;" href="report/{{$view_report}}" class="btn btn-info waves-effect waves-light m-l-10 editEG" >View Call Record</a>  </td>

                               
                            </tr>

                           <?php }?>


                            
                            
                           
                        </table>

                            

                        

                    </div><!-- /.box-body -->
                </div><!-- /.box -->

             
            </div><!-- /.col -->


        </div><!-- /.row -->
        @endisset

    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(document).ready(function() {
    var oTable = $('#example').dataTable( {
    "aoColumnDefs": [
        { "bSortable": false, "aTargets": [ 2,3 ] }
    ]
}); 
} );
$(document).ready(function(){
  $("#submit_search").click(function(){
    var list_id = $("#list_id").val();
    var header_column = $("#header_column").val();
    var header_value  = $("#header_value").val();


    alert(list_id);
    alert(header_column);

     $.ajax({
            url: 'searchLeadColumn/'+list_id+'/'+header_column+'/'+header_value,
            type: 'get',
            success: function(response){
                 //window.location.reload(1);
            }
        });



  });
});

$("#list_id").change(function(){
  data_list = $("#list_id").val();
  //alert(data);

  $.ajax({
            url: 'searchListHeader/'+data_list,
            type: 'get',
            success: function(response){

                //alert(response);
                if(response =='0'){

                    $("#errorMsg").show();
                    $("#mes").html('Whoops! List Header not created.');

                }else{

                $("#hiddenHeader").show();

                $("#header_column").html("");

                //header_column

            // alert(response.length);

             var elem = document.getElementById('header_column');
              


             for(var i = 0; i < response.length; i++) {

                var obj = response[i];

//alert(obj);

elem.innerHTML = elem.innerHTML + '<option value="'+obj.column_name+'">'+obj.title+'</option>';
            

           }


          //$('#hiddenAfterSms').html("");
      }

                 
            }
        });
});
</script>
@endsection