@extends('layouts.app') @section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 22px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider_button {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider_button:before {
  position: absolute;
  content: "";
  height: 15px;
  width: 15px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider_button {
  background-color: #00c0ef;
}

input:focus + .slider_button {
  box-shadow: 0 0 1px #00c0ef;
}

input:checked + .slider_button:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider_button.round {
  border-radius: 34px;
}

.slider_button.round:before {
  border-radius: 50%;
}


</style>

<style type="text/css">
.dialog-background{
    background: none repeat scroll 0 0 rgba(244, 244, 244, 0.5);
    height: 100%;
    left: 0;
    margin: 0;
    padding: 0;
    position: absolute;
    top: 0;
    width: 100%;
    z-index: 100;
}

.dialog-loading-wrapper {
    background: none repeat scroll 0 0 rgba(244, 244, 244, 0.5);
    border: 0 none;
    height: 100px;
    left: 50%;
    margin-left: -50px;
    margin-top: -50px;
    position: fixed;
    top: 50%;
    width: 100px;
    z-index: 9999999;
}
</style>

   
<div class="dialog-background" id="loading" style="display: none;">
    <div class="dialog-loading-wrapper">
        <img src="{{ asset('asset/img/lp.gif') }}">
    </div>
</div> 
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

        <section class="content-header">
                <h1>
                    <b>Campaign List</b>
                    
                </h1>
                <ol class="breadcrumb">
                     <li><a href="{{('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                     <li class="active">Campaign</li>
                    <li class="active">Campaign List</li>
                </ol>
        </section>

    <section class="content-header">

        <div class="text-right mt-5 mb-3"> 
            <a href="{{ url('/campaign') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Show Campaign</a>
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

                                    <th>Campaign Name</th>
                                    <th>List Name</th>
                                    <th>Dialled Leads/Total Leads</th>
                                   <th>Status</th>
                                    <th>Action</th>

                                   <!-- <th>Action</th> -->

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($camp_list as $key => $campaignlst)
                                <tr>

                                    <td>{{ $key+1 }}</td>
                                    <td>{{$campaignlst->title}}</td>
                                    <td>{{$campaignlst->l_title}}</td>
                                    <td>{{$campaignlst->rowLeadReport}} / {{$campaignlst->rowListData}}</td>
                                    <td>
                                        @if($campaignlst->status == '0')
                                        <span class="label label-danger">Inactive</span>
                                            
                                        @else ($campaignlst->status == '1')
                                        <span class="label label-success">Active</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($campaignlst->crm_title_url == 'hubspot')

                                        <a style="cursor:pointer;color:blue;" href="{{url('campaign/list')}}/contact/{{$campaignlst->list_id}}" 
                                    class=''   ><i class="fa fa-eye fa-lg"></i></a>
                                        @else
                                        <a style="cursor:pointer;color:red;"  
                                    class='openCampaignDelete' data-camid={{$campaignlst->campaign_id}} data-id={{$campaignlst->list_id}}  ><i class="fa fa-trash fa-lg"></i></a> | <a style="cursor:pointer;color:blue;"  class='openrecycle'  data-camid={{$campaignlst->campaign_id}} data-id={{$campaignlst->list_id}}><i class="fa fa-recycle fa-lg"></i></a> |  <label class="switch"><input data-campaignid="{{$campaignlst->campaign_id}}"  data-listid="{{$campaignlst->list_id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $campaignlst->status ? 'checked' : '' }}><span class="slider_button round"></span>
                                        @endif
</label> </td>
                                    


                                </tr>
                                @endforeach
                        </table>

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

            </div>
            <!-- /.col -->


            <!---- code for recycle  -->

<div id="recycleListModal" class="modal fade" role="dialog">
    <div class="modal-dialog" >

        <!-- Modal content-->
        <div class="modal-content" style="background-color: black !important;color:white;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Recycle List</h4>
            </div>
            <form method="post">
                @csrf
                <div class="modal-body">
                    <div class="col-md-12 p-b-10" style="font-weight: bold"><div class="col-md-1"></div><div class="col-md-6">Disposition</div><div class="col-md-2">Records</div><div class="col-md-3">Call times</div></div>
                    <div id="disposition" style="display: inline-block">

                    </div>
                </div>
                <div class="modal-footer">
                        <input type="hidden" name="param[id]" value="" id="recycle_list_id"/>
                        <input type="hidden" name="param[cid]" value="" id="recycle_campaign_id"/>

                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" name ="submit" value="recycle" class="btn btn-danger btn-ok deleteCampaignList">Recycle</button>

                                       </div>
            </form>
        </div>

    </div>
</div>

            <!-- end code -->

           
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
                      <input type="hidden" class="form-control" name="camid" value="" id ="camid" >



                          
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger btn-ok deleteCampaignList">Delete</button>
                </div>
            </div>
        </div>

            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>

     $(".openrecycle").click(function() {
        $("#recycleListModal").modal();
        var list_id = $(this).data("id");
        var campaign_id = $(this).data("camid");
        $('#recycle_list_id').val(list_id);
        $('#recycle_campaign_id').val(campaign_id);
         $.ajax({
            url: '/listDisposition/'+list_id,
            type: 'get',
            success: function(response){
                $('#disposition').html("");
                 for(var i = 0; i < response.length; i++) {
                var elem = document.getElementById('disposition');
                
                  var id = response[i].id;
                  var name = response[i].name;
                  var record_count = response[i].record_count;

                  

                  elem.innerHTML = elem.innerHTML + "<div class='col-md-12 p-b-10'><div class='col-md-1'><input type='checkbox' value='"+id+"' name='param[disposition][]'/></div><div class='col-md-6'>"+name+"</div><div class='col-md-2'>"+record_count+"</div><div class='col-md-3'><select class='form-control' name='param[select_id_"+id+"]'><option value='1'>1</option><option value='2'> less than or equal to 2</option><option value='3'> less than or equal to 3</option><option value='4'>less than or equal to 4</option><option value='5'>less than or equal to 5</option><option value='6'>less than or equal to 6</option><option value='7'>less than or equal to 7</option><option value='8'> less than or equal to 8</option><option value='9'> less than or equal to 9</option><option value='10'>less than or equal to 10</option><option value='11'>less than or equal to 11</option><option value='12'>less than or equal to 12</option><option value='13'>less than or equal to 13</option><option value='14'>less than or equal to 14</option><option value='15'>less than or equal to 15</option></select></div><hr></div>";

                  

                
              }
            }
        });
        /*.done(function( response ) {
            $('#disposition').html(response);
        });*/

    });


     $(".openCampaignDelete").click(function() {
        var delete_id = $(this).data('camid');
        var list_id = $(this).data('id');
        $("#delete").modal();
        $("#camid").val(delete_id);
        $("#list_id").val(list_id);

    });

          $(document).on("click", ".deleteCampaignList" , function() {
    //if(confirm("Are you sure you want to delete this?")){
        var list_id = $('#list_id').val();
        var camid = $('#camid').val();

       //alert(group_id);
        var el = this;
        $.ajax({
            url: '/deleteListData/'+list_id+'/'+camid,
            type: 'get',
            success: function(response){
                 window.location.href = "/campaign";
            }
        });
     //   window.location.reload(1);
   
});



    
</script>

<script>
    $(document).ready(function() {
        var oTable = $('#example').dataTable({
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [2, 3]
            }]
        });
    });
</script>

<script>
    $(function()
    {
        $("#example").on("change", ".toggle-class", function ()
        {
            $('#loading').show();
            var status = $(this).prop('checked') == true ? 1 : 0; 
            var campaignid = $(this).data('campaignid');             
            var listid = $(this).data('listid');             

            $.ajax({
                type: "GET",
                dataType: "json",
                url: '/updateCampaignList/'+campaignid+'/'+listid+'/'+status+'/4',
                success: function(data)
                {
                    if(data.status == 'true')
                    {
                        toastr.success(data.message);
                    }
                    else
                    {
                        toastr.error(data.message);

                    }
                    $('#loading').show();

                    console.log(data.success);
                    window.location.reload(1);
                }
            });
        })
    })
</script>
@endsection