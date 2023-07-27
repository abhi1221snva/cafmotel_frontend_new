<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Portal : </title>
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
  left: 45%;}
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="javascript:"><b>Admin</b>LTE</a>
    </div><!-- /.login-logo -->

   <div class="col-md-12">

            @if(session()->has('message'))

            <div class="alert alert-danger alert-block">

                 <button type="button" class="close" data-dismiss="alert">×</button>

                {{ session()->get('message') }}
            </div>
  
@endif

                                    @if ($message = Session::get('success'))
                       

                                

        <div class="alert alert-success alert-block">

            <button type="button" class="close" data-dismiss="alert">×</button>

                <strong>{{ $message }}</strong>

        </div>

        <!-- <img src="images/{{ Session::get('image') }}"> -->

        @endif


        @if (count($errors) > 0)

            <div class="alert alert-danger">

                <strong>Whoops!</strong> There were some problems with your input.

                <ul>

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

    </div>


        @endif


         <div class="alert alert-success alert-block" id="errorMessage" style="display: none;">

            <button type="button" class="close" data-dismiss="alert">×</button>

                <strong>{{ $message }}</strong>

        </div>
    <div class="login-box-body">
        <p class="login-box-msg">Reset Password</p>


       
           


                
               
         <form method="POST" action="{{url('/')}}">


            @csrf

            <input type="hidden" id="url_email" value="{{$email}}" />
                <input type="hidden" id="url_tokenId" value="{{$tokenId}}" />


              <p class="loading" style="display: none;">
    <img style="width:50px;" src="{{ asset('asset/img/loader.gif') }}" />
</p>

            <div class="form-group has-feedback ">
                <input id="password" type="text" class="form-control" name="password"  placeholder="Enter Password" required autocomplete="off" autofocus>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                <span id ="error_password"></span>
                
            </div>
            <div class="form-group has-feedback ">
                <input id="c_password" type="password" class="form-control" name="c_password" placeholder="Confirm Password" required autocomplete="off">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                <span id ="error_c_password"></span>

                
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <!-- <div class="checkbox icheck">
                        <label>
                            <input type="checkbox"> Remember Me
                        </label>
                    </div> -->
                </div><!-- /.col -->

                <div class="col-xs-4 ">
                    <button type="button" onclick="resetPassword();" class="btn btn-primary btn-block btn-flat">Submit</button>
                </div><!-- /.col -->
                
            </div>
        </form>

       <!--  <div class="social-auth-links text-center">
            <p>- OR -</p>
            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a>
            <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a>
        </div><!-- /.social-auth-links --> 

        <a href="/" >Login</a><br>
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
  



  

   
</script>

<script type="text/javascript">



function resetPassword()
{



 var email   = $("#url_email").val();
 var tokenId =$("#url_tokenId").val();
 var password =$("#password").val();
 var c_password =$("#c_password").val();
 



 if(password == ""){
  $("#error_password").html("Enter Password");
  $("#error_password").focus();

  return false;
 }

 else if(c_password == ""){

  $("#error_c_password").html("Enter Confirm Password");
  $("#error_c_password").focus();

  return false;

 }

 else if(password != c_password){

  $("#error_c_password").html("Confirm Password Not matched");
  $("#error_password").focus();
  return false;


 }


 
     
    
 if(email && tokenId)
 {

    $(".loading").show();

  jQuery.ajax({
   type:'GET',
  url: '{{url('resetPassword')}}',
  data: {
   email:email,tokenId:tokenId,password:password
  },
  success: function (response) {

    /*alert(response[message]);*/
    $(".loading").hide();

    $("#errorMessage").show();
   $('#errorMessage' ).html(response);
   setTimeout(function(){ window.location.href='/';}, 5000);

   if(response=="OK")   
   {
    return true;    
   }
   else
   {
    return false;   
   }
  }
  });
 }
 else
 {
  $( '#email_status' ).html("");
  return false;
 }
}

</script>
</body>
</html>
