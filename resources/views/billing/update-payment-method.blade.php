@extends('layouts.app')
@section('title', 'Recharge')
@section('content')
<div class="content-wrapper">

      <section class="content-header">
                <h1>
                   <b>Edit Card</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Edit Card</li>
                </ol>
        </section>
   
    <section class="content">
        <form id="stripeForm" class="form-horizontal" method="post">
            @csrf
            <input name="payment_method_id" type="hidden" value="{{$pmDetails->id}}"/>
            <div class="row">
                <div class="col-xs-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title" id="add_edit_box_header">Address Info</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group m-b-10">
                                        <div class="col-md-6">
                                            <label>Full Name <i data-toggle="tooltip" data-placement="right" title="Type your full name" class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="text" maxlength="30" class="form-control "
                                                       name="full_name" value="{{isset($pmDetails->billing_details->name) ? $pmDetails->billing_details->name : ""}}"
                                                       id="full_name" placeholder="Full Name">
                                                <span id="message"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group m-b-10">
                                        <div class="col-md-6">
                                            <label>Address <i data-toggle="tooltip" data-placement="right" title="Type your address" class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="text" maxlength="100" class="form-control " name="line1"
                                                       value="{{isset($pmDetails->billing_details->address->line1) ? $pmDetails->billing_details->address->line1 : ""}}"
                                                       id="line1" placeholder="Address">
                                                <span id="message"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>City <i data-toggle="tooltip" data-placement="right" title="Type city" class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="text" maxlength="50" class="form-control " name="city"
                                                       value="{{isset($pmDetails->billing_details->address->city) ? $pmDetails->billing_details->address->city : ""}}" id="city"
                                                       placeholder="City">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group m-b-10">

                                         <div class="col-md-6">
                                            <label>Country <i data-toggle="tooltip" data-placement="right" title="Select Country from the drop down" class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control " name="country" id="country">
                                                    <option>Select Country</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label id="label_change">State </label> <i data-toggle="tooltip" data-placement="right" title="Select your state/provinces from the dropdown" class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;font-size:15px;">*</span>
                                            <div class="input-daterange input-group col-md-12">
                                                
                                                <span id="state-code"><input class="form-control" value="{{isset($pmDetails->billing_details->address->state) ? $pmDetails->billing_details->address->state : ""}}" type="text" id="state" name="state"></span>

                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>
                            </div>

                            <select style="display: none;" id="districtSel" size="1">
                            </select>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group m-b-10">
                                        <div class="col-md-6">
                                            <label>Zip Code <i data-toggle="tooltip" data-placement="right" title="Type Zip Code" class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="text" maxlength="20" class="form-control "
                                                       name="postal_code" value="{{isset($pmDetails->billing_details->address->postal_code) ? $pmDetails->billing_details->address->postal_code : ""}}"
                                                       id="postal_code" placeholder="Zip Code">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title" id="add_edit_box_header">Card Details </h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row showOnlyOnNewCard">
                                <div class="col-sm-12">
                                    <div class="form-group m-b-10">
                                        <div class="col-md-3">
                                            <label>Ex. Month <i data-toggle="tooltip" data-placement="right" title="Type expiry month of the card" class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select id="exp_month" name="exp_month"
                                                        class="form-control">
                                                    <option value="-">--</option>
                                                    @for($i=1;$i<=12;$i++)
                                                    <option value="{{sprintf("%02d", $i)}}" 
                                                    {{isset($pmDetails->card->exp_month) &&  $pmDetails->card->exp_month == $i ? "selected='selected'" : ""}}     
                                                    >{{sprintf("%02d", $i)}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Year <i data-toggle="tooltip" data-placement="right" title="Select Expiry year of the card" class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select id="exp_year" name="exp_year"
                                                        class="form-control">
                                                    <option value="-">----</option>
                                                    @for($i=date("Y");$i<=date("Y")+20;$i++)
                                                        <option value="{{$i}}"
                                                        {{isset($pmDetails->card->exp_year) &&  $pmDetails->card->exp_year == $i ? "selected='selected'" : ""}}        
                                                        >{{sprintf("%02d", $i)}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group m-b-10">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-success " >Save</button>
                                            <a href="{{url('payment-method-list')}}" type="button" class="btn btn-primary" >Cancel</a> 
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <section class="waiting-section" style="display: none">
        <div class="waiting-box">
            <div class="payment-processing">
                <img src="{{asset("asset/img/loader-30px.gif")}}"/>
                <p>Your Card is being Saved!</p>
            </div>
        </div>
    </section>
    </div>
    <style>
        .content-wrapper {
            position: relative;
        }
        section.waiting-section {
            text-align: center;
            background-color: #ffffffbd;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            z-index: 2;
        }
        section.waiting-section div.payment-processing, section.waiting-section div.payment-completed {
            padding-top: 20%;
        }
        section.waiting-section p {
            font-size: 16px;
        }
        section.waiting-section .payment-completed-text, section.waiting-section .redirect-timer {
            font-size: 26px;
        }
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
    </style>
    <script>
        $(document).ready(function () {
            $("#stripeForm").submit(function (e) {
                e.preventDefault();
                $("section.waiting-section").show();
                $.ajax({
                    type: 'POST',
                    url: '{{url('stripe/update-card')}}',
                    data: $('#stripeForm').serialize(),
                    success: function (response) {
                        console.log(response);
                        if (response.success == true || response.success == 'true') {
                            toastr.success(response.message);
                            $("section.waiting-section .payment-processing").hide();
                            window.location.href = "payment-method-list";
                        } else {
                            $("section.waiting-section").hide();
                            $.each(response.errors, function (index, value) {
                                toastr.error(value);
                            });
                        }
                    },
                    error: function (response) {
                        $("section.waiting-section").hide();
                        toastr.error("There is problem with updating card");
                        console.log(response);
                    }
                });
            });
        });
    </script>

    <script src="{{ asset('asset/js/country-states.js') }}"></script>



    <script>
        var payMethodCountry = "{{isset($pmDetails->billing_details->address->country) ? $pmDetails->billing_details->address->country : ""}}";
        var payMethodState = "{{isset($pmDetails->billing_details->address->state) ? $pmDetails->billing_details->address->state : ""}}";
(function () {
    //country code for selected option
    let country_list = country_and_states['country'];
    let states_list = country_and_states['states'];
    // country name drop down
    let option =  '';
    option += '<option>select country</option>';
    for(let country_code in country_list){
        // set selected option user country
        let selected = (country_code == payMethodCountry) ? ' selected' : '';
        option += '<option value="'+country_code+'"'+selected+'>'+country_list[country_code]+'</option>';
    }
    document.getElementById('country').innerHTML = option;
    // state name drop down
    let text_box = '<input type="text" class="form-control" class="input-text" id="state">';
    let state_code_append_id = document.getElementById("state-code");
    function create_states_dropdown() {
        let country_code = document.getElementById("country").value;
        let states = states_list[country_code];
        // invalid country code or no states add textbox
        if(!states){
            state_code_append_id.innerHTML = text_box;
            return;
        }
        let option = '';
        if (states.length > 0) {
            option = '<select class="form-control" name="state" id="state">\n';
            for (let i = 0; i < states.length; i++) {
                let selected = (states[i].name == payMethodState) ? ' selected' : '';
                option += '<option value="'+states[i].name+'"'+selected+'>'+states[i].name+'</option>';
            }
            option += '</select>';
        } else {
            // create input textbox if no states 
            option = text_box
        }
        state_code_append_id.innerHTML = option;
    }
    // country change event
    const country_select = document.getElementById("country");
    country_select.addEventListener('change', create_states_dropdown);
    create_states_dropdown();
})();

function deletePaymentMethod(id) {
    if(confirm("Are you sure want to update this card info?")) {
        $.ajax({
            type: 'POST',
            url: '{{url('stripe/save-card')}}',
            data: $('#stripeForm').serialize(),
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