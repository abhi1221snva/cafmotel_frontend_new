@extends('layouts.app')

@section('title', 'Did List')

@section('content')
<style>
    #modal-container {
  position: relative;
}

#loader {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 9999;
}

    </style>
 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
     <section class="content-header">
                <h1>
                   <b>Phone Number(s)</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Inbound Configuration</li>
                    <li class="active">Phone Number(s)</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
                    @if(Session::get('level') > 9)
                   <a id="openExcelForm" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-excel-o"></i>Upload Excel </a> &nbsp;
                   @endif
  
                   <!-- <a id="openExcelForm" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-excel-o"></i>Upload Excel </a> &nbsp; -->

                <!--<a id="openListForms" href="{{url('/add-did')}}" style="float:right;" type="submit" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Phone number </a>-->

                 @if(Session::get('level') > 9)


            <a href="{{url('/show-buy-did')}}"  type="button" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i>&nbsp; Buy Number From DIDForSale </a>

            <a href="{{url('/show-buy-did-plivo')}}"  type="button" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i>&nbsp; Buy Number From Plivo </a>

            @endif
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
                                <th style="width:180px;">Phone Number(s)</th>
                                <th>CNAME</th>
                                <th>Destination Type</th>
                                <th>Destination</th>
                                <th>SMS</th> 
                                <th>Assigned User</th>     
                                <th style="width:90px;">Exclusive User</th>                    
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>

                                @if(count($did_list) > 0)

                                @foreach(array_reverse($did_list) as $key => $lists)

                                <tr>
                                <td>{{++$key }}</td>
                                <td>{{$lists->cli}} &nbsp;
                                    @if($lists->default_did == 1)
                                        <span class="badge bg-info">Default Number</span>
                                    @elseif($lists->cnam == null || $lists->dest_type == null)
                                        <span class="badge bg-info">Not Configured</span>
                                    @endif

                                    @if($lists->cnam != null)
                                        @switch($lists->fax)
                                            @case(1)
                                                <span style="float:right;" class="badge bg-purple">Fax</span>
                                            @break
                                            @case('')
                                                <span style="float:right;" class="badge bg-green">Voice</span>
                                            @break
                                        @endswitch
                                    @endif

                                    @if((Session::get('level') > 9))
                                    @if($lists->voip_provider == 'didforsale')
                                        <span class="badge bg-info">DIDFORSALE</span>
                                    @elseif($lists->voip_provider == 'plivo')
                                        <span class="badge bg-info">PLIVO</span>
                                    @elseif($lists->voip_provider == 'voxox')
                                        <span class="badge bg-info">VOXOX</span>
                                    @endif
                                    @endif

                                </td>
                                <td>@if($lists->cnam != null)
                                        {{$lists->cnam}}
                                    @else
                                        --
                                    @endif    </td>
                                <td>
                                @if($lists->dest_type>0)
                                    {{ !empty($destTypeList[$lists->dest_type]) ? $destTypeList[$lists->dest_type] : ''  }}
                                @else
                                    @if($lists->cnam != null)
                                        IVR
                                    @else
                                        --
                                    @endif
                                @endif

                                </td>
                                <td>
                                @if($lists->dest_type > 0)
                                    @if($lists->dest_type == 1)
                                        @foreach($extension_list as $extension)
                                            @if($lists->extension == $extension->id)
                                                {{$extension->first_name}} {{$extension->last_name}} - {{$extension->extension}} 
                                            @endif
                                        @endforeach
                                    @elseif($lists->dest_type == 2)
                                        @foreach($extension_list as $extension)
                                            @if($lists->voicemail_id == $extension->id)
                                                {{$extension->first_name}} {{$extension->last_name}} - {{$extension->extension}} 
                                            @endif
                                        @endforeach
                                    @elseif($lists->dest_type == 8)
                                        @foreach($ring_group_list as $ring)
                                            @if($lists->ingroup == $ring->id)
                                                {{$ring->description}} - {{$ring->title}}
                                            @endif
                                        @endforeach
                                    @elseif($lists->dest_type == 5)
                                        @foreach($conferencing as $conf)
                                            @if($lists->conf_id == $conf->id)
                                                {{$conf->title}} - {{$conf->conference_id}}
                                            @endif
                                        @endforeach
                                    @elseif($lists->dest_type == 4)
                                        {{$lists->forward_number}}

                                        @elseif($lists->dest_type == 10)
                                        Run CNAM

                                         @elseif($lists->dest_type == 11)
                                        Voice AI

                                    @else
                                        {{$destTypeList[$lists->dest_type]}}
                                    @endif
                                @else
                                    @foreach($ivr_list as $ivr)
                                        @if($lists->ivr_id == $ivr->ivr_id)
                                            {{$ivr->ivr_desc}} - {{$ivr->ivr_id}}
                                        @endif
                                    @endforeach
                                @endif
                                </td>
                                
                                <td>
                                    @if($lists->sms == 0)
                                    <span class="badge bg-red">NO</span>
                                    @elseif($lists->sms == 1)
                                    <span class="badge bg-green">YES</span>
                                    @endif
                                    
                                </td>
                                <td> @if($lists->sms==1)
                                @foreach($extension_list as $extension)
                                            @if($lists->sms_email == $extension->id)
                                                {{$extension->first_name}} {{$extension->last_name}} - {{$extension->extension}} 
                                            @endif
                                        @endforeach
                                         @elseif($lists->sms==0)
                                         @endif
                                  </td>
                                <td>
                                    @if($lists->set_exclusive_for_user == 0)
                                    <span class="badge bg-red">NO</span>
                                    @elseif($lists->set_exclusive_for_user == 1)
                                    <span class="badge bg-green">YES</span>
                                    @endif
                                </td>
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
                         <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="inputEmail3" class="col-form-label">Cli</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" required  name="cli" id="cli" placeholder="Enter CLI">
                        </div>


                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="inputEmail3" class="col-form-label">CNAM</label>
                        </div>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" required  name="cnam" id="cnam" placeholder="Enter cnam">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="inputEmail3" class="col-form-label">Area code</label>
                            </div>

                    <div class="col-sm-9">
                            <input type="text" class="form-control"  name="area_code" id="area_code" placeholder="Enter area code">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="inputEmail3" class="col-form-label">Dest type</label>
                            </div>

                    <div class="col-sm-9">
                            <select class="form-control"  name="dest_type" id="dest_type" onchange="return showData(this.value)">
                            @if (!empty($dest_type))
                                @foreach($dest_type as $item)
                                    <option value={{$item->dest_id}}>{{$item->dest_type}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group row hide_show" id="user_0" style="display:block;">
                        <div class="col-sm-3">
                            <label for="inputEmail3" class="col-form-label">IVR</label>
                            </div>

                    <div class="col-sm-9">
                    <select class="form-control"  name="ivr_id" id="ivr_id">
                    @if (isset($ivr_list))
                        @foreach($ivr_list as $ivr_lst)
                            <option value={{$ivr_lst->ivr_id}}>{{$ivr_lst->ivr_id}}</option>
                        @endforeach
                    @endif
                    </select>
                        </div>
                    </div>

                    <!-- <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="inputEmail3" class="col-form-label">ivr id</label>
                            <input type="text" class="form-control" required  name="ivr_id" id="ivr_id" placeholder="Enter ivr_id">
                        </div>
                    </div> -->

                    <div class="form-group row hide_show" id="user_1">
                        <div class="col-sm-3">
                            <label for="inputEmail3" class="col-form-label">Extension</label>
                            </div>

                    <div class="col-sm-9">
                    <select class="form-control"  name="extension" id="extension">
                    @if (is_array($extension_list))
                        @foreach($extension_list as $ext_lst)
                            <option value={{$ext_lst->id}}>{{$ext_lst->extension}}</option>
                        @endforeach
                    @endif
                    </select>
                        </div>
                    </div>

                    <div class="form-group row hide_show" id="user_2">
                        <div class="col-sm-3">
                            <label for="inputEmail3" class="col-form-label">voicemail id</label>
                            </div>

                    <div class="col-sm-9">
                    <select class="form-control"  name="voicemail_id" id="voicemail_id"></select>
                        </div>
                    </div>

                    <div class="form-group row hide_show" id="user_4">
                        <div class="col-sm-3">
                            <label for="inputEmail3" class="col-form-label">DID</label>
                            </div>

                    <div class="col-sm-9">
                            <input type="text" class="form-control"  name="forward_number" id="forward_number" placeholder="Enter number where you want to forward did">
                        </div>
                    </div>

                    <div class="form-group row hide_show" id="user_5">
                        <div class="col-sm-3">
                            <label for="inputEmail3" class="col-form-label">Conferencing</label>
                            </div>

                    <div class="col-sm-9">
                    <select class="form-control"  name="conf_id" id="conf_id"></select>
                    </div>
                    </div>

                    <div class="form-group row hide_show" id="user_9">
                        <div class="col-sm-3">
                            <label for="inputEmail3" class="col-form-label">Queue</label>
                            </div>

                    <div class="col-sm-9">
                    <select class="form-control"  name="queue_id" id="queue_id"></select>
                        </div>
                    </div>

                    <div class="form-group row hide_show" id="user_8">
                        <div class="col-sm-3">
                            <label for="inputEmail3" class="col-form-label">Ring Group</label>
                            </div>

                    <div class="col-sm-9">
                    <select class="form-control"  name="ingroup" id="ingroup">
                    @if (isset($ring_group_list))
                        @foreach($ring_group_list as $rgroup_lst)
                            <option value={{$rgroup_lst->id}}>{{$rgroup_lst->title}}</option>
                        @endforeach
                    @endif
                    </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="inputEmail3" class="col-form-label">Operator</label>
                            </div>

                            <div class="col-sm-1">
                            <input type="checkbox" id="operator_check" name="operator_check" value="1">
                            </div>
                    <div class="col-sm-8">
                    <select class="form-control operator_txt"  name="operator" id="operator">
                    @if (is_array($extension_list))
                        @foreach($extension_list as $ext_lst1)
                            <option value={{$ext_lst1->id}}>{{$ext_lst1->extension}}</option>
                        @endforeach
                    @endif
                    </select>

                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="inputEmail3" class="col-form-label">Default</label>
                            </div>

                    <div class="col-sm-9">
                            No <input type="radio" id="vehicle1" name="default_did" checked value="0">
                            Yes <input type="radio" id="vehicle2" name="default_did" value="1">
                            <!-- <input type="text" class="form-control" required  name="default_did" id="default_did" placeholder="Enter default_did"> -->
                        </div>
                    </div>
                    <div class="form-group row">
                    <div class="col-sm-3">
                            <label for="inputEmail3" class="col-form-label">Option 1</label>
                            </div>
                        <div class="col-sm-9">
                            <label for="inputEmail3" class="col-form-label">voice</label>
                            <input type="radio" id="veh1" class="grid_option_1" name="option_1" checked value="v">
                            <label for="inputEmail3" class="col-form-label">fax</label>
                            <input type="radio" id="veh1" class="grid_option_1" name="option_1" value="f">

                        </div>
                    </div>

                    <div class="form-group row fax_email_grid">
                    <div class="col-sm-3">
                            <label for="inputEmail3" class="col-form-label">Fax email</label>
                            </div>
                        <div class="col-sm-9">
                            <label for="inputEmail3" class="col-form-label"></label>
                            <select class="form-control select2" multiple  name="fax_did[]" id="fax_did" style="width:100%">
                                    @if (is_array($extension_list))
                                        @foreach($extension_list as $ext_lst_1)
                                            <option value={{$ext_lst_1->id}} >{{$ext_lst_1->email}}</option>
                                        @endforeach
                                    @endif
                               </select>
                        </div>
                    </div>
                    

                    <div class="form-group row">
                    <div class="col-sm-3">
                            <label for="inputEmail3" class="col-form-label">SMS</label>
                            </div>
                        <div class="col-sm-9">
                        No <input type="radio" class="radio_sms" id="chk_sms" name="is_sms" checked value="0">
                            Yes <input type="radio" class="radio_sms" id="unchk_sms" name="is_sms" value="1">
                        </div>
                    </div>

                     <div class="form-group row sms_grid">

                        <div class="col-sm-6">
                            <label for="inputEmail3" class="col-form-label"></label>
                            <input type="text" class="form-control"  name="sms_phone" id="sms_phone" placeholder="Enter Phone number">
                        </div>

                        <div class="col-sm-6">
                            <label for="inputEmail3" class="col-form-label"></label>
                            <select class="form-control select2"  name="sms_email" id="sms_email" style="width:100%">
                                    @if (is_array($extension_list))
                                        @foreach($extension_list as $ext_lst_1)
                                            <option value={{$ext_lst->id}} >{{$ext_lst_1->email}}</option>
                                        @endforeach
                                    @endif
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


  <div class="modal fade" id="myModalExcel" role="dialog">


    <div class="modal-dialog">
        <div class="modal-content" style="">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="upload-excel"></h4>
            </div>

            <form method="post" action="{{url('did/uploadDid')}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                <div id="loader" style="display: none;">
                    <img src="{{ asset('asset/giphy.gif') }}" alt="Loading..."style="height:100px;width:100px;">
                </div>
                    <input type="hidden" class="form-control" name="did" value="" >

                    <label for="inputEmail3" class="col-form-label closed">Excel <i data-toggle="tooltip" data-placement="right" title="Browser for Excel file" class="fa fa-info-circle" aria-hidden="true"></i></label>
                    <input type="file" accept=".xls,.xlsx" class="form-control closed" required name="did_file" id="did_file" placeholder="Select Excel File">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="submit" class="btn btn-info btn-ok">Save</button>
                </div>
            </form>
    
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
.fax_email_grid{
    display:none;
}
</style>
<!-- /.content-wrapper -->

<script>


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
    $("#add-edit").html('Add Did');
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

        $('.grid_option_1').on('click',function(){
            let radio_val = $(this).attr('value');
            if(radio_val=='f'){
                $('.fax_email_grid').show();
            }else{
                $('.fax_email_grid').hide();
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

    $('.grid_option_1').on('ifChecked', function(event){
        $('.fax_email_grid').show();
    });

    $('.grid_option_1').on('ifUnchecked', function(event){
        $('.fax_email_grid').hide();
    });


    $('.grid_option_11').on('ifChecked', function(event){
        $('.fax_email_grid').hide();
    });

    $('.grid_option_11').on('ifUnchecked', function(event){
        $('.fax_email_grid').show();
    });



    $('#operator_check').on('ifUnchecked', function (event) {
        $('.operator_txt').hide();
        });
    $('#operator_check').on('ifChecked', function(event){
        $('.operator_txt').show();
    });
    
    $('.select2').select2()

});
$("#openExcelForm").click(function() {
console.log('did_file');
$("#myModalExcel").modal();
$("#name").val('');
$("#status").val('1');
$("#id").val('');
$(".closed").show();
$("#upload-excel").html('Upload Excel');


});
  



</script>
<script>
  const form = document.querySelector('#myModalExcel form');
  const loader = document.querySelector('#loader');

  form.addEventListener('submit', () => {
    loader.style.display = 'block';
  });

  form.addEventListener('submit', () => {
    loader.style.display = 'none';
  });
</script>

<script>
  $(function() {
    $('form').submit(function() {
      $('#loader').show();
    });
  });
</script>
<script>
  $(function() {
    $('form').submit(function() {
      $('#loader').show();
    }).ajaxComplete(function() {
      $('#loader').hide();
    });
  });
</script>

<script src="{{asset('asset/plugins/iCheck/icheck.min.js') }}"></script>
<link rel="stylesheet" href="{{asset('asset/plugins/iCheck/all.css') }}">
@endsection
