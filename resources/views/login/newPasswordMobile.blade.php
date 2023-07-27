<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Smart Phone Platform For Businesses | Login</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1"><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Smart Phone Platform For Businesses | Login</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Vendors Style-->

	<link rel="stylesheet" href="{{asset('assets/css/vendors_css.css')}}">
	  
	<!-- Style-->  
	<link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/skin_color.css')}}">	
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <style>

.loading {
    position: absolute;
    color: White;
    top: 50%;
    left: 45%;
}
</style>
</head>
<body class="hold-transition theme-primary bg-img" style="background-image: url('{{ asset('assets/img/auth-bg/bg-2.jpg') }}');">
<div class="container h-p70 ">
		<div class="row align-items-center justify-content-md-center h-p100">
        @include("layouts.messaging")
        <div id="errorMessage"></div>
			<div class="col-12">
				<div class="row justify-content-center g-0">
                <div class="col-lg-5 col-md-5 col-12">
					
						<div class="bg-white rounded10 shadow-lg">
							<div class="content-top-agile p-20 pb-0">
								<h3 class="mb-0 text-primary">Recover Password</h3>								
							</div>
							<div class="p-40">
         
                        <div class="form-group hidediv">

                            <p class="loading" style="display: none;">
                                <img style="width:50px;" src="{{ asset('asset/img/loader.gif') }}"/>
                            </p>
                         </div>    
                         <form method="POST" action="{{url('resetPasswordUserMobile')}}">
                         @csrf
									<div class="form-group hidediv">
										<div class="input-group mb-3">
											<span class="input-group-text bg-transparent"><i class="fas fa-lock"></i></span>
											<input type="password" class="form-control ps-15 bg-transparent" id="newpassword" name="password" placeholder="New Password" required>

										</div>                

									</div>
                                    <div class="form-group hidediv">
										<div class="input-group mb-3">
											<span class="input-group-text bg-transparent"><i class="fas fa-lock"></i></span>
											<input type="password" class="form-control ps-15 bg-transparent" id="confirmpassword" name="password" placeholder="Confirm Password" required>

										</div>               

									</div>
									  <div class="row showdiv">
										<div class="col-12 text-center">
										  <button type="submit" class="btn btn-info margin-top-10">Reset</button>
										</div>
										<!-- /.col -->
									  </div>
								</form>	
                              
                            </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	


<script src="{{asset('assets/js/vendors.min.js')}}"></script>
	<script src="{{asset('assets/js/pages/chat-popup.js')}}"></script>
    <script src="{{asset('assets/icons/feather-icons/feather.min.js')}}"></script>	
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('form').submit(function(event) {
                var newPassword = $('#newpassword').val();
                var confirmPassword = $('#confirmpassword').val();

                if (newPassword !== confirmPassword) {
                    event.preventDefault();
                    $('#errorMessage').html('<div class="alert alert-danger">New Password and Confirm Password must match</div>');
                }
            });
            $('#newpassword, #confirmpassword').on('input', function() {
            $('#errorMessage').empty();
        });
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

<script>
    function disableSubmitButton() {
        document.getElementById("submitButton").disabled = true;
    }
</script>
</body>
</html>

	<!-- Vendors Style-->

	<link rel="stylesheet" href="{{asset('assets/css/vendors_css.css')}}">
	  
	<!-- Style-->  
	<link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/skin_color.css')}}">	
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <style>

.loading {
    position: absolute;
    color: White;
    top: 50%;
    left: 45%;
}
</style>
</head>
<body class="hold-transition theme-primary bg-img" style="background-image: url(assets/img/auth-bg/bg-2.jpg);">
<div class="container h-p70 ">
		<div class="row align-items-center justify-content-md-center h-p100">
        @include("layouts.messaging")
        <div id="errorMessage"></div>
			<div class="col-12">
				<div class="row justify-content-center g-0">
                <div class="col-lg-5 col-md-5 col-12">
					
						<div class="bg-white rounded10 shadow-lg">
							<div class="content-top-agile p-20 pb-0">
								<h3 class="mb-0 text-primary">Recover Password</h3>								
							</div>
							<div class="p-40">
         
                        <div class="form-group hidediv">

                            <p class="loading" style="display: none;">
                                <img style="width:50px;" src="{{ asset('asset/img/loader.gif') }}"/>
                            </p>
                         </div>    
                         <form method="POST" action="{{url('resetPasswordUserMobile')}}">
                         @csrf
									<div class="form-group hidediv">
										<div class="input-group mb-3">
											<span class="input-group-text bg-transparent"><i class="fas fa-lock"></i></span>
											<input type="password" class="form-control ps-15 bg-transparent" id="newpassword" name="password" placeholder="New Password" required>

										</div>                

									</div>
                                    <div class="form-group hidediv">
										<div class="input-group mb-3">
											<span class="input-group-text bg-transparent"><i class="fas fa-lock"></i></span>
											<input type="password" class="form-control ps-15 bg-transparent" id="confirmpassword" name="password" placeholder="Confirm Password" required>

										</div>               

									</div>
									  <div class="row showdiv">
										<div class="col-12 text-center">
										  <button type="submit" class="btn btn-info margin-top-10">Reset</button>
										</div>
										<!-- /.col -->
									  </div>
								</form>	
                              
                            </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	


<script src="{{asset('assets/js/vendors.min.js')}}"></script>
	<script src="{{asset('assets/js/pages/chat-popup.js')}}"></script>
    <script src="{{asset('assets/icons/feather-icons/feather.min.js')}}"></script>	
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('form').submit(function(event) {
                var newPassword = $('#newpassword').val();
                var confirmPassword = $('#confirmpassword').val();

                if (newPassword !== confirmPassword) {
                    event.preventDefault();
                    $('#errorMessage').html('<div class="alert alert-danger">New Password and Confirm Password must match</div>');
                }
            });
            $('#newpassword, #confirmpassword').on('input', function() {
            $('#errorMessage').empty();
        });
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

<script>
    function disableSubmitButton() {
        document.getElementById("submitButton").disabled = true;
    }
</script>
</body>
</html>
