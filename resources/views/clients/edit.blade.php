@extends('layouts.app')
@push('styles')
    <style>
        .status-label-container {
            margin-top: 5px;
        }

        .status-label {
            font-size: 100%;
            padding: 6px;
        }
    </style>
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
                <h1>
                    <b>Client - {{$client["company_name"]}}</b>

                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> System Configuration</li>

                    <li class="active">Client - {{$client["company_name"]}}</li>
                </ol>
        </section>

        <section class="content-header">
            <div class="text-right mt-5 mb-3">
                <a href="{{ url('/clients') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show Clients</a>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <form method="post" role="form" name="editForm" id="editForm"
                              action="{{url('client')."/".$client["id"]}}">
                            @csrf
                            <input type="hidden" class="form-control" name="id" value="{{$client["id"]}}" id="id"
                                   required>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_name">Name  <i data-toggle="tooltip" data-placement="right" title="Type the client's name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <input type="text" class="form-control" name="company_name"
                                                   id="company_name" value="{{$client["company_name"]}}" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="stage">Status  <i data-toggle="tooltip" data-placement="right" title="It shows the status of the client setup" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div
                                                class="select2-container select2-container--default status-label-container">
                                                @if($client["stage"] < 5)
                                                    <span class="label label-warning status-label"><i
                                                            class="icon fa fa-ban"></i> Pending ({{$client["stage"]}}/5)</span>
                                                @else
                                                    <span class="label label-success status-label"><i
                                                            class="icon fa fa-check"></i> Ready</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address_1">Building/Street  <i data-toggle="tooltip" data-placement="right" title="Type the client's address" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <input type="text" class="form-control" name="address_1" id="address_1"
                                                   value="{{$client["address_1"]}}"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address_2">Area/State/Zipcode  <i data-toggle="tooltip" data-placement="right" title="Type area,zipcode etc" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <input type="text" class="form-control" name="address_2" id="address_2"
                                                   value="{{$client["address_2"]}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="trunk">Trunk <i data-toggle="tooltip" data-placement="right" title="Type the Voice Trunk" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <input type="text" class="form-control" name="trunk" id="trunk"
                                                   value="{{$client["trunk"]}}" maxlength=30/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="asterisk_servers">Server Allotted  <i data-toggle="tooltip" data-placement="right" title="Select multiple server from the drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <select class="form-control select2" multiple name="asterisk_servers[]"
                                                    id="asterisk_servers" data-placeholder="Asterisk Servers"
                                                    style="width: 100%;">
                                                @if(!empty($client["asteriskServerList"]))
                                                    @foreach($client["asteriskServerList"] as $server)
                                                        <option
                                                            value={{$server["id"]}} @if(isset($client["clientServers"][$server["id"]])) {{'selected="selected"'}} @endif >{{$server["host"]}}
                                                            - {{$server["location"]}} - {{$server["trunk"]}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <?php /*?>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="trunk">SMS Plateform  <i data-toggle="tooltip" data-placement="right" title="Select Sms Plateform" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                <select class="form-control" required name="sms_plateform" id="sms_plateform">

                                                    <option @if($client["sms_plateform"] == 'didforsale') selected @endif value="didforsale">DID For Sale</option>
                                                    <option @if($client["sms_plateform"] == 'plivo') selected @endif value="plivo">Plivo</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <?php */ ?>

                                </div>

                                <div class="row">
                                <div class="col-md-6">
                                                <label>Enable 2FA <i data-toggle="tooltip" data-placement="right" title="Select Yes/No For Enable 2FA" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                <div class="input-daterange input-group col-md-12">
                                                    <select class="form-control" name="enable_2fa" id="enable_2fa" >
                                                        <option @if($client["enable_2fa"] == '0') selected @endif value="0">No</option>
                                                        <option @if($client["enable_2fa"] == '1') selected @endif value="1">Yes</option>

                                                    </select>
                                                </div>
                                            </div>
                                    <div class="col-md-6">
                                        
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label>
                                                <input name="sms" @if($client['sms'] == 1) checked @endif value="1"  type="checkbox">SMS Plugin</label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="fax" @if($client['fax'] == 1) checked @endif value="1" type="checkbox">Fax Plugin</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label>
                                                <input name="chat" @if($client['chat'] == 1) checked @endif value="1"  type="checkbox">Chat Plugin</label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label>
                                                <input name="webphone" @if($client['webphone'] == 1) checked @endif value="1"  type="checkbox">Webphone Plugin</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                               


                                       

                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" name="submit" value="Save"
                                        class="btn btn btn-primary waves-effect waves-light">Save
                                </button>
                            </div>
                        </form>
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div><!-- /.row -->

            <div CLASS="row"><!-- /.row -->


             <div class="col-md-12">

                   

                 
<div class="box box-default collapsed-box">
<div class="box-header with-border">
<h3 class="box-title" style="font-weight: bold;">SMS Provider</h3>
<div class="box-tools pull-right">
<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
</div>
</div>

<div class="box-body" style="display: none;">
<div class="row">
<div class="col-md-12">

<div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#timeline" data-toggle="tab" aria-expanded="true" style="font-weight:bold">DIDForSale</a></li>
                    <li class=""><a href="#settings" data-toggle="tab" aria-expanded="false" style="font-weight:bold">PLIVO</a></li>

                </ul>
                <div class="tab-content">

                   
                  
                    <div class="tab-pane active" id="timeline">

                        @if(!empty($sms_provider))
                            @foreach($sms_provider as $provider)
                            @if($provider->provider == 'didforsale')
                            @php
                            $didforsale_auth_id = $provider->auth_id;
                            $didforsale_api_key = $provider->api_key;

                            @endphp
                            @endif
                            

                            @if($provider->provider == 'plivo')
                            @php
                            $plivo_auth_id = $provider->auth_id;
                            $plivo_api_key = $provider->api_key;

                            @endphp
                            @endif
                            @endforeach
                        @endif

                       

                            <form method="post" role="form" name="" id=""
                                  >
                                @csrf

                            <input type="hidden" value="didforsale" name="provider">

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="inputPassword3" class="col-form-label">Auth ID</label>

                                    <input type="text" required="" class="form-control" value="@if(!empty($didforsale_auth_id)) {{$didforsale_auth_id}} @endif"  name="auth_id" id="inputEmail" autocomplete="off" placeholder="Enter Auth Id">
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="inputPassword3" class="col-form-label">Auth Token</label>

                                    <input type="text" required="" class="form-control" value="@if(!empty($didforsale_api_key)) {{$didforsale_api_key}} @endif" name="api_key" id="inputEmail" autocomplete="off" placeholder="Enter Key">
                                </div>

                            </div>

                        


                            <div class="card-footer">
                                <button type="submit" class="btn btn-info">Submit</button>
                            </div>
                        </form>
                        <!-- The timeline -->
                       
                    </div><!-- /.tab-pane -->

                    <div class="tab-pane" id="settings">
                        <form class="form-horizontal form-label-left"  method="post">
                            @csrf


                            <input type="hidden" value="plivo" name="provider">



                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="inputPassword3" class="col-form-label">Auth ID</label>

                                    <input type="text" required="" class="form-control" value="@if(!empty($plivo_auth_id)) {{$plivo_auth_id}} @endif" name="auth_id" id="inputEmail" autocomplete="off" placeholder="Enter Auth Id">
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="inputPassword3" class="col-form-label">Auth Token</label>

                                    <input type="text" required="" class="form-control" value="@if(!empty($plivo_api_key)) {{$plivo_api_key}} @endif" name="api_key" id="inputEmail" autocomplete="off" placeholder="Enter key">
                                </div>

                            </div>

                        


                            <div class="card-footer">
                                <button type="submit" class="btn btn-info">Submit</button>
                            </div>
                        </form>
                    </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
            </div><!-- /.nav-tabs-custom -->
</div>



</div>

</div>

</div>

                                <!--  -->
                            </div>
            
        </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <h3 style="margin-bottom: 20px;">Manual Subscription Assignment</h3>
                            <form method="post" role="form" name="manualSubscriptionForm" id="manualSubscriptionForm"
                                  action="{{url('client/manual-subscription')}}">
                                @csrf
                                @if($client["stage"] >= 5)
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="package">Subscription</label>
                                                <div class="input-daterange input-group col-sm-12 col-xs-12">
                                                    <select id="package" name="package"
                                                            class="form-control" required>
                                                        <option value="#">Select Subscription</option>
                                                        @foreach($arrPackages as $package)
                                                            <option value={{$package->key}}>{{$package->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Billing</label>
                                                <div class="input-daterange input-group col-sm-12 col-xs-12">
                                                    <select id="billing" name="billing" class="form-control">
                                                        <option value="#">Select Billing Period</option>
                                                        <option value="4">Yearly</option>
                                                        <option value="3">Half Yearly</option>
                                                        <option value="2">Quarterly</option>
                                                        <option value="1">Monthly</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Quantity</label>
                                                <div class="input-daterange input-group col-sm-12 col-xs-12">
                                                    <div class="number-of-users">
                                                        <span class="minus" data-page="cart"><span
                                                                class="glyphicon glyphicon-minus"></span></span>
                                                        <input type="text" class="quantity-field" name="quantity" value="0"/>
                                                        <span class="plus" data-page="cart"><span
                                                                class="glyphicon glyphicon-plus"></span></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="hidden" class="form-control" name="parent"
                                                       value="{{$client["id"]}}" required>
                                                <input type="submit" id="submitbtn" class="btn btn-primary"
                                                       value="Submit">
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div><h5>This section is available once client portal is ready</h5>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-bottom:40px;">
                    <div class="box">
                        <div class="box-body">
                            <h3 style="margin-bottom: 20px;">Credit Wallet</h3>
                            <form method="post" role="form" name="manualCreditWallet" id="manualCreditWallet"
                                  action="{{url('client/credit-wallet')}}">
                                @csrf
                                @if($client["stage"] >= 5)
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="amount">Amount</label>
                                                <input type="text"  class="form-control" name="amount" id="amount" required="" placeholder="Enter Amount">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="hidden" class="form-control" name="parent"
                                                       value="{{$client["id"]}}" required>
                                                <input type="submit" id="submitbtn" class="btn btn-primary"
                                                       value="Submit">
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                @else
                                    <div><h5>This section is available once client portal is ready</h5>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        .number-of-users input {
            width: 100px;
            text-align: center;
            font-size: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: inline-block;
            vertical-align: middle;
        }

        .number-of-users .minus, .number-of-users .plus {
            width: 32px;
            background: #f2f2f2;
            border-radius: 4px;
            padding: 5px;
            border: 1px solid #ddd;
            display: inline-block;
            vertical-align: middle;
            text-align: center;
            cursor: pointer;
        }

        .number-of-users .title {
            text-align: left;
            font-weight: 700;
            padding: 5px 0px;
        }
    </style>
    <script>
        $(document).ready(function () {
            $('.number-of-users .minus').click(function () {
                var input = $(this).parent().find('input');
                var count = parseInt(input.val()) - 1;

                count = count < 1 ? 1 : count;
                input.val(count);
                input.change();
            });

            $('.number-of-users .plus').click(function () {
                var input = $(this).parent().find('input');
                var page = $(this).data("page");
                input.val(parseInt(input.val()) + 1);
                input.change();
            });

            $('#package').change(function () {
                if ($(this).val() == "588703ba-e78a-430f-8872-bb088dc1abba") {
                    $("#billing").prop('disabled', true);
                } else {
                    $("#billing").prop('disabled', false);
                }
            });

            $('#submitbtn').click(function () {
                let validateSubscription = true;
                var subscriptionSelected = $("#package").val();
                var billingSelected = $("#billing").val();
                var quantitySelected = $(".number-of-users .quantity-field").val();

                if (subscriptionSelected == "#") {
                    toastr.error("Please select Subscription");
                    validateSubscription = false;
                }
                if (billingSelected == "#" && subscriptionSelected != "588703ba-e78a-430f-8872-bb088dc1abba") {
                    toastr.error("Please select Billing period");
                    validateSubscription = false;
                }
                if (quantitySelected == 0 && subscriptionSelected != "588703ba-e78a-430f-8872-bb088dc1abba") {
                    toastr.error("Please select Quantity");
                    validateSubscription = false;
                }

                if (validateSubscription == true) {
                    return true;
                } else {
                    return false;
                }
            });
        });
    </script>
@endsection
