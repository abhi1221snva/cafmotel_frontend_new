<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Smart Phone Platform For Businesses | Login</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('asset/css/AdminLTE.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('asset/plugins/iCheck/square/blue.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>

        .loading {
            position: absolute;
            color: White;
            top: 50%;
            left: 45%;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="javascript:"><b>Forgot </b>Password</a>
    </div><!-- /.login-logo -->
    <div class="row">
         @include("layouts.messaging")
    </div>
    <div class="login-box-body">
        

       
        <form method="POST" action="">
            @csrf

            <div class="form-group has-feedback showdiv">
                <input id="" type="password" class="form-control @error('email') is-invalid @enderror" name="password" placeholder="New Password" value="{{ old('password') }}" required  >
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>

            </div>
            <div class="form-group has-feedback showdiv">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" placeholder="Confirm Password" required >
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>

            </div>
            <div class="row">
                <div class="col-xs-8">
                    <!-- <div class="checkbox icheck">
                        <label>
                            <input type="checkbox"> Remember Me
                        </label>
                    </div> -->
                </div><!-- /.col -->

                <div class="col-xs-4 hidediv">
                    <button type="button" onclick="checkEmail();" class="btn btn-primary btn-block btn-flat">Submit</button>
                </div><!-- /.col -->
                <div class="col-xs-4 showdiv">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
                </div><!-- /.col -->
            </div>
        </form>

        <!--  <div class="social-auth-links text-center">
             <p>- OR -</p>
             <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a>
             <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a>
         </div><!-- /.social-auth-links -->

       
       
        <!--  <a href="register.html" class="text-center">Register a new membership</a> -->

    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="{{ asset('asset/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
<!-- Bootstrap 3.3.5 -->
<script src="{{ asset('asset/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('asset/plugins/iCheck/icheck.min.js') }}"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });

    $(".hidediv").hide();
    $("#login").hide();


    $("#forgot_pass").click(function () {

        $(".showdiv").hide();
        $(".hidediv").show();
        $("#login").show();
        $("#forgot_pass").hide();


    });


    $("#login").click(function () {

        $(".showdiv").show();
        $(".hidediv").hide();
        $("#login").hide();
        $("#forgot_pass").show();


    });

    // Removing sipML credentials & settings
    localStorage.removeItem("org.doubango.identity.display_name");
    localStorage.removeItem("org.doubango.identity.impi");
    localStorage.removeItem("org.doubango.identity.impu");
    localStorage.removeItem("org.doubango.identity.password");
    localStorage.removeItem("org.doubango.identity.realm");
    localStorage.removeItem("org.doubango.expert.websocket_server_url");
    localStorage.removeItem("org.doubango.expert.disable_video");
    localStorage.removeItem("org.doubango.expert.enable_rtcweb_breaker");
    localStorage.removeItem("org.doubango.expert.sip_outboundproxy_url");
    localStorage.removeItem("org.doubango.expert.ice_servers");
    localStorage.removeItem("org.doubango.expert.bandwidth");
    localStorage.removeItem("org.doubango.expert.video_size");
    localStorage.removeItem("org.doubango.expert.disable_early_ims");
    localStorage.removeItem("org.doubango.expert.disable_debug");
    localStorage.removeItem("org.doubango.expert.enable_media_caching");
    localStorage.removeItem("org.doubango.expert.disable_callbtn_options");

</script>

<script type="text/javascript">


    function checkEmail() {


        var email = document.getElementById("form_email").value;
        //alert(email);
        // var Get_Email          = $("#email").val();
        var R_Email_regex = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/; // reg ex email check


        if (email == "") {

            $('#email_status').html('Please Enter Email');

            $("#form_email").focus();
            validation_holder = 1;
            return false;
        } else {
            if (!R_Email_regex.test(email)) {

                $('#email_status').html('Invalid Email Id');

                $("#form_email").focus();
                validation_holder = 1;
                return false;
            }
        }

        if (email) {

            $(".loading").show();

            jQuery.ajax({
                type: 'GET',
                url: '{{url('send-email-to-forgot-password')}}',
                data: {
                    email: email,
                },
                success: function (response) {

                    /*alert(response[message]);*/
                    $(".loading").hide();

                    $("#errorMessage").show();
                    $('#errorMessage').html(response);
                    /*setTimeout(function () {
                        window.location.reload(1);
                    }, 5000);*/

                    if (response == "OK") {
                        return true;
                    } else {
                        return false;
                    }
                }
            });
        } else {
            $('#email_status').html("");
            return false;
        }
    }


</script>
</body>
</html>
