@extends('layouts.app')
@section('title', 'View Packages')
@section('content')

<div class="content-wrapper">


     <section class="content-header">
                <h1>
                    <b>View Packages</b>
                    
                </h1>
                <ol class="breadcrumb">
                     <li><a href="{{('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> System Configuration</li>
                     
                    <li class="active">View Packages</li>
                </ol>
        </section>
    <section class="content-header">
       

          <div class="text-right mt-5 mb-3"> 
            <a href="{{ url('/super/packages') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show Packages</a>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <i class="fa fa-text-width"></i>
                        <h3 class="box-title">Package Details</h3>
                    </div>

                    <div class="box-body">
                        <dl class="dl-horizontal">
                            <dt>Name</dt>
                            <dd>{{$package['name']}}</dd>
                            <dt>Description</dt>
                            <dd class="description">@php print $package['description']; @endphp</dd>
                            <dt>Status</dt>
                            <dd>
                                @if($package['is_active'] == 1)
                                <span class="badge bg-green">Active</span>
                                @else
                                <span class="badge bg-red">Inactive</span>
                                @endif
                            </dd>
                            <dt>Display Order</dt>
                            <dd>{{$package['name']}}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <i class="fa fa-text-width"></i>
                        <h3 class="box-title">Modules</h3>
                    </div>

                    <div class="box-body">
                        <dl class="dl-horizontal">
                            <dt>Show On</dt>
                            <dd>
                                @php
                                $show_on =implode(',',$package['show_on']);
                                @endphp
                                {{$show_on}}
                            </dd>
                            <dt>Currency Code</dt>
                            <dd><span class="badge bg-yellow">{{$package['currency_code']}}</span></dd>
                            <dt>Modules</dt>
                            <dd>
                                @php
                                $modules =implode(',',$package['modules']);
                                @endphp
                                {{$modules}}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <i class="fa fa-text-width"></i>
                        <h3 class="box-title">Package Price In <span class="badge bg-yellow">{{$package['currency_code']}}</span></h3>
                    </div>

                    <div class="box-body">
                        <dl class="dl-horizontal">
                            <dt>Monthly Billed</dt>
                            <dd><i class="fa fa-usd" aria-hidden="true"></i> {{$package['base_rate_monthly_billed']}}</dd>
                            <dt>Quarterly Billed</dt>
                            <dd><i class="fa fa-usd" aria-hidden="true"></i> {{$package['base_rate_quarterly_billed']}}</dd>
                            <dt>Half Yearly Billed</dt>
                            <dd><i class="fa fa-usd" aria-hidden="true"></i> {{$package['base_rate_half_yearly_billed']}}</dd>

                            <dt>Yearly Billed</dt>
                            <dd><i class="fa fa-usd" aria-hidden="true"></i> {{$package['base_rate_yearly_billed']}}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <i class="fa fa-text-width"></i>
                        <h3 class="box-title">Rate In <span class="badge bg-yellow">{{$package['currency_code']}}</span></h3>
                    </div>

                    <div class="box-body">
                        <dl class="dl-horizontal">
                            <dt>Call Per Minute</dt>
                            <dd><i class="fa fa-usd" aria-hidden="true"></i> {{$package['call_rate_per_minute']}}</dd>
                            <dt>Call Per Sms</dt>
                            <dd><i class="fa fa-usd" aria-hidden="true"></i> {{$package['rate_per_sms']}}</dd>
                            <dt>Call Per Phone No</dt>
                            <dd><i class="fa fa-usd" aria-hidden="true"></i> {{$package['rate_per_did']}}</dd>
                            <dt>Call Per Fax</dt>
                            <dd><i class="fa fa-usd" aria-hidden="true"></i> {{$package['rate_per_fax']}}</dd>
                            <dt>Call Per Email</dt>
                            <dd><i class="fa fa-usd" aria-hidden="true"></i> {{$package['rate_per_email']}}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </div>
<style>
    .box-body .description ul{
        padding-left: 0;
    }
</style>
@endsection
