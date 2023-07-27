@extends('layouts.app')

@section('title', 'Did List')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

     <section class="content-header">
                <h1>
                   <b> Buy Phonenumber PLIVO</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Inbound Configuration</li>
                    <li class="active">Buy Phonenumber</li>
                </ol>
        </section>
        <section class="content-header">

                   <div class="text-right mt-5 mb-3">

                   <a href="{{url('/did')}}"  type="button" class="btn btn-sm btn-primary"><i class="fa fa-arrow-left"></i>&nbsp; Back </a>
           </div>
        </section>


    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Please enter any starting digit of phone number, example 949 will search numbers starting 949.
                        </h3>
                    </div>

                    <div class="box-body" style="padding: 20px 20px;">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group m-b-10">
                                    <div class="col-md-3">
                                        <label>Country <i data-toggle="tooltip" data-placement="right" title="Select country name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Number Type <i data-toggle="tooltip" data-placement="right" title="Select number type" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Show <i data-toggle="tooltip" data-placement="right" title="Show no of list  for phone number" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Number <i data-toggle="tooltip" data-placement="right" title="Type number" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding-bottom: 5px;">
                            <div class="col-sm-3">
                                <select id="country" class="form-control">
                                    <option value="1">United States (+1)</option>
                                    <option value="1">Canada (+1)</option>
                                    <option value="44">United Kingdom (+44)</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select id="numberType" class="form-control">
                                    <option value="local">Local</option>
                                    <option value="tollfree">Toll Free</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select id="show" class="form-control">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input id='phone' type="text" class="form-control" data-inputmask="'mask': '(999) 999-9999'" data-mask="" />
                            </div>
                        </div>
                        <div class="row" style="padding-top:5px;">
                            <div class="col-sm-12">
                                <div class="form-group" style="float:right;">
                                    <tfoot>
                                        <tr>
                                            <td>
                                                <button onclick="getDidList();" class="btn btn-primary" type="button"><i class="fa fa-check-square-o fa-lg"></i>
                                                    Submit
                                                </button>
                                                &nbsp;
                                                <a type="button" class="btn btn-warning"  onclick="window.location.reload();"><i class="fa fa-refresh fa-lg"></i>
                                                    Reset
                                                </a>
                                                &nbsp;
                                                <a type="button" class="btn btn-danger" style="margin-right: 14px;" href="{{url('/did')}}">
                                                    <i class="fa fa-close fa-lg"></i>
                                                    Cancel
                                                </a>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding-top:5px;">
                            <div class="col-sm-12">
                                <p id="no_data_found" style="display: none;text-align: left;color:red;"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table id="did_list_table" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="text-align: center;"><input type="checkbox" id="select_all_checkbox" onclick="checkDIds();" /></th>
                                    <th style="text-align: center;">Number(s)</th>
                                    <th style="text-align: center;">State</th>
                                    <th style="text-align: center;">Type</th>
                                </tr>
                            </thead>
                            <tbody id="did_list_body_html" style="text-align: center;"></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" style="text-align: right;">
                                        <input id="buy_number_btn" style="display: none;" value="Buy" type="button" class="btn btn-warning" onclick="validateOrder();" />
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>

<div class="modal fade modal-success" id="buy" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #d33724 !important;color:white;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Confirm Order</h4>
            </div>
            <div class="modal-body" id="buy_modal_body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success btn-ok" onclick="buyDid();">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- /.content-wrapper -->
<script>
    $(document).ready(function () {
        $('#phone').inputmask("(999) 999-9999");
        initDT([]);
    }).ajaxStart(function () {
        $('#preloader').css({'opacity': 0.5}).fadeIn();
        $('#preloader-status').show();
    }).ajaxStop(function () {
        $('#preloader').css({'opacity': 0}).fadeOut();
        $('#preloader-status').hide();
    });

    function getDidList() {
        if ($("#phone").val() == '' || $("#phone").val().length < 3) {
            toastr.error('Phone number length must be equal to or more than 3');
            return;
        }

        $.ajax({
            url: root_url + '/get-did-list-from-plivo',
            type: 'post',
            data: {'country': $("#country").val(), 'numberType': $("#numberType").val(),
                'show': $("#show").val(), 'phone': $("#phone").val()},
            success: function (response) {
                var dateSet = response;
                if (dateSet.length > 0) {
                    $("#buy_number_btn").show();
                    $("#no_data_found").hide();
                } else {
                    var num = $("#phone").val();
                    num = num.replaceAll('(', '');
                    num = num.replaceAll(')', '');
                    num = num.replaceAll('_', '');
                    num = num.replaceAll('-', '');
                   // $("#no_data_found").html('<b>This ' + num + ' combination is not available currently. Please Click on the Create Order button below in order to raise the order manually for phone number with area code ' + num + ' within 12- 24 hours with subject to availability.</b> &nbsp;&nbsp;<button class="btn btn-primary btn-sm" onclick="backOrder();">Create Order</button>');

                    $("#no_data_found").html('<b>We are currently unable to offer your requested numbers</b>');

                    
                    $("#no_data_found").show();
                    $("#buy_number_btn").hide();
                }
                initDT(dateSet);
            },
            error: function (response) {
                toastr.error(response.responseJSON);
            }
        });
    }

    function initDT(dateSet) {
        $('#did_list_table').dataTable({
            destroy: true,
            paging: true,
            lengthChange: true,
            ordering: false,
            data: dateSet
        });
    }

    function checkDIds() {
        if ($("#select_all_checkbox").prop('checked') == true) {
            $('.did_checkbox').prop('checked', true);
        } else {
            $('.did_checkbox').prop('checked', false);
        }
    }

    function validateOrder() {
        var checked = 1;
        $('.did_checkbox').each(function () {
            if (this.checked == true) {
                checked = 0;
            }
        });
        if (checked) {
            toastr.error('Please select at least one number');
            return;
        }

        var didHtml = '';
        var total_amount = 0;
        var amount = 2;
        $(".did_checkbox").each(function (ind, ele) {
            if (this.checked == true) {
                didHtml += "<p> DID "+ele.value+" - $"+amount+"</p>";
                total_amount += amount;
            }
        });

        var html = "<p>Please Confirm the order for phone number(s) below:</p>";
        html += didHtml;
        html += "<p>Total Amount : $"+total_amount+" will be deducted from your balance every month.</p>"
        $('#buy_modal_body').html(html);
        $('#buy').modal();
    }

    function buyDid() {
        $('#buy').modal('hide');
        var dids = [];
        $(".did_checkbox").each(function (ind, ele) {
            if (this.checked == true) {
                var DidObj = new Object();
                DidObj.value = ele.value;
                DidObj.ratecenter = ele.getAttribute('data-ratecenter');
                DidObj.referenceid = ele.getAttribute('data-referenceid');
                DidObj.state = ele.getAttribute('data-state');
                DidObj.didtype = ele.getAttribute('data-didtype');

                var DidObjJsonString= JSON.stringify(DidObj);
                dids.push(DidObjJsonString);
            }
        });

        $.ajax({
            url: root_url + '/buy-did-plivo',
            type: 'post',
            data: {'number': dids, 'provider': 1, 'voip_provider': 'plivo', 'country_code': $("#country").val()},
            success: function (response) {
                toastr.success(response.message);
                setTimeout(function () {
                    window.location.href = root_url + "/did";
                }, 700);
            },
            error: function (response) {
                toastr.error(response.message);
            }
        });
    }

    function backOrder() {
        toastr.success('Back Order Created');
        setTimeout(function () {
            window.location.href = root_url + "/did";
        }, 700);
    }
</script>

<!-- InputMask -->
<script src="{{url('/asset/js/input-mask/jquery.inputmask.js')}}"></script>
<script src="{{url('/asset/js/input-mask/jquery.inputmask.extensions.js')}}"></script>
@endsection
