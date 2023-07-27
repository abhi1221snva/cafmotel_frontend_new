@extends('layouts.app')
@section('title', 'Add Packages')
@section('content')

<div class="content-wrapper">


      <section class="content-header">
                <h1>
                    <b>Add Packages</b>
            
                </h1>
                <ol class="breadcrumb">
                     <li><a href="{{('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> System Configuration</li>
                     
                    <li class="active">Add Packages</li>
                </ol>
        </section>
    <section class="content-header">
         <div class="text-right mt-5 mb-3"> 
           <a href="{{ url('/super/packages') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show Packages</a>
        </div>
       
    </section>

   

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form method="post">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="applicable_for" id="applicable_for" value="1">
                            <div class="col-sm-7">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Package Details</legend>
                                    <div class="form-group m-b-10">
                                        <div class="col-md-12">
                                            <label>Name <i data-toggle="tooltip" data-placement="right" title="Type package name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                <input type="text" class="form-control" name="name"
                                                id="name"   placeholder="Enter Name" value="{{old('name')}}">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label>Description <i data-toggle="tooltip" data-placement="right" title="Type package description" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                            <textarea type="text" class="form-control" rows="8" name="description"  id="description"  placeholder="Enter Description">{{old('description')}}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <label>Is Active <i data-toggle="tooltip" data-placement="right" title="select yes/no " class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12" >
                                            <select class="form-control"  id="is_active" name="is_active" >
                                                <option @if(old('is_active') == '1') selected @endif value="1">Yes</option>
                                                <option @if(old('is_active') == '0') selected @endif value="0">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-7">
                                        <label>Display Order <i data-toggle="tooltip" data-placement="right" title="Type order for package" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <input type="text" maxlength="2" onkeypress="return isNumberKey($(this));" class="form-control" name="display_order" value="{{old('display_order')}}" id="display_order"  placeholder="Display order">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            </div>

                            <div class="col-sm-5">
                                <div class="col-sm-12">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Module</legend>
                                        <div class="form-group m-b-10">
                                            <div class="col-md-8">
                                                <label>Show On <i data-toggle="tooltip" data-placement="right" title="Select website/portal " class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                    <select class="form-control select2" multiple="multiple" id="show_on" name="show_on[]">
                                                        <option @if(old('show_on.0') == 'website') selected @endif value="website">Website</option>
                                                        <option @if(old('show_on.1') == 'portal') selected @endif value="portal">Portal</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label>Currency <i data-toggle="tooltip" data-placement="right" title="select currency type" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                <div class="input-daterange input-group col-md-12">
                                                    <select class="form-control" id="currency_code" name="currency_code">
                                                        <option @if(old('currency_code') == 'USD') selected @endif  value="USD">USD</option>
                                                        <option @if(old('currency_code') == 'CAD') selected @endif value="CAD">CAD</option>
                                                        <option @if(old('currency_code') == 'INR') selected @endif value="INR">INR</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12" >
                                                <label>Modules <i data-toggle="tooltip" data-placement="right" title="Selecct modules for packages" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                    <select name="modules[]" id="modules" class="form-control select2" multiple="multiple">
                                                        @foreach($modules as $key => $mod)
                                                        <option {{ (collect(old('modules'))->contains($mod->key)) ? 'selected':'' }} value="{{$mod->key}}">{{$mod->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

                                <div class="col-sm-12" style="margin-top:-35px;">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Package Price </legend>
                                        <div class="form-group m-b-10">
                                            <div class="col-md-6">
                                                <label>Monthly <i data-toggle="tooltip" data-placement="right" title="Type monthly package price" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                    <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="base_rate_monthly_billed" value="{{old('base_rate_monthly_billed')}}" id="base_rate_monthly_billed"  placeholder="Monthly">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Quarterly <i data-toggle="tooltip" data-placement="right" title="Type quarterly package price" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                    <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="base_rate_quarterly_billed" value="{{old('base_rate_quarterly_billed')}}" id="base_rate_quarterly_billed"  placeholder="Quarterly">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Half Yearly <i data-toggle="tooltip" data-placement="right" title="Type half yearly package price" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                    <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="base_rate_half_yearly_billed" value="{{old('base_rate_half_yearly_billed')}}" id="base_rate_half_yearly_billed"  placeholder="Half Yearly">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Yearly <i data-toggle="tooltip" data-placement="right" title="Type yearly package price" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                    <div class="input-daterange input-group col-md-12">
                                                        <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="base_rate_yearly_billed" value="{{old('base_rate_yearly_billed')}}" id="base_rate_yearly_billed"
                                                        placeholder="Yearly">
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Rate </legend>
                                    <div class="form-group m-b-10">
                                        <div class="col-md-12">
                                            <label>Call Rate Per Minute <i data-toggle="tooltip" data-placement="right" title="Type call rate per minute" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="call_rate_per_minute" value="{{old('call_rate_per_minute')}}" id="call_rate_per_minute"  placeholder="Rate Per SMS">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label>Per SMS Send/Received <i data-toggle="tooltip" data-placement="right" title="Type rate per sms send /received" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="rate_per_sms" value="{{old('rate_per_sms')}}"
                                                id="rate_per_sms"  placeholder="Rate Per SMS">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label>Phone No <i data-toggle="tooltip" data-placement="right" title="Type rate per phone number" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="rate_per_did" value="{{old('rate_per_did')}}" id="rate_per_did"  placeholder="Rate Per Phone No">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label>Per Fax Send/Received <i data-toggle="tooltip" data-placement="right" title="Type Per fax send /received" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="rate_per_fax" value="{{old('rate_per_fax')}}" id="rate_per_fax"  placeholder="Rate Per Fax">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label>Per Email <i data-toggle="tooltip" data-placement="right" title="Type rate per email" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="rate_per_email" value="{{old('rate_per_email')}}" id="rate_per_email"  placeholder="Rate Per Email">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-sm-3">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Free / Month</legend>
                                    <div class="form-group m-b-10">
                                        <div class="col-md-12">
                                            <label>Free Call Minute <i data-toggle="tooltip" data-placement="right" title="Type free call per minute" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="free_call_minute_monthly" value="{{old('free_call_minute_monthly')}}" id="free_call_minute_monthly"  placeholder="Free Call">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label>Free SMS <i data-toggle="tooltip" data-placement="right" title="Type free sms" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="free_sms_monthly" value="{{old('free_sms_monthly')}}"
                                                id="free_sms_monthly"  placeholder="Free SMS">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label>Free FAX <i data-toggle="tooltip" data-placement="right" title="Type free fax" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="free_fax_monthly" value="{{old('free_fax_monthly')}}" id="free_fax_monthly"  placeholder="Free Fax">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label>Free Emails <i data-toggle="tooltip" data-placement="right" title="Type free emails" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="free_emails_monthly" value="{{old('free_emails_monthly')}}" id="free_emails_monthly"  placeholder="Free Emails">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label>Free Did <i data-toggle="tooltip" data-placement="right" title="Type free did" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="free_did_monthly" value="{{old('free_did_monthly')}}" id="free_did_monthly"  placeholder="Free DID">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group" style="float:right;padding: 0px 7px;">
                                    <tfoot>
                                        <tr>
                                            <td>
                                                <button id="submit" class="btn btn-primary" type="submit">
                                                    <i class="fa fa-check-square-o fa-lg"></i>
                                                        Submit
                                                </button>
                                                &nbsp;

                                                <a type="button" class="btn btn-warning"  onclick="window.location.reload();"><i class="fa fa-refresh fa-lg"></i>
                                                        Reset
                                                </a>
                                                &nbsp;

                                                <a type="button" class="btn btn-danger" style="margin-right: 14px;" href="{{url('/super/packages')}}">
                                                    <i class="fa fa-close fa-lg"></i>
                                                        Cancel
                                                </a>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </div>
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

@endsection

@push('scripts')
    <script src="{{ asset("asset/plugins/ckeditor/ckeditor.js") }}"></script>
    <script language="javascript">
        $(function () {
            CKEDITOR.config.autoParagraph = false;
            CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
            CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_P;
            CKEDITOR.replace('description', {
                enterMode: CKEDITOR.ENTER_BR,
                filebrowserUploadUrl: "{{route('start-dialing.upload', ['_token' => csrf_token() ])}}",
                filebrowserUploadMethod: 'form'
            });

            CKEDITOR.instances['description'].on('contentDom', function () {
                this.document.on('click', function (event) {
                    $('#setBoxValue').html('');
                });
            });
        });
    </script>
@endpush
