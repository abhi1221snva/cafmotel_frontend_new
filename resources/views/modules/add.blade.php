@extends('layouts.app')
@section('title', 'Add Module')
@section('content')

<div class="content-wrapper">

       <section class="content-header">
                <h1>
                   <b>Add Module</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> System Configuration</li>
                    <li class="active">Add Module</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
              <a href="{{ url('super/modules') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show Modules</a>
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
                                <legend class="scheduler-border">Module Details</legend>
                                <div class="form-group m-b-10">
                                    <div class="col-md-3">
                                        <label>Name <i data-toggle="tooltip" data-placement="right" title="Type module name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                            <input type="text" class="form-control" name="name"
                                        id="name" value="{{old('name')}}"  placeholder="Enter Name">
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <label>Components <i data-toggle="tooltip" data-placement="right" title="Type module component" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                <select name="components[]" id="components" class="form-control select2" multiple="multiple">
                                                    @foreach($components as $key => $comp)
                                                    <option {{ (collect(old('components'))->contains($comp->key)) ? 'selected':'' }} value="{{$comp->key}}">{{$comp->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                </div>

                                <div class="col-md-2">
                                    <label>Attribute <i data-toggle="tooltip" data-placement="right" title="Type module attributes" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12" style="margin-bottom: 10px;">
                                                <input type="text" class="form-control" name="attributes[]"
                                        id="attributes" value=" "  placeholder="Enter Attributes">
                                        <div id="span" ></div>


                                            </div>
                                </div>




                                <div class="col-md-2">
                                    <label>Is Active <i data-toggle="tooltip" data-placement="right" title="Select yes/no" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <div class="input-daterange input-group col-md-12" >
                                        <select class="form-control"  id="is_active" name="is_active" >
                                                <option @if(old('is_active') == '1') selected @endif value="1">Yes</option>
                                                <option @if(old('is_active') == '0') selected @endif value="0">No</option>
                                            </select>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <label>Order <i data-toggle="tooltip" data-placement="right" title="Type order for modules" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <div class="input-daterange input-group col-md-12">
                                            <input type="text" maxlength="2" onkeypress="return isNumberKey($(this));" class="form-control" name="display_order" value="{{old('display_order')}}" id="display_order"  placeholder="Order">
                                        </div>
                                </div>
                            </div>
                        </fieldset>
                        </div>

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

                                                <a type="button" class="btn btn-danger" style="margin-right: 14px;" href="{{url('super/modules')}}">
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
@endsection
