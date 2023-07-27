@extends('layouts.app')

@section('content')




<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
     <section class="content-header">
                <h1>
                   <b>Recyle Rules</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Lead Management</li>
                    <li class="active">Recyle Rules</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                <a id="" href="add-recycle" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Recycle Rule</a>
           </div>
        </section>
  

    <!-- Main content -->
    <section class="content">
        <div class="row">


         <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <form class="form-inline" method="post">
                                    @csrf
                                    <div class="form-group m-l-10">
                                        <label>Campaign <i data-toggle="tooltip" data-placement="right" title="Select campaign name you wish to search recycle rule for " class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                                <select name="campaign_id" class="form-control" id="search-campaign-rule" required>
                                                    <option value="">Select</option>
                                                    <?php foreach ($campaign_list as $key=>$value){
                                                        if(!empty($value->title)){?>
                                                        <option @if($value->id == request()->input('campaign_id'))  selected @endif value="{{$value->id}}">{{$value->title}}</option>

                                                        
                                                    <?php } }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-l-10">
                                        <label>List <i data-toggle="tooltip" data-placement="right" title="Select List name you wish to search" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                                <select name="list_id" class="form-control" id="search-list-rule">
                                                    <option value="">Select List</option>
                                                    <?php foreach ($list_details as $key=>$value){?>
                                                        <option @if($value->list_id == request()->input('list_id'))  selected @endif value="{{$value->list_id}}">{{$value->list}}</option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-l-10">
                                        <label>Disposition <i data-toggle="tooltip" data-placement="right" title="Select disposition name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                                <select name="disposition_id" class="form-control" id="search-disposition-rule">
                                                    <option value="">Select Disposition</option>
                                                    <?php foreach ($disposition_list as $key=>$value){?>
                                                        <option @if($value->id == request()->input('disposition_id'))  selected @endif value="{{$value->id}}">{{$value->title}}</option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-l-10">
                                        <label>Day <i data-toggle="tooltip" data-placement="right" title="Select day" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range1">
                                                <select name="days" class="form-control" id="search-days-rule">
                                                    <option value="">Select Day</option>

                                                    <option @if(request()->input('days') =='sunday')  selected @endif  value="sunday">Sunday</option>
                                                    <option @if(request()->input('days') =='monday')  selected @endif  value="monday">Monday</option>
                                                    <option @if(request()->input('days') =='tuesday')  selected @endif  value="tuesday">Tuesday</option>
                                                    <option @if(request()->input('days') =='wednesday')  selected @endif  value="wednesday">Wednesday</option>
                                                    <option @if(request()->input('days') =='thursday')  selected @endif  value="thursday">Thursday</option>
                                                    <option @if(request()->input('days') =='friday')  selected @endif  value="friday">Friday</option>
                                                    <option @if(request()->input('days') =='saturday')  selected @endif  value="saturday">Saturday</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-l-10">
                                        <label>Call Time <i data-toggle="tooltip" data-placement="right" title="Select number of repititions after which you wish to recycle" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range1">
                                                <select name="call_time" class="form-control" id="search-calltime-rule">
                                                    <option value="">Select Call Time</option>

                                                    <option @if(request()->input('call_time') =='1')  selected @endif value='1'>1</option>
                                                    <option @if(request()->input('call_time') =='2')  selected @endif value='2'> less than or equal to 2</option>
                                                    <option @if(request()->input('call_time') =='3')  selected @endif value='3'> less than or equal to 3</option>
                                                    <option @if(request()->input('call_time') =='4')  selected @endif value='4'>less than or equal to 4</option>
                                                    <option @if(request()->input('call_time') =='5')  selected @endif value='5'>less than or equal to 5</option>
                                                    <option @if(request()->input('call_time') =='6')  selected @endif value='6'>less than or equal to 6</option>
                                                    <option @if(request()->input('call_time') =='7')  selected @endif value='7'>less than or equal to 7</option>
                                                    <option @if(request()->input('call_time') =='8')  selected @endif value='8'>less than or equal to 8</option>
                                                    <option @if(request()->input('call_time') =='9')  selected @endif value='9'>less than or equal to 9</option>
                                                    <option @if(request()->input('call_time') =='10')  selected @endif value='10'>less than or equal to 10</option>
                                                    <option @if(request()->input('call_time') =='11')  selected @endif value='11'>less than or equal to 11</option>
                                                    <option @if(request()->input('call_time') =='12')  selected @endif value='12'>less than or equal to 12</option>
                                                    <option @if(request()->input('call_time') =='13')  selected @endif value='13'>less than or equal to 13</option>
                                                    <option @if(request()->input('call_time') =='14')  selected @endif value='14'>less than or equal to 14</option>
                                                    <option @if(request()->input('call_time') =='15')  selected @endif value='15'>less than or equal to 15</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-l-10">
                                        <label>Time <i data-toggle="tooltip" data-placement="right" title="Choose date range" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div>
                                            <div class="input-daterange input-group" style="width: 270px;">
                                                <input type="time" class="form-control " value="09:30" name="time" id="timepicker6">
                                                <span class="input-group-addon bg-primary text-white b-0">to</span>
                                                <input type="time" class="form-control " value="09:30" name="time" id="timepicker9">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range1">
                                                <button type="submit" name="submit"
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
            <div class="col-xs-12">
                <div class="box">
                    
                    <div class="box-body">

                        <table id="example" class="table table-bordered table-hover">

                                <thead>
                                     <!-- <tr><th><button type="submit"  name="submit"
                                                        class="btn btn-danger waves-effect waves-light m-l-10"
                                                        value="Search">Delete
                                                </button></th></tr> -->
                                <tr>
                                   <!--  <th></th> -->
                                    <th>#</th>
                                    <th>Campaign</th>
                                    <th>List</th>
                                    <th>Disposition</th>
                                    <th>Day</th>
                                    <th>Time</th>
                                    <th>Call Time</th>
                                    <th>Recycle Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($recycle_list))
                                    
                                    @foreach($recycle_list as $key => $recyle)
                                    <tr>
                                    <!-- <td><input type="checkbox" /></td> -->

                                    <td>{{++$key}}</td>
                                    <td>{{$recyle->campaign}}</td>
                                    <td>{{$recyle->list}}</td>
                                    <td>{{$recyle->disposition}}</td>
                                    <td>{{$recyle->day}}</td>
                                    <td>{{$recyle->time}}</td>
                                    <td>{{$recyle->call_time}}</td>
                                    <td>{{$recyle->is_deleted}}</td>
                                     <td><a style="cursor:pointer;color:blue;"  href="edit-recycle/{{$recyle->id}}" class='editRecycle' ><i class="fa fa-edit fa-lg"></i></a> | <a style="cursor:pointer;color:red;"  class='deleteRecycle' data-id={{$recyle->id}} data-account_no=><i class="fa fa-trash fa-lg"></i></a> | <a style="cursor:pointer;color:blue;"  href=
                                        "recycle-rule/{{$recyle->list_id}}/{{$recyle->disposition_id}}" class='editRecycle' ><i class="fa fa-recycle" aria-hidden="true"></i></a> </td>

                                </tr>
                                   
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

                       
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

              
            </div><!-- /.col -->









         <div class="modal fade" id="delete" role="dialog">
    
      <!-- Modal content-->
     
       
               <div class="modal-dialog">
            <div class="modal-content" style="background-color: #d33724 !important;color:white;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                </div>
                <div class="modal-body">
                    <p>You are about to delete <b><i class="title"></i></b> Recycle.</p>
                    <p>Do you want to proceed?</p>
                      <input type="hidden" class="form-control" name="recycle_id" value="" id ="recycle_id" >


                          
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger btn-ok deleteRecycleRule">Delete</button>
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
        { "bSortable": false, "aTargets": [ 0,3 ] }
    ]
}); 
} );



        $(".deleteRecycle").click(function(){
        var delete_id = $(this).data('id');
        $("#delete").modal();
        $("#recycle_id").val(delete_id);
       
    });

           $(document).on("click", ".deleteRecycleRule" , function() {
    //if(confirm("Are you sure you want to delete this?")){
        var recycle_id = $('#recycle_id').val();
       //alert(group_id);
        var el = this;
        $.ajax({
            url: 'deleteRecycleRule/'+recycle_id,
            type: 'get',
            success: function(response){
                 window.location.reload(1);
            }
        });
     //   window.location.reload(1);
   
});


</script>
@endsection


