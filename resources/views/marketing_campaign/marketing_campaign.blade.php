@extends('layouts.app') @section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
                <h1>
                   <b>Marketing Campaign</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Marketing Campaigns</li>

                    <li class="active">Marketing Campaign List</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                <a href="{{ url('/add-marketing-campaign') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Marketing Campaign</a>
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

                                    <th>Title</th>
                                    <th>Description</th>
                                     <th>Mail Gateway</th>
                                     <th>Mail Name</th>


                                     <th>SMS Gateway</th>
                                     <th>SMS Name</th>
                                     <th>Campaign Runtime</th>
                                     <th>Send Report</th>




                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($marketing_campaign_list as $key => $marketing)
                                <tr>

                                    <td>{{ $key+1 }}</td>

                                    <td>{{$marketing->title}}</td>
                                    <td>{{$marketing->description}}</td>
                                    <td>{{$marketing->mail_gateway_type}}</td>
                                    <td>{{$marketing->mail_gateway}}</td>
                                    <td>{{$marketing->sms_gateway_type}}</td>
                                    <td>{{$marketing->sms_gateway}}</td>
                                    <td>{{$marketing->campaign_run_times}}</td>
                                    <td>
                                        @if($marketing->send_report == '1')
                                        Yes
                                        @elseif($marketing->send_report == '0')
                                        No
                                        @endif

                                    </td>

                                    <td>
                                        <a style="cursor:pointer;color:blue;" href="{{url('marketing-campaign')}}/{{$marketing->id}}" class='' data-id={{$marketing->id}} ><i class="fa fa-edit fa-lg"></i></a> | <a style="cursor:pointer;color:red;" class='openCampaignDelete' data-campaign={{$marketing->id}} ><i class="fa fa-trash fa-lg"></i></a></td>

                                </tr>
                                @endforeach
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

<script>


     $(".openCampaignDelete").click(function() {
        var delete_id = $(this).data('campaign');
        $("#delete").modal();
        $("#campaign_id").val(delete_id);

    });
    $(document).on("click", ".deleteCampaign", function() {

       // if (confirm("Are you sure you want to delete this record?")) {
            var campaign = $("#campaign_id").val();
            //alert(campaign);
            //var account_no = $(this).data('account_no');
            //alert(account_no);
            var el = this;
            $.ajax({
                url: 'deleteMarketingCampaign/' + campaign,
                type: 'get',
                success: function(response) {
                    window.location.reload(1);
                }
            });

        /*} else {
            return false;
        }*/
    });
</script>

<script>
    $(document).ready(function() {
        var oTable = $('#example').dataTable({
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [2, 3, 4]
            }]
        });
    });
</script>
@endsection