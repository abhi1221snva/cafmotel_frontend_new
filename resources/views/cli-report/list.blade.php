@extends('layouts.app')
@section('title', 'Caller ID Name Reports')
<div class="dialog-background" id="loading" style="display: none;">
    <div class="dialog-loading-wrapper">
        <img src="{{ asset('asset/img/lp.gif') }}">
    </div>
</div>

<style type="text/css">
.dialog-background
{
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

.dialog-loading-wrapper
{
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
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<!-- Content Wrapper. Contains page content -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height:500px !important">
        <!-- Content Header (Page header) -->

        <section class="content-header">
                <h1>
                   <b>Caller ID Name Reports</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Caller ID Name Reports</li>
                </ol>
                <div class="text-right">
                <a href="{{ url('/cli-report') }}" class="btn btn-sm btn-primary">Back</a>
               </div>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
               <!-- <form method="post">
                    @csrf
                    <a href="{{ url('/smtp') }}"  type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Smtp</a>
                </form> -->
           </div>
        </section>
           

        <!-- Main content -->
        <section class="content">
            <div class="row">
            <?php
        $url_page = explode('?',str_replace('/','',$_SERVER['REQUEST_URI']));
        $url = $url_page[0];
            if (!empty($cli_report)) {
               
           

            if($page == 1)
            {
                $currentPage = 1;
            }

            else
            {
                $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            }

            $perPage = $show;
            $paginator = new Illuminate\Pagination\LengthAwarePaginator($cli_report,$record_count,$perPage,$currentPage,['path' => url($url)]);
            $record_count = $paginator->total();
        ?>

                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                        
               
                   
                            <form class="form-inline form-dialer" action="{{url('/cli-report')}}" method="post">
                                @csrf
                                                                <div class="row-fluid">
                                    <div class="form-group">
                                        <input type="" onkeypress="return isNumberKey($(this));" pattern="[0-9]+" id="phone_number" name="phone_number" value="" class="form-control" minlength="10" maxlength="12" placeholder="Phone Number" required="">
                                    </div>
                                    <div class="form-group">
                                        <button type="button" id="search_lead" class="btn btn-success waves-effect waves-light m-l-10" value="Search">Run Maually Call for CNAM</button>
                                    </div>
                                    <div class="form-group" style="margin-left: 15%;">
                                        <div class="row text-danger text-center" id="form_error">
                                                                                    </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="box">
                  
                        <div class="box-body">
                        <form method="GET" action="">
                    <label for="show">Show:</label>
                        <select name="show" onchange="this.form.submit()">
                            <option value="10" {{ request('show') == 10 ? 'selected' : '' }}selected>10</option>
                            <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('show') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <label for="entries">entries</label>
                    </form>
                    <div class="text-right"style="margin-bottom:10px;">
                    <form action="{{ url('/cli-report') }}" method="get">
                        <input type="text" name="search"id="search" placeholder="cli or cnam">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                    </div>
                    <b>Total Rows :<?= $record_count ?></b>
                            <table id="example" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Caller ID</th>
                                    <th>Caller ID Name</th>
                                    <th>Generation Date</th>
                                    
                                </tr>
                                </thead>
                                <tbody>

                                @if(!empty($cli_report))
                                    @foreach($cli_report as $key => $smtp)

                                        <tr>
                                            <td>{{ ($paginator->currentPage() - 1) * $paginator->perPage() + $key + 1 }}</td>
                                            <td>{{$smtp->cli}}</td>
                                            
                                            <td>{{$smtp->cnam}}</td>

                                            <td>{{$smtp->created_date}}</td>
                                            


                                            

                                        </tr>

                                    @endforeach
                                @endif
                                </tbody>

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
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
                <?php } ?>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


    <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">CNAM Record</h4>
      </div>
      <div class="modal-body">
        <table id="smsListTable" class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Number</th>
                                        <th>CNAM</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>

                                <tbody id="hiddenAfterSms">
                                
                                </tbody>
                            </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div ng-app="myAppReport" ng-controller="myCtrlReport"></div>


@endsection
@push('scripts')

<script>

    
        var app = angular.module('myAppReport', []);
        app.controller('myCtrlReport', function ($scope, $http, $interval)
        {
            phone_number = $("#phone_number").val();

            i=0;
            var interval;
            interval = $interval(function ()
            {
                $scope.displayDataReport();  
            }, 1000);

            $scope.displayDataReport = function()
            { 
                $http({ url: '/find-cli-report/'+phone_number, method: "get"})
                .success(function(response_data) {                 
                    var res_length = Object.keys(response_data.data).length
                    if (res_length > 0) {
                        $('#hiddenAfterSms').html("");
                        var elem = document.getElementById('hiddenAfterSms');
                        for (var i = 0; i < res_length; i++) {
                            var obj = response_data.data[i];
                            var localTime = obj.created_date;
                            var created_date = localTime;
                            elem.innerHTML = elem.innerHTML + '<tr><td class="mailbox-name"  >' + obj.cli + '</td><td class="mailbox-name"  >' + obj.cnam + '</td><td class="mailbox-date" style="width:180px;">' + created_date + '</td></tr>';
                        }
                    }
                });  
            }
        });
    
    
</script>

<script language="javascript">
    $(document).ready(function () {
        $("#search_lead").click(function() {
            $('#loading').show();
            phone_number = $("#phone_number").val();
           

            $.ajax({
                type: "get",
                url: "/cli-report-manually/"+phone_number,
                //data: postData,
                dataType: "json",
                success: function (data) {
                    $('#myModal').modal();
                    $('#loading').hide();
                }
            });
        });
    });

    // $(document).ready(function () {
    //     var oTable = $('#example').dataTable({
    //         "aoColumnDefs": [
    //         {"bSortable": false, "aTargets": [2, 3]}
    //         ]
    //     });
    // });

    function deleteSetting(id) {
        $("#delete-" + id).hide();
        postData = {
            "_token": "{{ csrf_token() }}"
        };

        $.ajax({
            type: "POST",
            url: "/smtp-delete/" + id,
            data: postData,
            dataType: "json",
            success: function (data) {
                console.log(data);
                if (data.success) {
                    $("#alert-success").html(data.message).show();
                    setTimeout(function() {
                        window.location.reload(1);
                    }, 2000);
                }
                else {
                    $("#alert-errors").html(data.message).show();
                }
            },
            error: function (xhr, status, error) {
                $("#alert-errors").html(error).show();
            }
        });
    }

</script>
<!-- <script>
     function fetch_data(page, sort_type, sort_by, query)
 {
  $.ajax({
   url:"/cli_report/fetch_data?"&query="+query",
   success:function(data)
   {
    $('tbody').html('');
    $('tbody').html(data);
   }
  })
 }
$(document).on('keyup', '#search', function(){
  var query = $('#search').val();
 
  fetch_data( query);
 });
 </script> -->
@endpush
