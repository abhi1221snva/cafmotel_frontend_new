@extends('layouts.app') 
@section('title', 'Do Not Call List')

@section('content')



<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->

     <section class="content-header">
                <h1>
                   <b>Do Not Call List</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> Do Not Call</li>

                    <li class="active">Do Not Call List</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
                   <a href="{{ url('/dnc') }}" class="btn btn-sm btn-primary">Back</a>

               <a id="openExcelForm" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-excel-o"></i>Upload Excel </a> &nbsp;
            <a id="openDNCForm" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add DNC</a>
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
            $paginator = new Illuminate\Pagination\LengthAwarePaginator($dnc_list, $record_count ,$perPage,$currentPage,['path' => url($url)]);
            $record_count = $paginator->total();
        ?>
            <div class="col-xs-12">
                <div class="box">
               
                    <div class="box-body">
                    <b>Total Rows :<?= $record_count ?></b>       
                    <form method="GET" action="">
                    <div class="text-right"style="margin-bottom:10px;">
                        <input type="text" name="search"id="search" placeholder="Number or Extension"value="{{ $searchTerm }}">
                        <button type="submit"><i class="fa fa-search"></i></button>
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
                                    <th>Number</th>
                                    <th>Extension</th>

                                    <th>Comment</th>
                                    @if(session()->get('level') >= 7)
                                    <th>Action</th>
                                    @endif
                                </tr>
                            </thead>

                            @if(!empty($dnc_list))
                            <tbody>
                           
                                @foreach($dnc_list as $key => $dnc)
                                <tr>
                                <td>{{ ($paginator->currentPage() - 1) * $paginator->perPage() + $key + 1 }}</td>
                                    <td>{{$dnc->number}}</td>
                                    <td>{{$dnc->extension}}</td>
                                    <td>{{$dnc->comment}}</td>
                                    <td>

                                        @if(session()->get('level') >= 7)

                                        <a style="cursor:pointer;color:blue;" class='editDnc' data-number={{$dnc->number}}  ><i class="fa fa-edit fa-lg"></i></a> |
                                        <a style="cursor:pointer;color:red;" class='openDncDelete' data-number={{$dnc->number}}><i class="fa fa-trash fa-lg"></i></a> @endif
                                    </td>

                                </tr>

                                @endforeach
                            </tbody>
                            
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
                            <input type="hidden" class="form-control" name="dnc" value="" >

                            <label for="inputEmail3" class="col-form-label closed">Excel <i data-toggle="tooltip" data-placement="right" title="Browser for Excel file" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <input type="file"  class="form-control closed" required name="dnc_file" id="dnc_file" placeholder="Select Excel File">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="submit" class="btn btn-info btn-ok">Save</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="modal fade" id="myModal" role="dialog">


            <div class="modal-dialog">
                <div class="modal-content" style="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="add-edit"></h4>
                    </div>

                    <form method="post" action="">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" class="form-control" name="dnc" value="" id="id">

                            <label for="inputEmail3" class="col-form-label closed number">Number <i data-toggle="tooltip" data-placement="right" title="Type the phone number you wish to add in DO NOT CALL database" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <input type="text" class="form-control closed" required name="number" id="number" placeholder="Please enter 10-digit phone number" data-inputmask="'mask': '(999) 999-9999'" data-mask="">


                            <label for="inputEmail3" class="col-form-label ">Extension <i data-toggle="tooltip" data-placement="right" title="Select extension" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            
                            <select class="form-control" required name="extension" id="extension">
                                <option value="">Please Select Extension</option>
                                @foreach($extension_list as $key => $extensions)

                                @if((request()->session()->get('level') > 9))
                                @if(($extensions->user_level <= 9) || ($extensions->extension == request()->session()->get('extension')))
                                <option value="{{$extensions->extension}}">{{$extensions->name}} - {{$extensions->extension}}</option>
                                @endif
                                

                                @elseif(($extensions->user_level < 9) || ($extensions->extension == request()->session()->get('extension')))
                                <option value="{{$extensions->extension}}">{{$extensions->name}}  - {{$extensions->extension}}</option>
                                @endif

                                @endforeach

                            </select>

                            <label for="inputPassword3" id="" class="col-form-label">Comments <i data-toggle="tooltip" data-placement="right" title="Type for comment if any" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <textarea class="form-control" required name="comment" id="comment" placeholder="Enter comments here"></textarea>
                            <span id="rchars">300</span> Character(s) Remaining


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
                        <p>You are about to delete <b><i class="title"></i></b> Do Not Call record.</p>
                        <p>Do you want to proceed?</p>
                        <input type="hidden" class="form-control" name="dnc_number" value="" id="dnc_number">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger btn-ok deleteDNC">Delete</button>

                    </div>
                </div>
            </div>

        </div>

        <?php  ?>
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script src="{{asset('asset/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{asset('asset/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{asset('asset/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
    <script>
        $(document).ready(function () {
        $('#number').inputmask("(999) 999-9999");
    })
    </script>
<script>
    var maxLength = 300;
    $('#comment').keyup(function()
    {
        var textlen = maxLength - $(this).val().length;
        $('#rchars').text(textlen);
    });

</script>
<script>
    // $(document).ready(function() {
    //     var oTable = $('#example').dataTable({
    //         "aoColumnDefs": [{
    //             "bSortable": false,
    //             "aTargets": [2, 3]
    //         }]
    //     });
    // });

    $(".openDncDelete").click(function() {
        var delete_id = $(this).data('number');
        $("#delete").modal();
        $("#dnc_number").val(delete_id);

    });


     $("#openExcelForm").click(function() {

        $("#myModalExcel").modal();
        $("#name").val('');
        $("#status").val('1');
        $("#id").val('');
        $(".closed").show();
        $("#upload-excel").html('Upload Excel');
        console.log('dnc_file');
    });

    $("#openDNCForm").click(function() {

        $("#myModal").modal();
       // document.getElementById("number").disabled = false;

        $("#number").val('');
        $("#extension").val('');
        $("#comment").val('');

        $(".closed").show();
        $("#add-edit").html('Add DNC');
    });

    $(document).on("click", ".editDnc", function() {
        $("#myModal").modal();
        $("#add-edit").html('Edit DNC');
        var edit_number = $(this).data('number');
        //$(".closed").hide();
        $(".number").hide();
        $("#number").hide();


       // document.getElementById("number").disabled = true;

        $.ajax({
            url: 'editDnc/' + edit_number,
            type: 'get',
            success: function(response) {

                $("#number").val(response[0].number);


                $("#extension").val(response[0].extension);
                $("#comment").val(response[0].comment);

                 $("#id").val(response[0].number);
            }
        });
    });

    $(document).on("click", ".deleteDNC", function() {
        // if(confirm("Are you sure you want to delete this?")){

        var delete_id = $("#dnc_number").val();

        var el = this;
        $.ajax({
            url: 'deleteDnc/' + delete_id,
            type: 'get',
            success: function(response) {
                window.location.reload(1);
            }
        });

        //}
        /*  else
          {
              return false;
          }*/
    });
</script>

<!-- <script>
    const showFilter = document.getElementById('show-filter');

   
function updateEntriesDisplayed(numEntries) {
  // Hide all entries
  const entries = document.querySelectorAll('.entry');
  entries.forEach(entry => entry.style.display = 'none');

  // Show the selected number of entries
  const entriesToShow = document.querySelectorAll(`.entry:nth-of-type(-n+${numEntries})`);
  entriesToShow.forEach(entry => entry.style.display = 'block');
}
showFilter.addEventListener('change', () => {
  const selectedValue = showFilter.value;
  updateEntriesDisplayed(selectedValue);
});
    </script> -->
    
@endsection
