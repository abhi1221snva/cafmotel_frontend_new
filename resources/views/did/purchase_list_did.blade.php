@extends('layouts.app')

@section('title', 'Did List')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

      <section class="content-header">
                <h1>
                   <b> Did List</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Inbound Configuration</li>
                    <li class="active">Did List</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                   <a id="openListForm"  type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Order A DID</a>
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
                                <th>DID</th>
                                <th>CNAM</th>
                                <th>Route To</th>
                                <!-- <th>Detail</th>             -->
                                <th>Action</th>
                               
                            </tr>
                            </thead>
                            <tbody>


                               
                                @if(!empty($did_list))
                                
                                @foreach($did_list as $key => $lists)
                                
                                <tr>
                                <td>{{++$key }}</td>
                                <td>{{$lists->cli}}</td>
                                <td>{{$lists->cnam}}    </td>
                                <td>
                                <!--@if($lists->dest_type>0)
                                    {{ !empty($destTypeList[$lists->dest_type]) ? $destTypeList[$lists->dest_type] : ''  }}
                                @else
                                    IVR {{$lists->dest_type}}
                                @endif-->

                                @if($lists->dest_type>0)
                                @if($lists->dest_type == 1)
                                @foreach($extension_list as $extension)
                                @if($lists->extension == $extension->id)
                                {{ $extension->extension}} ({{ !empty($destTypeList[$lists->dest_type]) ? $destTypeList[$lists->dest_type] : ''  }})
                                @endif
                                @endforeach
                                @else
                                {{$destTypeList[$lists->dest_type]}}
                                @endif

                                @else
                                    @foreach($ivr_list as $ivr)
                                    @if($lists->ivr_id == $ivr->ivr_id)
                                    {{$ivr->ivr_desc}} (IVR {{$lists->dest_type}})
                                    @endif
                                    
                                    @endforeach
                                @endif


                                
                                </td>
                                <!-- <td>
                                @switch($lists->dest_type)
                                    @case(0)
                                        {{$lists->ivr_id}}
                                    @break
                                    @case(1)
                                        {{$lists->extension}}
                                    @break

                                @endswitch
                                </td> -->
                                <td><a style="cursor:pointer;color:blue;" href="edit-did/{{$lists->id}}" class='editEG'  ><i class="fa fa-edit fa-lg"></i></a> | <a style="cursor:pointer;color:red;"  
                                    class='deleteList' data-cli={{$lists->cli}} data-id={{$lists->id}}  ><i class="fa fa-trash fa-lg"></i></a> </td>



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
                    <form enctype="multipart/form-data"  class="form-horizontal" method="post" action="{{url('did/saveDid')}}">
                                @csrf
                    <div class="box-body">

                        <input type="hidden"  class="form-control" name="operator" value="didforsale"/>
                        <input type="hidden" class="form-control" required  name="cnam" id="cnam" value="didforsale">
                        <input type="hidden" class="form-control" required  name="dest_type" id="cnam" value="0">

                        <div class="item form-group ">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Country <i data-toggle="tooltip" data-placement="right" title="Select country name" class="fa fa-info-circle" aria-hidden="true"></i><span class="required"></span>
                            </label>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control" name="country" id="country">
                                <option value="">Select Country</option>

                                @foreach($country_list as $key=>$c_list)
                                <option  value="{{$c_list->id}}">{{$c_list->name}}</option>
                                @endforeach
                            </select>
                        </div>
                       </div>


                       <div class="item form-group " id="state" style="display: none;">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">State <i data-toggle="tooltip" data-placement="right" title="Select state name" class="fa fa-info-circle" aria-hidden="true"></i><span class="required"></span>
                        </label>



                        <div class="col-md-6 col-sm-6 col-xs-12">

                          <select class="form-control" name="state" id="state_id">
                            
                        </select>
                       
                        </div>

                        
                      </div>



                        <div class="item form-group ">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Area Code <i data-toggle="tooltip" data-placement="right" title="Select area code" class="fa fa-info-circle" aria-hidden="true"></i><span class="required"></span>
                            </label>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control" name="area_code" id="npa_id">
                                
                                <option selected="" value="216">216</option>
                            </select>
                        </div>
                       </div>


                       <div class="item form-group ">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">NXX <i data-toggle="tooltip" data-placement="right" title="Select nxx code" class="fa fa-info-circle" aria-hidden="true"></i><span class="required"></span>
                            </label>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control" name="nxx" id="nxx_id">
                                <option value="">Select NXX</option>
                                <option value="373">373</option>
                            </select>
                        </div>
                       </div>


                        <div class="item form-group" id="did" style="display: none;">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">DID <i data-toggle="tooltip" data-placement="right" title="Select did" class="fa fa-info-circle" aria-hidden="true"></i><span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="msg">


                          <div id='loadingmessage' style='display:none'>
                            <img src={{ asset('asset/img/giphy.gif') }} />
                        </div>

                          <select required="" class="form-control" name="cli" id="did_id" style="display: none;">
                           

                            
                          </select>
                       
                        </div>

                        
                      </div>


                        <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                          <!-- <button type="submit" class="btn btn-primary">Cancel</button> -->
                          
                           <button id="send" type="submit" class="btn btn-info">Submit</button>
                          
                        </div>
                      </div>


                      


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

<style>
.hide_show{
    display:none;
}
.operator_txt{
    display:none;
}
.sms_grid{
    display:none;
}
</style>
<!-- /.content-wrapper -->
<script>


     $(function() {
            $('select[name=country]').change(function() {
                $("#state").show();

                var url = '{{ url('country') }}/' + $(this).val();

                $.get(url, function(data) {
                    var select = $('form select[name= state]');

                    select.empty();

                    select.append('<option value="">Select State</option>');

                    $.each(data, function(key, value) {
                        select.append('<option value=' + value.id + '>' + value.name + '</option>');
                    });
                });
            });

        });


            $('select[name=nxx]').change(function() {
                $("#did").show();
                $("#loadingmessage").show();
               /* setTimeout(function(){
                    $("#loadingmessage").hide();
                    $("#did_id").show();
                },2000); */

                 cid= $("#country").val();
                 
                sid=$("#state_id").val();
               
                npa=$("#npa_id").val();
               
                nxx=$("#nxx_id").val();
               
                                 var url = '{{ url('listdid') }}/' + cid + '/'+sid+'/'+npa+'/'+nxx;

                $.get(url, function(data) {

                     $("#loadingmessage").hide();
                    $("#did_id").show();
                  //alert(data['numbers']);
                  var did = $('form select[name= cli]');
                  did.empty();


                    did.append('<option value="">Select DID</option>');

                    if(data['status'] == 'success'){

                    $.each(data['numbers'], function(key, value) {
                     //SS alert(value.npa);
                        did.append('<option value='+value.number+'>' + value.number + '</option>');
                    });
                  }else {

                    $("#did_id").hide();
                    //$("#msg").html(data['message']);
                    $("#msg").html('<h3 style="color:brown;">The above information is not match with our database plz submit</h3>');


                  }
                     
                   
                  


                    
                  
                });
            });

    
    $(document).ready(function() {
    var oTable = $('#example').dataTable( {
    "aoColumnDefs": [
        { "bSortable": false, "aTargets": [ 2,3 ] }
    ]
}); 
} );
    $("#openListForm").click(function(){
    $("#listModal").modal();
    $("#name").val('');
    $("#status").val('1');
    $("#id").val('');
    $("#add-edit").html('Order A Did');
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

       //alert(group_id);
        var el = this;
        $.ajax({
            url: 'deleteDidData/'+list_id,
            type: 'get',
            success: function(response){
                 window.location.reload(1);
            }
        });
     //   window.location.reload(1);
   
});

function showData(id){
    $('.hide_show').hide();
    $('#user_'+id).show();
}

$(document).ready(function(){
        $('input[id="operator_check"]').click(function(){
            if($(this).prop("checked") == true){
                $('.operator_txt').show();
            }
            else if($(this).prop("checked") == false){
                $('.operator_txt').hide();
            }
        });
// is_sms
        $('.radio_sms').on('change',function(){
            let radio_val = $(this).val();
            if(radio_val==1){
                $('.sms_grid').show();
            }else{
                $('.sms_grid').hide();
            }
            
        });

});

$(document).ready(function(){
  $('.radio_sms , #vehicle1 , #vehicle2 , #operator_check ').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    });
    
    $('#unchk_sms').on('ifChecked', function(event){
        $('.sms_grid').show();
    });

    $('#chk_sms').on('ifChecked', function(event){
        $('.sms_grid').hide();
    });

    $('#operator_check').on('ifChecked', function(event){
        $('.operator_txt').show();
    });

    $('#operator_check').on('ifUnchecked', function (event) {
        $('.operator_txt').hide();
        });
    $('.select2').select2()

});
</script>

<script src="{{asset('asset/plugins/iCheck/icheck.min.js') }}"></script>
<link rel="stylesheet" href="{{asset('asset/plugins/iCheck/all.css') }}">
@endsection