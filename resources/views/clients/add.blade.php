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
                    <b>Add New Client</b>
                    
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> System Configuration</li>
                    <li class="active">Add New Client</li>
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
                            <form method="POST" role="form" name="addForm" id="addForm" action="{{ url('client') }}">
                                @csrf
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company_name">Name  <i data-toggle="tooltip" data-placement="right" title="Type the client's name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                <input type="text" class="form-control" name="company_name" id="company_name" value="{{ old('company_name') }}" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="stage">Status  <i data-toggle="tooltip" data-placement="right" title="It shows the status of the client setup" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                <div class="select2-container select2-container--default status-label-container">
                                                    <span class="label label-primary status-label"><i class="icon fa fa-plus"></i> NEW</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address_1">Building/Street  <i data-toggle="tooltip" data-placement="right" title="Type the client's address" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                <input type="text" class="form-control" name="address_1" id="address_1" value="{{ old("address_1") }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address_2">Area/State/Zipcode  <i data-toggle="tooltip" data-placement="right" title="Type area,zipcode etc" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                <input type="text" class="form-control" name="address_2" id="address_2" value="{{ old("address_2") }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="trunk">Trunk  <i data-toggle="tooltip" data-placement="right" title="Type the Voice Trunk" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                <input type="text" class="form-control" name="trunk" id="trunk" value="{{ old("trunk") }}" maxlength=30 />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?php $oldServer = empty(old("asterisk_servers"))?[]:old("asterisk_servers") ?>
                                                <label for="asterisk_servers">Server Allotted  <i data-toggle="tooltip" data-placement="right" title="Select multiple server from the drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                <select class="form-control select2" multiple name="asterisk_servers[]" id="asterisk_servers" data-placeholder="Asterisk Servers" style="width: 100%;">
                                                    @if(!empty($asteriskServers))
                                                        @foreach($asteriskServers as $server)
                                                            <option value={{$server["id"]}} @if(in_array($server["id"], $oldServer)) {{ "selected" }} @endif >{{$server["host"]}} - {{$server["location"]}} - {{$server["trunk"]}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-md-6">
                                                <label>Enable 2FA <i data-toggle="tooltip" data-placement="right" title="Select Yes/No For Enable 2FA" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                <div class="input-daterange input-group col-md-12">
                                                    <select class="form-control" name="enable_2fa" id="enable_2fa" >
                                                        <option value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                    </div>
                                    <?php /*?>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="trunk">SMS Plateform  <i data-toggle="tooltip" data-placement="right" title="Select Sms Plateform" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                <select class="form-control" required name="sms_plateform" id="sms_plateform">
                                                    <option value="">Select SMS Plateform</option>

                                                    <option value="didforsale">DID For Sale</option>
                                                    <option value="plivo">Plivo</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <?php */ ?>


                                </div><!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" name="submit" value="Save" class="btn btn btn-primary waves-effect waves-light">Save</button>
                                </div>
                            </form>

                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
