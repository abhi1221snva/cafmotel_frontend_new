@extends('layouts.app')
@section('title', 'Upgrade Plan')
@section('content')
<link rel="stylesheet" href="<?php echo e(asset('asset/css/subscriptions.css')); ?>">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height:500px !important">
        <!-- Content Header (Page header) -->
         <section class="content-header">
                <h1>
                   <b>Upgrade Subscription</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Subscription</li>

                    <li class="active">Upgrade Subscription</li>
                </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                @include('layouts.messaging')
                <div class="price-sec-wrap">
                    <div class="container upgrade-plan">
                        <div class="row">
                            @php $intBoxSize = 3; @endphp
                            @if(isset($arrPackagesDetails))
                                @foreach($arrPackagesDetails as $strPackageName => $PackagesDetail)
                                    @if($arrTrialPackageDetails->days_remaining == 0 && $arrTrialPackageDetails->count >= 2 && strtolower($PackagesDetail->name) == 'trial')
                                        @php $intBoxSize = 4; @endphp
                                        @php continue; @endphp
                                    @endif
                                    <div class="col-lg-{{$intBoxSize}}">
                                        <div class="price-box">
                                            <div class="">
                                                <div class="price-label {{strtolower($PackagesDetail->name)}}">{{$PackagesDetail->name}} Plan</div>
                                                <div class="price"><?php if($PackagesDetail->currency_code == 'USD') echo '$'; elseif($PackagesDetail->currency_code == 'CAD') echo 'C$'; elseif($PackagesDetail->currency_code == 'INR') echo '₹'; else echo '$'; ?>{{$PackagesDetail->base_rate_monthly_billed}}</div>
                                                <div class="price-info">Per Month, Per User.</div>
                                            </div>
                                            <div class="info">
                                                @php print $PackagesDetail->description; @endphp
                                                    @if(strtolower($PackagesDetail->name) == 'trial')
                                                        @if($arrTrialPackageDetails->expired == TRUE)
                                                            <div class="no-action subscription-expired">Plan Expired!</div>
                                                        @else
                                                            <div class="no-action days-remaining">{{$arrTrialPackageDetails->days_remaining}} Days remaining</div>
                                                        @endif
                                                    @else
                                                        <select name="d_type" class="form-control billing-type">
                                                            <option value="#">Select Billing Period</option>
                                                            <option value="4">Yearly</option>
                                                            <option value="3">Half Yearly</option>
                                                            <option value="2">Quarterly</option>
                                                            <option value="1">Monthly</option>
                                                        </select>
                                                        <div class="number-of-users">
                                                            <div class="title">No. of users</div>
                                                            <span class="minus" data-page="upgrade"><span class="glyphicon glyphicon-minus"></span></span>
                                                            <input type="text" value="0"/>
                                                            <span class="plus" data-page="upgrade"><span class="glyphicon glyphicon-plus"></span></span>
                                                        </div>
                                                        <input type="hidden" class="selected-package" name="package-name" value="{{$PackagesDetail->name}}">
                                                        <a href="#" class="btn btn btn-primary waves-effect waves-light plan-btn add-to-cart">Add to cart</a>
                                                    @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
<script src="<?php echo e(asset('asset/js/pages/subscriptions.js')); ?>"></script>
@endsection
