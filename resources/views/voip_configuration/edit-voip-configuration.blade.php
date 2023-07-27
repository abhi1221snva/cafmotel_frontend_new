@extends('layouts.app')
@section('title', 'Edit VoIP Configurations')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
                <h1>
                   <b>Edit VoIP Configuration</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Configuration/li>
                    <li class="active">Edit VoIP Configurations</li>
                </ol>
        </section>

        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                 
            <a href="{{ url('/voip-configurations') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Show VoIP Configuration</a>

           </div>
        </section>

    <!-- Main content -->

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <form class="needs-validation" action="" method="post">
                            @csrf

                            @php
                                $data = explode('_',$voip_configuration[0]['name']);
                                @endphp

                             <input type="hidden" class="form-control" placeholder="Name"  type="" name="user_extension_id" value="{{$voip_configuration[0]['user_extension_id']}}" id="user_extension_id" maxlength="" >
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="validationTooltip01">Name <i data-toggle="tooltip" data-placement="right" title="Type name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <input type="text" class="form-control" placeholder="Name"  type="" name="name" value="{{str_replace('-',' ',$data[0])}}" id="name" maxlength="" >
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="validationTooltip01">Host <i data-toggle="tooltip" data-placement="right" title="Type host" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <input type="text" class="form-control" placeholder="Host" onkeypress="return isNumberKey(event)" type="" name="host" value="{{$voip_configuration[0]['host']}}" id="host" maxlength="" >
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="validationTooltip01">Username <i data-toggle="tooltip" data-placement="right" title="Type username" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <input type="text" class="form-control" placeholder="Username" type="" name="username" value="{{$voip_configuration[0]['username']}}" id="username" maxlength="" >
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="validationTooltip01">Password <i data-toggle="tooltip" data-placement="right" title="Type password number" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <input type="text" class="form-control" placeholder="Password"  type="" name="secret" value="{{$voip_configuration[0]['secret']}}" id="secret" maxlength="" >
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="validationTooltip01">Dial Prefix(If Any)<i data-toggle="tooltip" data-placement="right" title="Type prefix number" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <input type="text" class="form-control" placeholder="Prefix" onkeypress="return dialPrefix(event)" type="" name="prefix" value="{{$voip_configuration[0]['prefix']}}" id="prefix" maxlength="12 >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                            </div>
                        </div>
                                    <div style="clear: both"></div>
                                <div class="modal-footer">
                                    <!--  <input type="hidden" name="extension" value="" id="extension-edit-id"/> -->
                                    <button type="submit" name="submit" value="Search" class="btn btn btn-primary waves-effect waves-light"><i class="fa fa-check-square-o fa-lg"></i> Update</button>

                                    <a type="button" class="btn btn-warning" onclick="window.location.reload();">
                                        <i class="fa fa-refresh fa-lg"></i> 
                                        Reset
                                        </a>
                                        <a type="button" class="btn btn-danger"  href="{{url('/voip-configurations')}}"><i class="fa fa-reply fa-lg"></i> 
                                        Cancel
                                        </a>



                                </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</section><!-- /.content -->
</div>

<script>
    
///////////////api //////////////


   


    
  
   
    



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



<script language=Javascript>
    function dialPrefix(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if(charCode == 35)
        {
            return true;
        }
        else
        if (charCode != 46 && charCode > 31  && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
       
</script>
@endsection
