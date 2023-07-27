@extends('layouts.app') @section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<div class="content-wrapper">

      <section class="content-header">
                <h1>
                   <b>Coupons</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Coupons</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                <a href="javascript:void(0);" onclick="openEditModal(0);" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Label</a>
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
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Amount/Percentage</th>
                                    <th>Currency Code</th>
                                    <th>Start Date</th>
                                    <th>Expire Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody style='text-align: center;'>
                                @foreach($coupons_list as $coupon)
                                    <tr>
                                        <td>{{$coupon->id}}</td>
                                        <td>{{$coupon->name}}</td>
                                        <td>{{$coupon->code}}</td>
                                        <td>{{$coupon->type}}</td>
                                        <td>{{$coupon->amount}}</td>
                                        <td>{{$coupon->currency_code}}</td>
                                        <td>{{$coupon->start_at}}</td>
                                        <td>{{$coupon->expire_at}}</td>
                                        <td>
                                            @if($coupon->status == "Active")
                                                <span class="label label-success">Active</span>
                                            @else
                                                <span class="label label-warning">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a style="cursor:pointer;color:blue;" href="javascript:void(0);" 
                                               onclick="openEditModal('{{$coupon->id}}');">
                                                <i class="fa fa-edit fa-lg"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content" style="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="add-edit"></h4>
                    </div>
                    <form id="couponForm" method="post" action="javascript:void(0)" onsubmit="submitCoupon();">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" class="form-control" name="coupon_id" value="0" id="coupon_id">
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label for="" class="col-form-label">Name</label>
                                    <input type="text" class="form-control" required name="name" id="name" placeholder="eg. New Year Promotion">
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="col-form-label">Code</label>
                                    <input type="text" class="form-control" required name="code" id="code" placeholder="eg. NYP_PRE_10">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label for="" class="col-form-label">Type</label>
                                    <select id="type_ddl" class="form-control" name='type' required >
                                        <option value="amount" selected>Fixed Amount</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="col-form-label">Amount/Percentage</label>
                                    <input type="number" step="1" min="1" class="form-control" required name="amount" id="amount" placeholder="eg. 10">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label for="" class="col-form-label">Currency Code</label>
                                    <select id="cc_ddl" class="form-control"  name='currency_code' required>
                                        <option value="USD" selected>USD</option>
                                        <option value="CAD">CAD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label for="" class="col-form-label">Start At</label>
                                    <input type="text" autocomplete="off" class="form-control col-md-6 datepicker" id="start_at" name="start_at" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="col-form-label">Expire At</label>
                                    <input type="text" autocomplete="off" class="form-control col-md-6 datepicker" id="expire_at" name="expire_at" required>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label for="" class="col-form-label">Status</label>
                                    <select id="status_ddl" class="form-control" name='status' required>
                                        <option value="Active" selected>Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="submit" class="btn btn-info btn-ok">Save</button>
                        </div>
                    </form>
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
    $(document).ready(function() {
        var oTable = $('#example').dataTable({
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [2, 3]
            }]
        });
    }).ajaxStart(function () {
        $('#preloader').css({'opacity': 0.5}).fadeIn();
        $('#preloader-status').show();
    }).ajaxStop(function () {
        $('#preloader').css({'opacity': 0}).fadeOut();
        $('#preloader-status').hide();
    });

    function openEditModal(id)
    {
        if(id == 0)
        {
            $("#add-edit").html("Add Coupon");
        }
        else
        {
            $("#add-edit").html("Edit Coupon");
            getCouponDetail(id);
        }
        $("input[type=text], input[type=number], input[type=hidden], select").val("");
        $("#editModal").modal();
    }
    
    function getCouponDetail(id)
    {
        $.ajax({
            url: 'coupon-detail/' + id,
            type: 'get',
            success: function(response) {
                if(response.success == true)
                {
                    var coupon = response.data;
                    $("#coupon_id").val(coupon[0].id);
                    $("#name").val(coupon[0].name);
                    $("#amount").val(coupon[0].amount);
                    $("#code").val(coupon[0].code);
                    $("#start_at").val(coupon[0].start_at);
                    $("#expire_at").val(coupon[0].expire_at);
                    $("#type_ddl").val(coupon[0].type);
                    $("#cc_ddl").val(coupon[0].currency_code);
                    $("#status_ddl").val(coupon[0].status);
                }
                else
                {
                    toastr.error(response.message);
                }
            }
        });
    }
    
    function submitCoupon()
    {
        $.ajax({
            url: 'coupon-edit',
            type: 'post',
            data: $('#couponForm').serialize(),
            success: function(response) {
                if(response.success == 'true')
                {
                    toastr.success(response.message);
                    window.location.reload();
                }
                else
                {
                    $(response.message).each(function(ind, ele) {
                        for (var key in ele) {
                            if (ele.hasOwnProperty(key)) {
                                toastr.error(ele[key]);
                            }
                        }
                    });
                }
            }
        });
    }
</script>
@endsection