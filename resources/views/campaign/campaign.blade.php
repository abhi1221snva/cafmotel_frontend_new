@extends('layouts.app')

@push("styles")
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

         <section class="content-header">
                <h1>
                    <b>Campaign</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Campaign</li>
                    <li class="active">Campaign List</li>
                </ol>
        </section>

        <section class="content-header">

             <div class="text-right mt-5 mb-3"> 
           
               
                <a href="{{ url('/add-campaign') }}"  type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Campaign</a>
           </div>
           
            <ol class="breadcrumb">
                
            </ol>
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
                                    <th>Name</th>
                                    <th>Call Time</th>
                                    <th>Lists associated</th>
                                    <th>Dialled Leads/Total Leads</th>
                                    <th>Hopper Count</th>
                                    <th>Hopper Mode</th>

                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($campaign_list))
                                    @foreach($campaign_list as $key => $campaign)
                                        @if(!empty($campaign->title))
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{$campaign->title}}</td>
                                                <td>{{date('g:i A',strtotime($campaign->call_time_start))}} to {{date('g:i A',strtotime($campaign->call_time_end))}}</td>
                                                <td>{{$campaign->rowList}}</td>
                                                <td>{{$campaign->rowLeadReport}} / {{$campaign->rowListData}}</td>
                                                <td>{{$campaign->rowLeadTemp}}</td>
                                                <td>
                                                    @if($campaign->hopper_mode == '1')
                                                        <span class="label label-success">Linear</span>
                                                    @else ($campaign->hopper_mode == '2')
                                                        <span class="label label-info">Random</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($campaign->status == '0')
                                                        <span class="label label-danger">Inactive</span>
                                                    @else ($campaign->status == '1')
                                                        <span class="label label-success">Active</span>
                                                    @endif
                                                </td>
                                                <td>{{date('Y-m-d H:i',strtotime($campaign->updated))}}</td>
                                                <td>
                                                    <a style="cursor:pointer;color:blue;" href="{{url('campaign')}}/{{$campaign->id}}" class='' data-id={{$campaign->id}} ><i class="fa fa-edit fa-lg"></i></a>
                                                    @if(session()->get('level') >= 7)
                                                    | <a style="cursor:pointer;color:blue;" class='reloadHopper' data-campaign={{$campaign->id}} data-url="{{ route('reloadHopper', ['campaign'=>$campaign->id]) }}"><i class="fa fa-repeat"></i></a>
                                                    @endif
                                                    | <a style="cursor:pointer;color:blue;" href="{{url('copy-campaign/')}}/{{$campaign->id}}" class="" data-id="{{$campaign->id}}"><i class="fa fa-copy fa-lg"></i></a>
                                                    | <a style="cursor:pointer;color:red;" class='openCampaignDelete' data-campaign={{$campaign->id}} ><i class="fa fa-trash fa-lg"></i></a>
                                                    | <a style="cursor:pointer;color:blue;" href="{{url('campaign/list')}}/{{$campaign->id}}" class=''><i class="fa fa-list fa-lg"></i></a></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->

                <div class="modal fade" id="delete" role="dialog">
                    <!-- Modal content-->
                    <div class="modal-dialog">
                        <div class="modal-content" style="background-color: #d33724 !important;color:white;">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                            </div>
                            <div class="modal-body">
                                <p>You are about to delete <b><i class="title"></i></b> Campaign.</p>
                                <p>Do you want to proceed?</p>
                                <input type="hidden" class="form-control" name="campaign_id" value="" id="campaign_id">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger btn-ok deleteCampaign">Delete</button>
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
@endsection

@push("scripts")
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(".openCampaignDelete").click(function () {
            var delete_id = $(this).data('campaign');
            $("#delete").modal();
            $("#campaign_id").val(delete_id);

        });
        $(".reloadHopper").click(function (e) {
            e.preventDefault();
            var url = $(this).data('url');
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    alert(response.message);
                },
                error: function (jqXHR, exception) {
                    console.log(jqXHR.responseText);
                    var resp = JSON.parse(jqXHR.responseText);
                    alert("Failed to send the campaign reload request.\n"+resp.message);
                }
            });
        });
        $(document).on("click", ".deleteCampaign", function () {
            // if (confirm("Are you sure you want to delete this record?")) {
            var campaign = $("#campaign_id").val();
            //alert(campaign);
            //var account_no = $(this).data('account_no');
            //alert(account_no);
            var el = this;
            $.ajax({
                url: 'deleteCampaign/' + campaign,
                type: 'get',
                success: function (response) {
                    toastr.success('Campaign has been deleted successfully');
                    window.location.reload(1);
                }
            });
        });
        $(document).ready(function () {
            var oTable = $('#example').dataTable({
                "aoColumnDefs": [{
                    "bSortable": false,
                    "aTargets": [2, 3, 4]
                }]
            });
        });
    </script>
@endpush
