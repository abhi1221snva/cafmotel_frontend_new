<?php
use \App\Http\Controllers\InheritApiController;

$usermenus = InheritApiController::headerMenu();
$userdetails = InheritApiController::headerUserDetails();

?>



<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">	
		<div class="user-profile px-20 py-10">
			<div class="d-flex align-items-center">			
				<div class="image">
                <img src="{{ asset('profile-pic') }}/{{$userdetails->data->profile_pic}}" class="user-image"
						alt="User Image"
						onerror="this.onerror=null;this.src='{{ asset('assets/img/user-128x128.png') }}';">
                </div>
				<div class="info">
                     <a class="dropdown-toggle px-20" data-bs-toggle="dropdown" href="#">{{$userdetails->data->first_name}} {{$userdetails->data->last_name}}</a>
					<div class="dropdown-menu">
					  <a class="dropdown-item" href="{{url('profile')}}"><i data-feather="user"></i> Profile</a>
					  <a class="dropdown-item" href="{{url('inbox')}}"><i data-feather="message-square"></i> Inbox</a>
					  <a class="dropdown-item" href="{{url('logout')}}"><i data-feather="log-out"></i> Logout</a>
				    </div>
			    </div>
		
	        </div>
        </div>
	  	<div class="multinav">
		  <div class="multinav-scroll" style="height: 100%;width:100%;">	
			  <!-- sidebar menu-->
              <ul class="sidebar-menu" data-widget="tree">	
					@foreach($usermenus as $menu_parent)	
						<li class="treeview">
								<a href="{{url('')}}/{{$menu_parent['parent']->url}}">

									<i class="{{$menu_parent['parent']->logo}}"></i>

									<span>{{$menu_parent['parent']->name}}</span>

									@if($menu_parent['parent']->arrow == 1)
										<span class="pull-right-container">
											<i class="fa fa-angle-right pull-right"></i>
										</span>
									
									@endif
								</a>
									@if($menu_parent['parent']->arrow == 1)

										<ul class="treeview-menu">
											@isset($menu_parent['child'])
												@foreach($menu_parent['child'] as $menu_child)
													@if($menu_child->is_active == 1)
													<li><a href="{{url('')}}/{{$menu_child->url}}">
														<i class="fa fa-circle"><span class="path1"></span><span class="path2"></span></i>{{$menu_child->name}} </a></li>
													@endif

												@endforeach
											@endisset
										</ul>
									@endif

						</li>
					@endforeach							 
				
			  </ul>
		  </div>
		</div>
    </section>
</aside>
