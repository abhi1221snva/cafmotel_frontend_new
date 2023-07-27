@extends('layouts.app')
@section('title', 'Wallet Transactions')
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height:500px !important">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Payment Methods / Cards</h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row" style="text-align:right; padding-bottom:5px;">
                <div class="col-xs-12">
                    <a type="button" class="btn btn-primary" href="{{'edit-payment-method'}}">Add Card</a>
                </div>
            </div>
            @if(count($paymentMethods) > 0 )
                @foreach($paymentMethods as $paymentMethod)
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box box-primary box-solid collapsed-box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <img class="img-border rounded" onerror="this.src='{{url('/logo/card.png')}}'" 
                                             src="{{url('/logo/')}}/{{$paymentMethod->card->brand}}.png" width="25px;">
                                        {{isset($paymentMethod->card->brand) ? ucwords($paymentMethod->card->brand) : "" }} card ending in {{isset($paymentMethod->card->last4) ? $paymentMethod->card->last4 : "" }} </h3>
                                    <div class="box-tools pull-right">
                                        <span>Expired : <b>{{isset($paymentMethod->card->exp_month) ? $paymentMethod->card->exp_month : "" }}/{{isset($paymentMethod->card->exp_year) ? $paymentMethod->card->exp_year : "" }}</b></span>
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                            <i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-xs-6" style="text-align: center;">
                                            <b>Name on Card</b> <br> {{isset($paymentMethod->billing_details->name) ? $paymentMethod->billing_details->name : "" }}
                                        </div>
                                        <div class="col-xs-6" style="text-align: center;">
                                            <b>Billing Address</b> <br>
                                            {{isset($paymentMethod->billing_details->address->city) ? $paymentMethod->billing_details->address->city : "" }}, {{isset($paymentMethod->billing_details->address->state) ? $paymentMethod->billing_details->address->state : "" }},<br>
                                            {{isset($paymentMethod->billing_details->address->line1) ? $paymentMethod->billing_details->address->line1 : "" }},<br>
                                            {{isset($paymentMethod->billing_details->address->country) ? $paymentMethod->billing_details->address->country : "" }}, {{isset($paymentMethod->billing_details->address->postal_code) ? $paymentMethod->billing_details->address->postal_code : "" }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12" style="text-align: right;">
                                            <a type="button" class="btn btn-primary" href="javascript:void(0);" onclick="deletePaymentMethod('{{$paymentMethod->id}}')">Remove</a>
                                            <a type="button" href="{{url('/update-payment-method')}}/{{$paymentMethod->id}}" class="btn btn-primary">Edit</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <script>
        function deletePaymentMethod(id) {
            if(confirm("Are you sure want to delete this card?")) {
                $.ajax({
                    type: 'GET',
                    url: '{{url('delete-payment-method')}}/'+id,
                    success: function (response) {
                        console.log(response);
                        if (response.success == true || response.success == 'true') {
                            toastr.success("Payment method delete successfully.");
                            location.reload();
                        } else {
                            toastr.error("Smoething went worng!!!");
                            console.log(response);
                        }
                    },
                    error: function (response) {
                        $("section.waiting-section").hide();
                        toastr.error("Smoething went worng!!!");
                        console.log(response);
                    }
                });
            }
        }
    </script>
@endsection
