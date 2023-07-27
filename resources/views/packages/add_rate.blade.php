@extends('layouts.app')
@section('title', 'Add Country Wise Rate')
@section('content')

<div class="content-wrapper">

       <section class="content-header">
                <h1>
                   <b>Add Rate</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> Packages</li>
                    <li class="active">Add Rate</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
              <a href="{{ url('super/packages') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show Packages</a>
           </div>
        </section>
   

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form method="post">
                        @csrf
                        <div class="row">
                        <div class="col-sm-12">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Rate Details</legend>
                                <div class="form-group m-b-10">
<div class="row">
                                    <div class="col-md-12">
                                            <label>Package List <i data-toggle="tooltip" data-placement="right" title="Select Package Plan" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                <select required class="form-control"  name="package_name" id="package_name">
                                                    <option value="">Select Package</option>
                                                @if (is_array($packages))
                                                @foreach($packages as $pack)
                                                <option value="{{$pack->key}}">{{$pack->name}} ({{$pack->key}})</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            </div>
                                    </div>
                                    </div>

                                    <div id="ivr_level_body">
                                            <div class="row">
                                    <div class="col-md-2">
                                            <label>Country </label>
                                            <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                <select  required class="form-control"  name="country_code[]" id="country_code">
                                                    <option value="">Select Country</option>

                                                @if (is_array($phone_country))
                                                @foreach($phone_country as $code)
                                                <option  value={{$code->phone_code}}>{{$code->country_name}} (+{{$code->phone_code}})
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                            </div>
                                    </div>
                                        
                                        <div class="col-md-2">
                                            <label>Call Rate Per Minute </label>
                                            <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                <input required type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="call_rate_per_minute[]" value="{{old('call_rate_per_minute')}}" id="call_rate_per_minute"  placeholder="Call Rate Per Minute">
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <label>Six By Six </label>
                                            <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                <input required type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="rate_six_by_six_sec[]" value="{{old('rate_six_by_six_sec')}}" id="rate_six_by_six_sec"  placeholder="Six By Six Sec">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label>Send/Received </label>
                                            <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="rate_per_sms[]" value="{{old('rate_per_sms')}}"
                                                id="rate_per_sms"  placeholder="Rate Per SMS">
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <label>Phone No </label>
                                            <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="rate_per_did[]" value="{{old('rate_per_did')}}" id="rate_per_did"  placeholder="Rate Per Phone No">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label>Per Fax Send/Received </label>
                                            <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="rate_per_fax[]" value="{{old('rate_per_fax')}}" id="rate_per_fax"  placeholder="Rate Per Fax">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label>Per Email </label>
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="rate_per_email[]" value="{{old('rate_per_email')}}" id="rate_per_email"  placeholder="Rate Per Email">
                                            </div>
                                        </div>
                                    </div>



                                     
                                    </div>


                                </div>

                        </fieldset>
                        </div>

                        <div class="form-group" style="float:right;padding: 0px 7px;">
                                    <tfoot>
                                        <tr>
                                            <td>

                                                   <button type="button" class="btn btn-success"onclick="addIvrRow();" title="Add More IVR Menu"><i class="fa fa-plus"></i></button>
                                                <button id="submit" class="btn btn-primary" type="submit">
                                                    <i class="fa fa-check-square-o fa-lg"></i>
                                                        Submit
                                                </button>
                                                &nbsp;

                                                <a type="button" class="btn btn-warning"  onclick="window.location.reload();"><i class="fa fa-refresh fa-lg"></i>
                                                        Reset
                                                </a>
                                                &nbsp;

                                                <a type="button" class="btn btn-danger" style="margin-right: 14px;" href="{{url('super/packages')}}">
                                                    <i class="fa fa-close fa-lg"></i>
                                                        Cancel
                                                </a>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </div>
                            </div>
                        </form>
                </div>
            </div>

        </div>

        </section>
    </div>

    <style>

    fieldset.scheduler-border
    {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 15px 6px 1.5em 6px !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
        box-shadow:  0px 0px 0px 0px #000;
    }

    legend.scheduler-border
    {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        /*border-bottom:none;*/
    }

    </style>

    <script>
           function addIvrRow() {

           


        var html ='<div class="row"><div class="col-md-2"><label>Country</label><div class="input-daterange input-group col-md-12" style="margin-bottom:10px"><select required class="form-control" name="country_code[]" id="country_code"><option value="">Select Country</option><?php if (is_array($phone_country))
        foreach($phone_country as $code){ ?><option  value={{$code->phone_code}}>{{$code->country_name}} (+{{$code->phone_code}})</option><?php  }?></select></div></div><div class="col-md-2"><label>Call Rate Per Minute</label><div class="input-daterange input-group col-md-12" style="margin-bottom:10px"><input required type="text" onkeypress="return isNumberKey($(this))" class="form-control" name="call_rate_per_minute[]" value="" id="call_rate_per_minute" placeholder="Call Rate Per Minute"></div></div><div class="col-md-1"><label>Six By Six</label><div class="input-daterange input-group col-md-12" style="margin-bottom:10px"><input required type="text" onkeypress="return isNumberKey($(this))" class="form-control" name="rate_six_by_six_sec[]" value="" id="rate_six_by_six_sec" placeholder="Six By Six Sec"></div></div><div class="col-md-2"><label>Send/Received</label><div class="input-daterange input-group col-md-12" style="margin-bottom:10px"><input type="text" onkeypress="return isNumberKey($(this))" class="form-control" name="rate_per_sms[]" value="" id="rate_per_sms" placeholder="Rate Per SMS"></div></div><div class="col-md-1"><label>Phone No</label><div class="input-daterange input-group col-md-12" style="margin-bottom:10px"><input type="text" onkeypress="return isNumberKey($(this))" class="form-control" name="rate_per_did[]" value="" id="rate_per_did" placeholder="Rate Per Phone No"></div></div><div class="col-md-2"><label>Per Fax Send/Received</label><div class="input-daterange input-group col-md-12" style="margin-bottom:10px"><input type="text" onkeypress="return isNumberKey($(this))" class="form-control" name="rate_per_fax[]" value="" id="rate_per_fax" placeholder="Rate Per Fax"></div></div><div class="col-md-2"><label>Per Email</label><div class="input-daterange input-group col-md-12"><input type="text" onkeypress="return isNumberKey($(this))" class="form-control" name="rate_per_email[]" value="" id="rate_per_email" placeholder="Rate Per Email"></div></div></div>';
        $('#ivr_level_body').append(html);
    }
    </script>
@endsection
