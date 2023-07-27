<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="<?php echo e(asset('asset/bootstrap/css/bootstrap.min.css')); ?>">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="<?php echo e(asset('asset/css/AdminLTE.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('asset/plugins/iCheck/square/blue.css')); ?>">
    </head>
    <body>
        <section class="invoice">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        <img style="width: 119px;height: 43px;padding: 0px 0px 3px 0px;" src="{{ asset(env('INVOICE_COMPANY_LOGO')) }}"/>
                        {{env('INVOICE_COMPANY_NAME')}}
                        <small class="pull-right">Date: {{\Carbon\Carbon::parse($arrOrderData->order->created_at)->format('dS M Y')}}</small>
                    </h2>
                </div>
            </div>
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    From
                    <address>
                        <strong>{{env('INVOICE_COMPANY_NAME')}}</strong><br>
                        {{env('INVOICE_COMPANY_ADDRESS1')}}<br>
                        {{env('INVOICE_COMPANY_ADDRESS2')}}<br>
                        Phone: {{env('INVOICE_COMPANY_PHONE')}}<br>
                        Email: {{env('INVOICE_COMPANY_EMAIL')}}
                    </address>
                </div>
                <div class="col-sm-4 invoice-col">
                    To
                    <address>
                        <strong>{{$arrProspectDetails['name']}}</strong><br>
                        {{$arrProspectDetails['address1']}}<br>
                        {{$arrProspectDetails['address2']}}<br>
                        Phone: {{$arrProspectDetails['phone']}}<br>
                        Email: {{$arrProspectDetails['email']}}
                    </address>
                </div>
                <div class="col-sm-4 invoice-col">
                    <br>
                    <b>Order ID:</b> 1000-{{sprintf("%06d", $arrOrderData->order->id)}}<br>
                    <b>Account:</b> 9000-{{sprintf("%06d", $arrOrderData->order->client_id)}}
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Subscription</th>
                            <th>Billing Period</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $intTotal = 0;@endphp
                        @foreach($arrOrderData->orderItems as $key => $orderItem)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$orderItem->package_name}}</td>
                                <td>{{$orderItem->billing_period}}</td>
                                <td>{{$orderItem->quantity}}</td>
                                <td>${{$orderItem->amount}}</td>
                            </tr>
                            @php $intTotal += $orderItem->amount; @endphp
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6">
                    <p class="lead">Payment Methods:</p>
                    <img src="<?php echo e(asset('asset/img/visa.png')); ?>" alt="Visa">
                    <img src="<?php echo e(asset('asset/img/mastercard.png')); ?>" alt="Mastercard">
                    <img src="<?php echo e(asset('asset/img/american-express.png')); ?>" alt="American Express">
                    <img src="<?php echo e(asset('asset/img/paypal2.png')); ?>" alt="Paypal">
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        We do accept both, credit cards and Paypal.
                    </p>
                </div>
                <div class="col-xs-6">
                    <p class="lead">Grand Total</p>

                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th>Subtotal:</th>
                                <td>${{$intTotal}}</td>
                            </tr>
                            <tr>
                                <th>Tax (0%):</th>
                                <td>$0</td>
                            </tr>
                            <tr>
                                <th>Shipping:</th>
                                <td>$0</td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td>${{$intTotal}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </body>

    <script src="{{ asset('asset/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
</html>
<script>
    $(document).ready(function() {
        window.print();
    });
</script>
