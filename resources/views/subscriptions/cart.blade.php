@extends('layouts.app')
@section('title', 'Cart')
@section('content')
<link rel="stylesheet" href="<?php echo e(asset('asset/css/subscriptions.css')); ?>">
<div class="content-wrapper" style="min-height:500px !important">
    <!-- Content Header (Page header) -->

      <section class="content-header">
                <h1>
                   <b>Cart</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Subscription</li>
                    
                    <li class="active">Cart</li>
                </ol>
        </section>
        
    

    <!-- Main content -->
    <section class="content">
        @if($cartItems)
        <div class="row">
            @include('layouts.messaging')
            <div class="col-md-12 col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Subscription</th>
                                <th>Billing Period</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Remove</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $intTotal = 0;@endphp
                            @if(isset($cartItems))
                                @foreach($cartItems as $cartId => $cartItem)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$cartItem->product}}</td>
                                        <td class="billing-period" data-billing="{{$cartItem->billing}}">{{$cartItem->billing_period}}</td>
                                        <td>
                                            <div class="number-of-users" data-id="{{$cartId}}">
                                                <span class="minus" data-page="cart"><span class="glyphicon glyphicon-minus"></span></span>
                                                <input type="text" value="{{$cartItem->quantity}}"/>
                                                <span class="plus" data-page="cart"><span class="glyphicon glyphicon-plus"></span></span>
                                            </div>
                                        </td>
                                        <td class="product-price" data-price="{{$cartItem->base_rate_monthly_billed}}">${{$cartItem->subtotal}}</td>
                                        <td><a style="cursor:pointer;color:red;" class='delete-cart-item' data-id={{$cartId}}><i class="fa fa-trash fa-lg"></i></a></td>
                                    </tr>
                                    @php $intTotal += $cartItem->subtotal; @endphp
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <p class="lead">Payment Methods:</p>
                        <img src="<?php echo e(asset('asset/img/visa.png')); ?>" alt="Visa">
                        <img src="<?php echo e(asset('asset/img/mastercard.png')); ?>" alt="Mastercard">
                        <img src="<?php echo e(asset('asset/img/american-express.png')); ?>" alt="American Express">
                        <img src="<?php echo e(asset('asset/img/paypal2.png')); ?>" alt="Paypal">

                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                            We do accept both, credit cards and Paypal.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <p class="lead">Amount Due</p>
                    <table class="table table-bordered table-hover">

                        <tbody>
                        <tr>
                            <th>Subtotal:</th>
                            <td class="subtotal-price">${{$intTotal}}</td>
                        </tr>
                        <tr>
                            <th>Tax (0%):</th>
                            <td class="tax-price" data-price="0">$0</td>
                        </tr>
                        <tr>
                            <th>Shipping:</th>
                            <td class="shipping-price" data-price="0">$0</td>
                        </tr>
                        <tr>
                            <th>Total:</th>
                            <td class="grand-total">${{$intTotal}}</td>
                        </tr>

                        </tbody>
                    </table>

                        <a href="{{url('/checkout')}}" class="btn btn btn-primary waves-effect waves-light plan-btn proceed-checkout">Proceed to Checkout</a>

                    </div>
                </div>
            </div>
        </div>
        @else
            <div class="cart-empty">Your cart is empty <i class="fa fa-shopping-cart"></i></div>
        @endif
    </section>
</div>
<!-- /.content-wrapper -->
<script src="<?php echo e(asset('asset/js/pages/subscriptions.js')); ?>"></script>
@endsection
