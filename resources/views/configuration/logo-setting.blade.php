@extends('layouts.app')
@section('content')
    <?php
    use App\Http\Controllers\InheritApiController;
    $userdetails = InheritApiController::headerUserDetails();
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper dialer">
        <!-- Content Header (Page header) -->
        <section class="content-header">
                <h1>
                   <b>Notification Setting</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Configuration</li>
                    <li class="active">Notification Setting</li>
                </ol>
        </section>
       
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12" >

                    <!-- Logo -->
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <i class="fa fa-building"></i>
                            <h3 class="box-title">{{ $userdetails->data->company_name }} Logo</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="container_css">
                                <?php
                if(!empty($userdetails->data->logo)){
                if (file_exists(public_path() . '/logo/' . $userdetails->data->logo)) { ?>
                                    <img src="{{ asset('logo') }}/{{$userdetails->data->logo}}" alt="Avatar" class="profile-user-img img-responsive  image" style="border:none;">
                                <?php }else
                                { ?>
                                    <img src="{{ asset('logo//logo_white.png') }}" alt="Avatar" class="profile-user-img img-responsive image" style="border:none;">
                                 <?php } 
                             } ?>
                                

                                <div class="overlay">
                                    <form id="form" action="{{ route('logo.upload.post') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="icon"><i class="fa fa-camera upload-button"></i></div>
                                        <input type="hidden" id="old_logo" name="old_logo" value="{{$userdetails->data->logo}}"/>
                                        <input id="html_btn" name="image" type="file" /><br>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box Logo -->

                    <!-- Email Setting -->
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <i class="fa fa-building"></i>
                            <h3 class="box-title">System Email Setting</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            @if($system_setting->success)
                                @if( count($system_setting->data) > 0)
                                    <dl class="dl-horizontal">
                                        <dt>Type</dt>
                                        <dd>{{ $system_setting->data[0]->sender_type }}</dd>
                                        <dt>Driver Name</dt>
                                        <dd>{{ $system_setting->data[0]->mail_driver }}</dd>
                                        <dt>Host Name</dt>
                                        <dd>{{ $system_setting->data[0]->mail_host }}</dd>
                                        <dt>Username</dt>
                                        <dd>{{ $system_setting->data[0]->mail_username }}</dd>
                                        <dt>Sender Email</dt>
                                        <dd>{{ $system_setting->data[0]->from_email }}</dd>
                                        <dt>Sender Name</dt>
                                        <dd>{{ $system_setting->data[0]->from_name }}</dd>
                                    </dl>
                                    <p>Update <a href="/smtp/{{ $system_setting->data[0]->id }}" style="color: #00a7d0">System Email Setting</a></p>
                                @else
                                    <div class="callout callout-warning">
                                        <h4>No email setting present with type <u>system</u>!</h4>
                                        <p>Define one by going in <a href="/smtps" style="color: #00a7d0">SMTP settings</a></p>
                                    </div>
                                @endif
                            @else
                                <div class="callout callout-danger">
                                    <h4>Failed to get system email setting!</h4>
                                    <p>{{ $system_setting->message }}</p>
                                </div>
                            @endif
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box Logo -->

                    <!-- Notification --->
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <i class="fa fa-envelope"></i>
                            <h3 class="box-title">Notifications</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table">
                                <form method="post">
                                    @csrf
                                    <?php
                                    foreach($notifications as $notification)
                                    {
                                    if ($notification["active"]) {
                                        $status = 'checked';
                                        $value = 1;
                                    } else {
                                        $status = '';
                                        $value = 0;
                                    }

                                    if ($notification["active_sms"]) {
                                        $active_sms = 'checked';
                                        $value = 1;
                                    } else {
                                        $active_sms = '';
                                        $value = 0;
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <h4>{{$notification["name"]}}</h4>
                                            <input type="hidden" name="notification_id[{{$notification["id"]}}]" value="{{$notification["id"]}}">
                                        </td>
                                        <td>
                                            
                                                <input type="checkbox" {{$status}} value="1" name="active[{{$notification["id"]}}]"> Email

                                                 {{-- @if($notification["id"] != 'send_fax_email' && $notification["id"] != 'daily_call_report') --}}
                                                &nbsp;&nbsp;<input type="checkbox" {{$active_sms}} value="1" name="active_sms[{{$notification["id"]}}]"> SMS
                                                {{--@endif --}}
                                                
                                            
                                        </td>
                                        <td>
                                        @if($notification["id"] === 'send_fax_email' || ($notification["id"] === 'send_callback'))
                                        <input type="hidden" name="subscribers[{{$notification["id"]}}][]" value="0" />
                                        @else
                                        
                        <select class="select2" multiple="multiple" name="subscribers[{{$notification["id"]}}][]" placeholder="Select Emails">
                            @foreach($extension_list as $key => $extensions)
                                @if(($extensions->user_level != '9') || ($extensions->extension == request()->session()->get('extension')))
                                    <option @if(!empty($notification["subscribers"])) @if(in_array($extensions->id, $notification["subscribers"]))  selected @endif @endif  value={{$extensions->id}}>{{$extensions->first_name}} {{$extensions->last_name}}-{{$extensions->email}}-(+{{$extensions->country_code}} {{$extensions->mobile}})</option>
                                @endif
                            @endforeach
                        </select>
                                        
                                        @endif
                                        </td>

                                    </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><input type="submit" class="btn btn-info" value="Submit"/></td>
                                    </tr>
                                </form>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box Notification -->
                </div>

            </div><!-- /.row -->
            <div style="clear: both; margin-bottom: 50px" class="row"></div>

        </section><!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
@endsection
@push('styles')
    <style>
        #html_btn {
            display: none;
        }

        .container_css {
            position: relative;
            width: 100%;
            max-width: 358px;
            background-color: grey;
            min-height: 100px;
        }

        .image {
            display: block;
            width: 100%;
            height: auto;
        }

        .overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100%;
            width: 100%;
            opacity: 0;
            transition: .3s ease;

        }

        .container_css:hover .overlay {
            opacity: 1;
        }

        .icon {
            color: black;
            font-size: 100px;
            position: absolute;
            top: 44%;
            left: 50%;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            text-align: center;
            cursor: pointer;
        }

        .fa-camera:hover {
            color: black;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endpush
@push('scripts')
    <script type="text/javascript">
        document.getElementById("html_btn").onchange = function () {
            document.getElementById("form").submit();
        }
        $('.icon').on("click", function () {
            $('#html_btn').click();
        });
    </script>
@endpush
