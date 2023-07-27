<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  
<!-- Mirrored from joblly-admin-template-dashboard.multipurposethemes.com/bs5/main/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 20 Jul 2023 04:56:25 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://joblly-admin-template-dashboard.multipurposethemes.com/bs5/img/favicon.ico">

    <title>Admin Portals: @yield('title')</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="{{asset('assets/css/font-awsome.min.css')}}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- Vendors Style-->
	<link rel="stylesheet" href="{{asset('assets/css/vendors_css.css')}}">
	  
	<!-- Style-->  
	<link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/skin_color.css')}}">
     	<!-- Vendor JS -->
	<script src="{{asset('assets/js/vendors.min.js')}}"></script>
	<script src="{{asset('assets/js/pages/chat-popup.js')}}"></script>
    <script src="{{asset('assets/icons/feather-icons/feather.min.js')}}"></script>
  
<body>
<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\WalletController;
use \App\Http\Controllers\InheritApiController;

$userdetails = InheritApiController::headerUserDetails();
$permissions = (array)$userdetails->data->permissions;

if(Session::get('level') >= 7){
    $objWalletController = new WalletController();
    $walletBalance = (array)$objWalletController->getWalletBalance();

    $objCartController = new CartController();
    $cartCount = (array)$objCartController->getCartCount();
}
?>
<?php

if(!empty($userdetails->data->timezone))
    {?>

    <script>
        setInterval(showTime, 1000);
        function showTime()
        {
            var here = new Date();
            var zone = '<?php echo $userdetails->data->timezone; ?>';
            var invdate = new Date(here.toLocaleString('en-US', {timeZone: zone}));
            var diff = here.getTime() - invdate.getTime();
            var time = new Date(here.getTime() - diff);

            let hour = time.getHours();
            let min = time.getMinutes();
            let sec = time.getSeconds();
            am_pm = "AM";

            if (hour == 12)
            {
                hour = 12;
                am_pm = "PM";
            }

            else if (hour > 12)
            {
                hour -= 12;
                am_pm = "PM";
            }

            else if (hour == 0)
            {
                hour = 12;
                am_pm = "AM";
            }
            hour = hour < 10 ? "0" + hour : hour;
            min = min < 10 ? "0" + min : min;
            sec = sec < 10 ? "0" + sec : sec;

            let currentTime = hour + " : " + min + " : " + sec + " "+am_pm;
            //alert(currentTime);
            //document.getElementById("caf_clock").innerHTML = currentTime;
            $("#caf_clock").html(currentTime);
        }

        showTime();

    </script>
<?php }?>




<header class="main-header">
	<div class="d-flex align-items-center logo-box justify-content-start">
		<a href="#" class="waves-effect waves-light nav-link d-none d-md-inline-block mx-10 push-btn bg-transparent" data-toggle="push-menu" role="button">
			<i data-feather="menu"></i>
		</a>	
		<!-- Logo -->
		<a href="{{url('dashboard')}}" class="logo">
        <?php
                if(!empty($userdetails->data->logo)){
                if (file_exists(public_path() . '/logo/' . $userdetails->data->logo)) { ?>
              <img style="width: 119px;height: 53px;padding: 20px 0px 3px 0px;"
                   src="{{ asset('logo/') }}/{{$userdetails->data->logo}}"/>
              <?php
                }
                else {
                ?>
              <img style="width: 119px;height: 53px;padding: 20px 0px 3px 0px;"
                   src="{{ asset('logo/logo_white.png') }}"/>
              <?php
                }
                }
                else {
                ?>
            <img style="width: 119px;height: 43px;padding: 20px 0px 3px 0px;" src="{{ asset('logo/logo_white.png') }}"/>
            <?php
                }
                ?>
		  <!-- logo-->
		 
		</a>	

	</div>  
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
	  <div class="app-menu">
		<ul class="header-megamenu nav">
			<li class="btn-group nav-item d-md-none">
				<a href="#" class="waves-effect waves-light nav-link push-btn" data-toggle="push-menu" role="button">
					<i data-feather="menu"></i>
			    </a>
			</li>
		</ul> 
	  </div>
		
      <div class="navbar-custom-menu r-side">
        <ul class="nav navbar-nav">	
        <li class="btn-group nav-item text-fade d-lg-flex d-none align-items-center">@if(!empty($userdetails->data->timezone))
		  <div class="caf_clock" id="caf_clock"></div>    @endif
		  </li>
          @if(Session::get('level') >= 7) 
		  <li class="btn-group nav-item text-fade d-lg-flex d-none align-items-center"style="margin-left:10px;"> @if(!empty($walletBalance['amount']))
		  <div class="caf_clock">Balance: 
			@if($walletBalance['amount'] < 0)

			- ${{round(str_replace('-', '',$walletBalance['amount']), 5)}}
			@else
			${{round($walletBalance['amount'], 5)}}
			@endif
		</div> 
		@endif  
		  </li>
		  @endif  
          <li class="btn-group nav-item text-fade d-lg-flex d-none align-items-center"style="margin-left:10px;">
		  <a class="text-fade">{{$userdetails->data->company_name}} </a>
		  </li>
		  <li class="btn-group nav-item d-lg-inline-flex d-none">
			<a href="#" data-provide="fullscreen" class="waves-effect waves-light nav-link full-screen" title="Full Screen">
				<i data-feather="maximize"></i>
        
			</a>
		  </li>
       
		  <!-- Notifications -->
		  <li class="dropdown notifications-menu">
			<a href="#" class="waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown" title="Notifications">
			  <i data-feather="bell"></i>
			</a>
			<ul class="dropdown-menu animated bounceIn">

			  <li class="header">
				<div class="p-20">
					<div class="flexbox">
						<div>
                        <h6 class="mb-0 mt-0">You have <span class="notification_id_total_count">0</span> notifications</h6>						</div>
						<div>
							<!-- <a href="#" class="text-danger">Clear All</a> -->
						</div>
					</div>
				</div>
			  </li>

			  <li>
				<!-- inner menu: contains the actual data -->
				<ul class="menu sm-scrol">
				
				
                <li class="notification_id_text_count" style="display: none;">
                                        <a href="{{url('/inbox')}}">
                                            <i class="fa fa-commenting-o"></i>
                                            <span id="sms_count_unread"></span> New Text Messages
                                            <span class="label label-info" id="notification_id_text_count"></span>
                                        </a>
                                    </li>
				 
				
                                    <li class="notification_id_voicemail_count" style="display: none;">
                                        <a href="{{url('/mailbox')}}">
                                            <i class="fa fa-envelope"></i> New Voicemails
                                            <span class="label label-primary" id="notification_id_voicemail_count"></span>
                                        </a>
                                    </li>
                                    <li class="notification_id_fax_count" style="display: none;">
                                        <a href="{{url('/receive-fax')}}">
                                            <i class="fa fa-fax"></i> New Fax
                                            <span class="label label-danger" id="notification_id_fax_count"></span>
                                        </a>
                                        </li>  
				
                 </ul>
               </li>
               <li class="footer">
                   <!-- <a href="#">View all</a> -->
               </li>
             </ul>
           </li>
		  <!-- User Account-->
          <li class="dropdown user user-menu">
            <a href="#" class="waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown" title="User">
            <img src="{{ asset('profile-pic') }}/{{$userdetails->data->profile_pic}}" class="user-image" alt="User Image"
                                 onerror="this.onerror=null;this.src='{{ asset('assets/img/user-128x128.png') }}';">
            </a>
            <ul class="dropdown-menu animated flipInX">
           
              <li class="user-body">

				 <a class="dropdown-item" href="{{url('profile')}}"><i data-feather="user"></i> Profile</a>
				 <div class="dropdown-divider"></div>
				 <a class="dropdown-item" href="{{url('logout')}}"><i data-feather="log-out"></i> Logout</a>
              </li>
            </ul>
          </li>	
        </ul>
      </div>
    </nav>
  </header>  
  <script src="{{asset('assets/vendor_components/apexcharts-bundle/dist/apexcharts.js')}}"></script>
	<script src="{{asset('assets/vendor_components/moment/min/moment.min.js')}}"></script>
	<script src="{{asset('assets/vendor_components/fullcalendar/fullcalendar.js')}}"></script>
	

	<script src="{{asset('assets/js/template.js')}}"></script>
	<script src="{{asset('assets/js/pages/dashboard.js')}}"></script>
	<script src="{{asset('assets/js/pages/calendar-dash.js')}}"></script>
  <script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>	
	<script src="{{asset('assets/js/pages/data-table.js')}}"></script>
</head>
  </body>
</html>