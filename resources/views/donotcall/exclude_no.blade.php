@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

       <section class="content-header">
                <h1>
                   <b>Exclude Number</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> Do Not Call</li>
                    
                    <li class="active">Exclude Number</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
               <!--   <a href="{{ url('/add-ext') }}" style="float:right;" type="submit" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Extension Group</a> -->
               <a href="{{ url('/exclude-from-list') }}" class="btn btn-sm btn-primary">Back</a>

            <a id="openExcelForm" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-excel-o"></i>Upload Excel </a> &nbsp;

            <a id="openExcludeForm" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Exclude No</a>
           </div>
        </section>
    

    <!-- Main content -->
    <section class="content">
        <div class="row">
        <?php
        $url_page = explode('?',str_replace('/','',$_SERVER['REQUEST_URI']));
        $url = $url_page[0];
        
            if($page == 1)
            {
                $currentPage = 1;
            }

            else
            {
                $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            }

            $perPage = $show;
            $paginator = new Illuminate\Pagination\LengthAwarePaginator($exclude_list, $record_count ,$perPage,$currentPage,['path' => url($url)]);
            $record_count = $paginator->total();
        ?>
            <div class="col-xs-12">
                <div class="box">
                    
                    <div class="box-body">
                    <b>Total Rows :<?= $record_count ?></b>
                    
                    <!-- <form action="{{ url('/exclude-from-list') }}" method="get">
                        <input type="text" name="search" placeholder="first_name or last_name or company_name or mobile">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form> -->
                        <form action="{{ url('/exclude-from-list') }}" method="get">
                            
                    </form>
                    </div>
                    <form method="GET" action="">  
                    <div class="text-right"style="margin-bottom:10px;">
                        <input type="text" name="search"  placeholder=""value="{{$searchTerm}}">
                        <button type="submit"> <i class="fa fa-search"></i></button>
                    </div>
                    <label for="show">Show:</label>
                        <select name="show" onchange="this.form.submit()">
                            <option value="10" {{ request('show') == 10 ? 'selected' : '' }}selected>10</option>
                            <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('show') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <label for="entries">entries</label>
                
                    </form>
                        <table id="example" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <!-- <th>Account No</th> -->
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Company Name</th>
                                <th>Mobile</th>                               
                                <th>Action</th>                                
                            </tr>
                            </thead>
                            @if(!empty($exclude_list))
                            <tbody>
                        @foreach($exclude_list as $key => $ex_number)
                            <tr>                                
                                <td>{{ ($paginator->currentPage() - 1) * $paginator->perPage() + $key + 1 }}</td>
                                <td>{{$ex_number->first_name}}</td>
                                <td>{{$ex_number->last_name}}</td>
                                <td>{{$ex_number->company_name}}</td>
                                <td>{{$ex_number->number}}</td>                                                                                                
                                <td>                                     
                                    <a style="cursor:pointer;color:blue;"  class='editExcludeNo' data-number={{$ex_number->number}} data-campaignid = {{$ex_number->campaign_id}}><i class="fa fa-edit fa-lg"></i></a> |
                                    <a style="cursor:pointer;color:red;"  class='openExcludeDelete' data-number={{$ex_number->number}} data-campaignid={{$ex_number->campaign_id}} ><i class="fa fa-trash fa-lg"></i></a>                                   
                                </td>                                                                
                            </tr>
                        @endforeach   
                    </tbody>
                    {{$paginator->appends(Request::all())->links()}}

                    @endif
                        </table>

                        <div class="text-right">
                        {{$paginator->appends(Request::all())->links()}}
                       </div>
                    </div><!-- /.box-body -->
                    @if ($paginator->total() > 0)
                        <div class="text-left mt-10"style=margin-left:10px;>
                            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} entries.
                        </div>
                    @endif
                </div><!-- /.box -->              
            </div><!-- /.col -->

 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="add-edit"></h4>
        </div>
                <div class="modal-body">
                    <form method="post" action="">
                            @csrf
                    
                <div class="modal-body" style="display: inline-block">
                    <div class="row col-md-12">
                        <div class="form-group col-md-6">

                            <input type="hidden" class="form-control" name="exclude" value="" id ="id" required>

                               
                            <label>First Name <i data-toggle="tooltip" data-placement="right" title="Type First name of the lead" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <input type="text" class="form-control" name="first_name" value="" id="first_name" required="">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Last Name <i data-toggle="tooltip" data-placement="right" title="Type Last name of the lead" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <input type="text" class="form-control" name="last_name" value="" id="last_name" required="">
                            </div>
                        </div>
                    </div>
                    <div class="row col-md-12">
                        <div class="form-group col-md-6">
                            <label>Company Legal Name <i data-toggle="tooltip" data-placement="right" title="Type company name of the lead" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <input type="text" class="form-control" name="company_name" value="" id="company_name" required="">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Number <i data-toggle="tooltip" data-placement="right" title="Type phone number of the lead" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="number" value="" id="number" required="">
                            </div>
                        </div>
                    </div>
                    <div class="row col-md-12 closed">
                        <div class="form-group col-md-6">
                            <label>Campaign <i data-toggle="tooltip" data-placement="right" title="Select Campaign name form drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <div class="input-daterange input-group col-md-12">
                                <select name="campaign_id" class="form-control" id="campaign_id" required="">
                                    @foreach($campaign_list as $cam)
                                        @if(!empty($cam->title))
                                            <option value="{{$cam->id}}">{{$cam->title}}</option>
                                        @endif
                                    @endforeach
                                                                           
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit" value="Save" class="btn btn btn-dark waves-effect waves-light">Save</button>
                    <button type="button" class="btn btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button>
                </div>
            
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
                    <p>You are about to delete <b><i class="title"></i></b> exclude number record.</p>
                    <p>Do you want to proceed?</p>
                      <input type="hidden" class="form-control" name="exclude_id" value="" id ="number_camp" >
                      <input type="hidden" class="form-control" name="exclude_id" value="" id ="campaignid" >                          
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger btn-ok deleteExcludeNo">Delete</button>
                </div>
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

                    <form method="post" action="" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" class="form-control" name="dnc" value="" id="id">
                            <label for="inputEmail3" class="col-form-label closed">Excel <i data-toggle="tooltip" data-placement="right" title="Browser for Excel file" class="fa fa-info-circle" aria-hidden="true"></i> </label>
                            <input type="file"  class="form-control closed" required name="exclude_file" id="exclude_file" placeholder="Select Excel File">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="submit" class="btn btn-info btn-ok">Save</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <?php  ?>

        </div><!-- /.row -->
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script>



     $(".openExcludeDelete").click(function(){
        var number_camp = $(this).data('number');
        var campaignid = $(this).data('campaignid');

        $("#delete").modal();
        $("#number_camp").val(number_camp);
        $("#campaignid").val(campaignid);

       
    });


$("#openExcludeForm").click(function(){
        $("#myModal").modal();
      $(".closed").show();

        $("#name").val('');
        $("#status").val('1');
        $("#id").val('');
        $("#add-edit").html('Add Exclude Number');
    });


   $("#openExcelForm").click(function() {

        $("#myModalExcel").modal();
        $("#name").val('');
        $("#status").val('1');
        $("#id").val('');
        $(".closed").show();
        $("#upload-excel").html('Upload Excel');
    });

$(document).on("click", ".deleteExcludeNo" , function() {
   // if(confirm("Are you sure you want to delete this?")){


        var number = $("#number_camp").val();
        var campaign_id = $("#campaignid").val();

        //alert(delete_id);
        
        var el = this;
        $.ajax({
            url: 'deleteExcludeNo/'+number+'/'+campaign_id,
            type: 'get',
            success: function(response){
                //$(el).closest( "tr" ).remove();
                 window.location.reload(1);
            }
        });
       // window.location.reload(1);
    // }
    // else
    // {
    //     return false;
    // }
});

$(document).on("click", ".editExcludeNo" , function() {
    $("#myModal").modal();
    $("#add-edit").html('Edit Exclude Number');
      var number = $(this).data('number');

      $(".closed").hide();
        var campaign_id = $(this).data('campaignid');
    $.ajax({
        url: 'editExcludeNumber/'+number+'/'+campaign_id,
        type: 'get',
        success: function(response){
             //$("#campaign_id").val(response[0].campaign_id).change();
             $("#number").val(response[0].number);
             $("#first_name").val(response[0].first_name);
             $("#last_name").val(response[0].last_name);
             $("#company_name").val(response[0].company_name);



             $("#id").val(response[0].campaign_id);
        }
    });
});
</script>
@endsection