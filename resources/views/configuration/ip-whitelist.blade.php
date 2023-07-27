@extends('layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

          <section class="content-header">
                <h1>
                   <b> Whitelist an IP</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Configuration</li>
                    <li class="active"> Whitelist an IP</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                 <a href="{{ url('/ip-setting') }}"  type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> IP Approval List</a>
           </div>
        </section>
       
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <form method="post" name="userform" id="userform" action="">
                                @csrf
                                <div class="box-body">
                                    <div class="modal-body">
                                        <div class="form-group m-b-10">

                                            <div class="col-md-4">
                                                <!-- IP mask -->
                                                <div class="form-group">
                                                    <label>IP Address: <i data-toggle="tooltip" data-placement="right" title="Type Ip address" class="fa fa-info-circle" aria-hidden="true"></i></label>

                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-laptop"></i>
                                                        </div>
                                                        <input type="text" name="whitelistIp" class="form-control" data-inputmask="'alias': 'ip'" data-mask  value="{{ old("whitelistIp") }}" />
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                                <!-- /.form group -->
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <?php $oldServer = empty(old("asteriskServers"))?[]:old("asteriskServers") ?>
                                                    <label for="asteriskServers">Servers <i data-toggle="tooltip" data-placement="right" title="You can select multiple options from the drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                    <select class="form-control select2" multiple name="asteriskServers[]" id="asteriskServers" data-placeholder="Asterisk Servers" style="width: 100%;">
                                                        @if(!empty($asteriskServers))
                                                            @foreach($asteriskServers as $server)
                                                                <option value={{$server["id"]}} @if(in_array($server["id"], $oldServer)) {{ "selected" }} @endif >{{$server["host"]}} - {{$server["location"]}} - {{$server["trunk"]}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="clear: both"></div>
                                    <div class="modal-footer">
                                        <button type="submit" name="submit" value="add" class="btn btn btn-primary waves-effect waves-light">Whitelist</button>
                                    </div>
                                </div><!-- /.box-body -->
                            </form>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')
    <!-- InputMask -->
    <script src="{{ asset('asset/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('asset/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('asset/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-mask]').inputmask();
        });
    </script>
@endpush

