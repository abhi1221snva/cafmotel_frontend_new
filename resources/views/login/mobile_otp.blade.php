<!DOCTYPE html>
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
<body class="hold-transition theme-primary bg-img" style="background-image: url(assets/img/auth-bg/bg-2.jpg)">
	
	<div class="container h-p100">
		<div class="row align-items-center justify-content-md-center h-p100">
        @include("layouts.messaging")
        <div id="errorMessage"></div>
        <div id="resendMessage"></div>
			<div class="col-12">
				<div class="row justify-content-center g-0">
					<div>						
						<div class="bg-white rounded10 shadow-lg">
							<div class="content-top-agile p-20 pb-0">
								<h3 class="mb-0 text-primary">Recover Password</h3>								
							</div>
							<div class="p-40">
                            <input id="mobile" type="hidden" class="form-control" name="mobile" placeholder="mobile" value="{{$mobile}}" required>
                                @php
                                if(!empty($mobile))
                                {
                                    $mobile = substr($mobile,-4);
                                }
                                else
                                {
                                    $mobile='';
                                }
                                @endphp
                                <p class="login-box-msg">Please enter the verification code which we have sent to your mobile number ending with {{$mobile}}.</p>
                                <form id="otp-form" method="POST" action="{{url('/verify-token-mobile/'.$otp_id)}}">
                                 @csrf
									<div class="form-group showdiv">
										<div class="input-group mb-3">
											<span class="input-group-text bg-transparent"><i class="fas fa-mobile-alt"></i></span>
											<input type="number" class="form-control ps-15 bg-transparent" id="otp"name="otp"placeholder="Please enter a valid 6-digit OTP" pattern="[0-9]{6}"  required>
                                            <span id="otpValidationMessage" style="color: red;"></span>
										</div>
									</div>
									  <div class="row">
										<div class="col-12 text-center">
										  <button type="submit" class="btn btn-info margin-top-10">Reset</button>
										</div>
										<!-- /.col -->
									  </div>
								</form>	
                                <div class="row">
                                <div class="col-xs-10 showdiv">
                                    <span class="showdiv">
                                        <a id="resendCodeBtn" href="#"style="display:none;">Resend Otp</a>
                                    </span>
                                    <br>
                                    <span id="timer"></span> <!-- Add the timer element here -->
                                </div>
                                <div class="col-xs-2 showdiv" style="margin-top:5px;">
                                    <a id="login" href="/">Login</a>
                                    <br>
                                </div>
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
        $(document).ready(function() {
  $("#otp").keyup(function() {
    var otpInput = $(this);
    var otpValue = otpInput.val().trim();
    var otpValidationMessage = $("#otpValidationMessage");

    // Check if the OTP input matches the 6-digit format
    if (!/^[0-9]{6}$/.test(otpValue)) {
      otpValidationMessage.text("Please enter a valid 6-digit OTP.");
    } else {
      otpValidationMessage.text("");
    }
  });
});
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });

    var timerStarted = false;

function countdown() 
{
    if (timerStarted) {
        return; // If the timer has already started, exit the function
    }

    var seconds = 59;

    function tick() {
        var counter = document.getElementById("timer");
        seconds--;
        counter.innerHTML = "0:" + (seconds < 10 ? "0" : "") + String(seconds);

        if (seconds > 0) {
            setTimeout(tick, 1000);
        } else {
            $("#resendCodeBtn").show();
            counter.innerHTML = "";
        }
    }

    tick();
    timerStarted = true; // Set the flag to indicate that the timer has started
}

$("#resendCodeBtn").click(function (event) {
    event.preventDefault();

    $.ajax({
        url: "{{ url('forgot-password-mobile') }}",
        method: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            mobile: $("#mobile").val()
        },
        success: function (response) {
            console.log(response); // Add this line to log the response data
            var messageElement = $("#resendMessage");

            // Clear any previous messages
            messageElement.empty();

            // Show success message
            var successMessage = '<div class="alert alert-success">OTP has been resent.</div>';
            messageElement.html(successMessage);

            countdown(); // Start the countdown again if needed
            $("#resendCodeBtn").hide();
        },
        error: function (xhr, status, error) {
            console.error(error); // Add this line to log any errors
            var messageElement = $("#resendMessage");

            // Clear any previous messages
            messageElement.empty();

            // Show error message
            var errorMessage = '<div class="alert alert-danger">An error occurred. Please try again later.</div>';
            messageElement.html(errorMessage);
        }
    });
});


countdown();
 

</script>

<script type="text/javascript">
    function checkEmail() {
        var email = document.getElementById("form_email").value;
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
                    var obj = JSON.parse(response);
                    $(".loading").hide();
                    $("#errorMessage").show();
                    if (obj.success == true) {
                        message = '<div class="alert alert-success alert-block"><button type="button" class="close" data-dismiss="alert">×</button>' + obj.message + '</div>'
                        $('#errorMessage').html(message);
                    } else {
                        message = '<div class="alert alert-danger alert-block"><button type="button" class="close" data-dismiss="alert">×</button>' + obj.message + '</div>'
                        $('#errorMessage').html(message);
                    }
                    setTimeout(function () {
                        window.location.reload(1);
                    }, 5000);
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
