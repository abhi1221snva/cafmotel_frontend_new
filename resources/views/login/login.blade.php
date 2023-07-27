<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Smart Phone Platform For Businesses | Login</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
=
</style>
<!-- <style>
    /* Custom radio buttons */
    .radio-label {
        display: inline-block;
        padding: 5px 15px;
        cursor: pointer;
        position: relative;
    }

    .radio-label input[type="radio"] {
        display: none;
    }

    /* Radio button style */
    .radio-custom {
        position: absolute;
        top: 50%;
        left: 0;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        border: 2px solid #bbb;
        border-radius: 50%;
        background-color: #fff;
    }

    /* Radio button checked style */
    .radio-label input[type="radio"]:checked + .radio-custom {
        background-color: #f96350;
    }

    /* Radio button inner dot */
    .radio-custom::after {
        content: '';
        display: block;
        width: 10px;
        height: 10px;
        margin: 4px;
        border-radius: 50%;
        background-color: #fff;
    }

    /* Radio button checked inner dot */
    .radio-label input[type="radio"]:checked + .radio-custom::after {
        background-color: #f96350;
    }

    /* Radio label text style */
    .radio-label-text {
        margin-left: 30px;
    }
</style> -->

</head>
	
<body class="hold-transition theme-primary bg-img" style="background-image: url(assets/img/auth-bg/bg-1.jpg)">
<div class="container h-p100">
		<div class="row align-items-center justify-content-md-center h-p100">	
			
			<div class="col-12">
				<div class="row justify-content-center g-0">
					<div class="col-lg-5 col-md-5 col-12">
                    <div class="row">
                        @include("layouts.messaging")
                    </div>
                    <div id="mobileOtpContainer"></div>
                    <div id="errorMessage"></div>
                        <span id="mobileError" class="invalid-feedback" role="alert"style="color:red;"></span>
                        <span id="email_status" style="color:red;"></span>
                        <div id="loginBoxContainer">
						<div class="bg-white rounded10 shadow-lg">
							<div class="content-top-agile p-20 pb-0">
								<h2 class="text-primary">Portal Login</h2>
								<p class="mb-0"id="sign">Sign in to start your session.</p>
                                <p class="mb-0"id="forgot"style="display:none;">Forgot Password</p>							
							
							</div>
    
                            <div class="form-check" id="radio_buttons" style="display: none; padding-left: 40px; padding-right: 40px; padding-top: 10px;" data-toggle="buttons">
                                <input class="form-check-input" type="radio" value="1" name="myRadios" id="emailOption" checked>
                                <label class="form-check-label" for="emailOption">Email</label>
                                
                                <input class="form-check-input" type="radio" value="2" name="myRadios" id="mobileOption">
                                <label class="form-check-label" for="mobileOption">Mobile</label>
                            </div>


							<div class="p-40 showdiv"style="text-align:center;">
								<form action="{{url('/')}}" method="POST">
                                    @csrf
									<div class="form-group showdiv">
										<div class="input-group mb-3">
											<span class="input-group-text bg-transparent"> <i class="fas fa-user"></i></span>
											<input id="email" type="text" class="form-control ps-15 bg-transparent"name="email" placeholder="Username">
										</div>
									</div>
									<div class="form-group showdiv">
										<div class="input-group mb-3">
											<span class="input-group-text  bg-transparent"><i class="fas fa-lock"></i></span>
											<input id="password" type="password" class="form-control ps-15 bg-transparent"name="password" placeholder="Password">
										</div>
									</div>						
										
										<!-- /.col -->
										<div class="col-12 text-center showdiv">
										  <button type="submit" class="btn btn-danger mt-10 ">SIGN IN</button>
										</div>
										<!-- /.col -->
								</form>	
							
							</div>	
							<div class="showdivMob"style="padding-left:40px;padding-right:40px;padding-top:10px;padding-bottom:10px;">
							    <form id="forgotPasswordMobile">
                                    @csrf
									<div class="form-group ">
										<div class="input-group mb-3">
											<span class="input-group-text bg-transparent"><i class="fas fa-mobile-alt"></i></span>
											<input type="text" class="form-control bg-transparent" required name="mobile" id="mobile" placeholder="Please enter 10-digit phone number" data-inputmask="'mask': '9999999999'">
											<small style="color: #888888;" id="mobileHint" class="text-muted">Hint: Please enter a 10-digit mobile number without the country code.</small>

										</div>

									</div>
								
								
										<div class="col-12 text-center">
										  <button type="submit" id="mobileSubmit" class="btn btn-danger mt-10">Submit</button>
										</div>
										<!-- /.col -->
								</form>	
								<!-- <div class="text-center">
									<p class="mt-15 mb-0">Don't have an account? <a href="auth_register.html" class="text-warning ms-5">Sign Up</a></p>
								</div>	 -->
							</div>	
							<div class="hidediv"style="padding-left:40px;padding-right:40px;padding-top:10px;padding-bottom:10px;">
							    <form id="resetPasswordForm">
                                    @csrf
									<div class="form-group ">
										<div class="input-group mb-3">
											<span class="input-group-text bg-transparent"> <i class="fas fa-user"></i></span>
											<input id="form_email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email Id" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

										</div>
									</div>
								
								
										
										<!-- /.col -->
										<div class="col-12 text-center">
										  <button type="submit" id="submitButton" class="btn btn-danger mt-10">Submit</button>
										</div>
										<!-- /.col -->
								</form>	
								<!-- <div class="text-center">
									<p class="mt-15 mb-0">Don't have an account? <a href="auth_register.html" class="text-warning ms-5">Sign Up</a></p>
								</div>	 -->
							</div>	
							<div class="row">
										<div class="col-6">
										</div>
							            <div class="col-6"style="padding-right:30px;padding-bottom:10px;">
										 <div class="fog-pwd text-end"style="text-align:right;">
											<a id="forgot_pass"href="#" class="hover-warning"><i class="ion ion-locked"></i> Forgot pwd?</a><br>
											<a id="login"href="#" class="hover-warning"><i class="ion ion-log-in"></i> login</a><br>

										  </div>
										</div>
                           </div>				
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<!-- Vendor JS -->
	<script src="{{asset('assets/js/vendors.min.js')}}"></script>
	<script src="{{asset('assets/js/pages/chat-popup.js')}}"></script>
    <script src="{{asset('assets/icons/feather-icons/feather.min.js')}}"></script>	
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{asset('assets/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{asset('assets/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{asset('assets/input-mask/jquery.inputmask.extensions.js') }}"></script>
<script>

    $(".hidediv").hide();
    $("#login").hide();
    $(".showdivMob").hide();


    $("#forgot_pass").click(function () {

        $(".showdiv").hide();
        $(".hidediv").show();
        $("#login").show();
        $("#forgot_pass").hide();
        $("#radio_buttons").show();
        $("#forgot").show();
        $("#sign").hide();



    });
    $("#mobilebtn").click(function () {
        $(".showdivMob").show();
        $(".hidediv").hide();
        $("#emailbtn").show();
        $("#mobilebtn").hide();
    });

    $("#emailbtn").click(function () {
        $(".showdivMob").hide();
        $(".hidediv").show();
        $("#emailbtn").hide();
        $("#mobilebtn").show();
    });
    $("#login").click(function () {
        $(".showdiv").show();
        $(".hidediv").hide();
        $("#login").hide();
        $("#forgot_pass").show();
        $("#radio_buttons").hide();
        $(".showdivMob").hide();
        $("#forgot").hide();
        $("#sign").show();

    });
    $(document).ready(function() {
    // Your jQuery code here
    $(".hidediv").hide();
    $(".showdivMob").hide();

    // Handle change event for login radio buttons
    $("input[name='myRadios']").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue === "1") { // Login using email
            $(".hidediv").show();
            $(".showdivMob").hide();
        } else if (selectedValue === "2") { // Login using mobile
            $(".hidediv").hide();
            $(".showdivMob").show();
        }
    });
});


    $(document).ready(function() {
        // Function to validate the email format
        function isValidEmail(email) {
            // Regular expression to check for a valid email format
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailPattern.test(email);
        }

        // Listen to the "input" event on the email input field
        $("#form_email").on('input', function() {
            var emailInput = $(this);
            var emailValue = emailInput.val().trim();
            var errorMessageElement = $("#email_status");

            // Check if the email input is empty
            if (emailValue === '') {
                errorMessageElement.html('');
                return;
            }

            // Check if the email input matches the email format
            if (!isValidEmail(emailValue)) {
                var errorMessage = '<div class="alert alert-danger">Please enter a valid email address.</div>';
                errorMessageElement.html(errorMessage);
            } else {
                errorMessageElement.html('');
            }
        });

        // Submit the form when the "Submit" button is clicked
        $("#submitButton").click(function(event) {
            event.preventDefault();

            var emailInput = $("#form_email");
            var emailValue = emailInput.val().trim();
            var errorMessageElement = $("#email_status");

            // Check if the email input is empty
            if (emailValue === '') {
                alert('Please enter your email address');
                return;
            }

            // Check if the email input matches the email format
            if (!isValidEmail(emailValue)) {
                alert('Please enter a valid email address');
                return;
            }

            // Disable the submit button to prevent multiple submissions
            $(this).prop('disabled', true);

            // Perform AJAX request to submit the form
            $.ajax({
                url: "{{ url('forgot-password') }}",
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    email: emailValue
                },
                success: function(response) {
                    console.log(response); // Add this line to log the response data
                    var messageElement = $("#email_status");

                    // Clear any previous messages
                    messageElement.empty();

                    // Check if the response contains the "error" property indicating an invalid mobile number
                    if (response.error === 'InvalidEmail') {
                        var errorMessage = '<div class="alert alert-danger">This Email is not registered. Please try it with a different email.</div>';
                        messageElement.html(errorMessage);
                    } else {
                        // Show success message
                        var successMessage = '<div class="alert alert-success">Password reset link has been sent to your mail. Please check your inbox for further instructions.</div>';
                        messageElement.html(successMessage);
                    }

                    // Enable the submit button back
                    $("#submitButton").prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    console.error(error); // Add this line to log any errors
                    var messageElement = $("#email_status");

                    // Clear any previous messages
                    messageElement.empty();

                    // Show error message
                    var errorMessage = '<div class="alert alert-danger">An error occurred. Please try again later.</div>';
                    messageElement.html(errorMessage);

                    // Enable the submit button back
                    $("#submitButton").prop('disabled', false);
                }
            });
        });
        $("#form_email").focus(function() {
            var messageElement = $("#email_status");
            messageElement.empty();
        });
    });





$(document).ready(function () {
            // Function to validate the mobile number
            function isValidMobile(mobile) {
                // Regular expression to check for a valid 10-digit mobile number
                var mobilePattern = /^\d{10}$/;
                return mobilePattern.test(mobile);
            }

            // Initialize the input mask for mobile number
            $('#mobile').inputmask('9999999999');

            // Submit the form when the "Submit" button is clicked
            $("#mobileSubmit").click(function (event) {
                event.preventDefault();

                var mobileInput = $("#mobile");
                var mobileValue = mobileInput.val().trim();
                var errorMessageElement = $("#mobileError");

                // Check if the mobile input is empty
                if (mobileValue === '') {
                    errorMessageElement.html('<div class="alert alert-danger">Please enter your mobile number.</div>');
                    return;
                }

                // Check if the mobile input matches the mobile number format
                if (!isValidMobile(mobileValue)) {
                    errorMessageElement.html('<div class="alert alert-danger">Please enter a valid 10-digit mobile number.</div>');
                    return;
                }

            // Disable the submit button to prevent multiple submissions
            $(this).prop('disabled', true);

            // Perform AJAX request to submit the form
            $.ajax({
                url: "{{ url('forgot-password-mobile') }}",
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    mobile: mobileValue
                },
                success: function(response) {
                    console.log(response); // Add this line to log the response data
                    var messageElement = $("#mobileError");

                    // Clear any previous messages
                    messageElement.empty();

                    // Check if the response contains the "error" property indicating an invalid mobile number
                    if (response.error === 'InvalidMobileNumber') {
                        var errorMessage = '<div class="alert alert-danger">This Mobile number is not registered.Please try it with different number.</div>';
                        messageElement.html(errorMessage);
                    } else {
                        // Show success message
                        var successMessage = '<div class="alert alert-success">OTP has been sent to your number.</div>';
                        messageElement.html(successMessage);
                        $("#loginBoxContainer").hide();

                        // Render and display the mobile_otp view
                        $("#mobileOtpContainer").html(response.view);
                    }

                    // Enable the submit button back
                    $("#mobileSubmit").prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    console.error(error); // Add this line to log any errors
                    var messageElement = $("#mobileError");

                    // Clear any previous messages
                    messageElement.empty();

                    // Show error message
                    var errorMessage = '<div class="alert alert-danger">An error occurred. Please try again later.</div>';
                    messageElement.html(errorMessage);

                    // Enable the submit button back
                    $("#mobileSubmit").prop('disabled', false);
                }
            });
        });
        $("#mobile").focus(function() {
            var messageElement = $("#mobileError");
            messageElement.empty();
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
</body>

<!-- Mirrored from joblly-admin-template-dashboard.multipurposethemes.com/bs5/main/auth_login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 20 Jul 2023 04:59:49 GMT -->
</html>
