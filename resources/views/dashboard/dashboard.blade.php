
  @extends('layouts.app')

@section('content')
<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
	
<div class="wrapper">
	<div id="loader"></div>
	
 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-xl-9 col-12">
					<div class="row">
						<div class="col-lg-4 col-12">
							<div class="box">
								<div class="box-body py-0">
									<div class="d-flex justify-content-between align-items-center">
										<div>
											<h5 class="text-fade">Applications</h5>
											<h2 class="fw-500 mb-0">132.0K</h2>
										</div>
										<div>
											<div id="revenue1"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-12">
							<div class="box">
								<div class="box-body py-0">
									<div class="d-flex justify-content-between align-items-center">
										<div>
											<h5 class="text-fade">Shortlisted</h5>
											<h2 class="fw-500 mb-0">10.9k</h2>
										</div>
										<div>
											<div id="revenue2"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-12">
							<div class="box">
								<div class="box-body py-0">
									<div class="d-flex justify-content-between align-items-center">
										<div>
											<h5 class="text-fade">On Hold</h5>
											<h2 class="fw-500 mb-0">03.1k</h2>
										</div>
										<div>
											<div id="revenue3"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xxxl-8 col-xl-7 col-12">
							<div class="box">
								<div class="box-header">
									<h4 class="box-title">Active Jobs</h4>
									<ul class="box-controls pull-right d-md-flex d-none">
									  <li class="dropdown">
										<button class="btn btn-primary px-10" href="#">View List</button>
									  </li>
									</ul>
								</div>
								<div class="box-body">
									<div id="active_jobs"></div>
								</div>
								<div class="box-body">
									<div class="bb-1 d-flex justify-content-between">
										<h5>Job title</h5>
										<h5>Applications</h5>
									</div>
									<div class="d-flex justify-content-between my-15">
										<p>Project Manager</p>
										<p> 
											<strong>325</strong>
											<button type="button" class="waves-effect waves-light btn btn-xs btn-outline btn-primary-light mx-5">
												<i class= "fa fa-line-chart"></i>
											</button>
										</p>
									</div>
									<div class="d-flex justify-content-between my-15">
										<p>Sales Manager</p>
										<p> 
											<strong>154</strong>
											<button type="button" class="waves-effect waves-light btn btn-xs btn-outline btn-primary-light mx-5">
												<i class= "fa fa-line-chart"></i>
											</button>
										</p>
									</div>
									<div class="d-flex justify-content-between my-15">
										<p>Machine Instrument</p>
										<p> 
											<strong>412</strong>
											<button type="button" class="waves-effect waves-light btn btn-xs btn-outline btn-primary-light mx-5">
												<i class= "fa fa-line-chart"></i>
											</button>
										</p>
									</div>
									<div class="d-flex justify-content-between mt-15">
										<p>Operation Manager</p>
										<p> 
											<strong>412</strong>
											<button type="button" class="waves-effect waves-light btn btn-xs btn-outline btn-primary-light mx-5">
												<i class= "fa fa-line-chart"></i>
											</button>
										</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xxxl-4 col-xl-5 col-12">
							<div class="box">
								<div class="box-header">
									<h4 class="box-title">Total Applications</h4>
								</div>
								<div class="box-body">
									<div class="d-flex w-p100 rounded100 overflow-hidden">
										<div class="bg-danger h-10" style="width: 8%;"></div>
										<div class="bg-warning h-10" style="width: 12%;"></div>
										<div class="bg-success h-10" style="width: 22%;"></div>
										<div class="bg-info h-10" style="width: 58%;"></div>
									</div>
								</div>
								<div class="box-body p-0">
									<div class="media-list media-list-hover media-list-divided">
										<a class="media media-single rounded-0" href="#">
										  <span class="badge badge-xl badge-dot badge-info"></span>
										  <span class="title">Applications </span>
										  <span class="badge badge-pill badge-info-light">58%</span>
										</a>

										<a class="media media-single rounded-0" href="#">
										  <span class="badge badge-xl badge-dot badge-success"></span>
										  <span class="title">Shortlisted</span>
										  <span class="badge badge-pill badge-success-light">22%</span>
										</a>

										<a class="media media-single rounded-0" href="#">
										  <span class="badge badge-xl badge-dot badge-warning"></span>
										  <span class="title">On-Hold</span>
										  <span class="badge badge-pill badge-warning-light">12%</span>
										</a>

										<a class="media media-single rounded-0" href="#">
										  <span class="badge badge-xl badge-dot badge-danger"></span>
										  <span class="title">Rejected</span>
										  <span class="badge badge-pill badge-danger-light">08%</span>
										</a>
									</div>
								</div>
							</div>
							<div class="box">
								<div class="box-header with-border">
									<h4 class="box-title">New Applications</h4>
								</div>
								<div class="box-body">
									<div class="d-flex align-items-center mb-30">
										<div class="me-15">
											<img src="assets/img/avatar/avatar-1.png" class="avatar avatar-lg rounded100 bg-primary-light" alt="" />
										</div>
										<div class="d-flex flex-column flex-grow-1 fw-500">
											<a href="#" class="text-dark hover-primary mb-1 fs-16">Sophia Doe</a>
											<span class="fs-12"><span class="text-fade">Applied for</span> Advertising Intern</span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu">
											  <a class="dropdown-item flexbox" href="#">
												<span>Inbox</span>
												<span class="badge badge-pill badge-info">5</span>
											  </a>
											  <a class="dropdown-item" href="#">Sent</a>
											  <a class="dropdown-item" href="#">Spam</a>
											  <div class="dropdown-divider"></div>
											  <a class="dropdown-item flexbox" href="#">
												<span>Draft</span>
												<span class="badge badge-pill badge-default">1</span>
											  </a>
											</div>
										</div>
									</div>
									<div class="d-flex align-items-center mb-30">
										<div class="me-15">
											<img src="assets/img/avatar/avatar-10.png" class="avatar avatar-lg rounded100 bg-primary-light" alt="" />
										</div>
										<div class="d-flex flex-column flex-grow-1 fw-500">
											<a href="#" class="text-dark hover-danger mb-1 fs-16">Mason Clark</a>						
											<span class="fs-12"><span class="text-fade">Applied for</span> Project Coordinator</span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu">
											  <a class="dropdown-item flexbox" href="#">
												<span>Inbox</span>
												<span class="badge badge-pill badge-info">5</span>
											  </a>
											  <a class="dropdown-item" href="#">Sent</a>
											  <a class="dropdown-item" href="#">Spam</a>
											  <div class="dropdown-divider"></div>
											  <a class="dropdown-item flexbox" href="#">
												<span>Draft</span>
												<span class="badge badge-pill badge-default">1</span>
											  </a>
											</div>
										</div>
									</div>
									<div class="d-flex align-items-center mb-30">
										<div class="me-15">
											<img src="assets/img/avatar/avatar-11.png" class="avatar avatar-lg rounded100 bg-primary-light" alt="" />
										</div>
										<div class="d-flex flex-column flex-grow-1 fw-500">
											<a href="#" class="text-dark hover-success mb-1 fs-16">Emily Paton</a>						
											<span class="fs-12"><span class="text-fade">Applied for</span> Layout Expert</span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu">
											  <a class="dropdown-item flexbox" href="#">
												<span>Inbox</span>
												<span class="badge badge-pill badge-info">5</span>
											  </a>
											  <a class="dropdown-item" href="#">Sent</a>
											  <a class="dropdown-item" href="#">Spam</a>
											  <div class="dropdown-divider"></div>
											  <a class="dropdown-item flexbox" href="#">
												<span>Draft</span>
												<span class="badge badge-pill badge-default">1</span>
											  </a>
											</div>
										</div>
									</div>
									<div class="d-flex align-items-center">
										<div class="me-15">
											<img src="assets/img/avatar/avatar-12.png" class="avatar avatar-lg rounded100 bg-primary-light" alt="" />
										</div>
										<div class="d-flex flex-column flex-grow-1 fw-500">
											<a href="#" class="text-dark hover-info mb-1 fs-16">Daniel Breth</a>											
											<span class="fs-12"><span class="text-fade">Applied for</span> Interior Architect</span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu">
											  <a class="dropdown-item flexbox" href="#">
												<span>Inbox</span>
												<span class="badge badge-pill badge-info">5</span>
											  </a>
											  <a class="dropdown-item" href="#">Sent</a>
											  <a class="dropdown-item" href="#">Spam</a>
											  <div class="dropdown-divider"></div>
											  <a class="dropdown-item flexbox" href="#">
												<span>Draft</span>
												<span class="badge badge-pill badge-default">1</span>
											  </a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>				
				<div class="col-xl-3 col-12">
					<div class="box">
						<div class="box-body">							
							<div class="box no-shadow">
								<div class="box-body px-0 pt-0">
									<div id="calendar" class="dask evt-cal min-h-350"></div>
								</div>
							</div>
							<div>
								<h4 class="box-title mb-30">Scheduled Meeting</h4>
								<div>
								  	<div class="d-flex align-items-center mb-30 justify-content-between">
										<div class="me-15 text-center rounded15 box-shadowed h-50 w-50 d-table">
											<p class="mt-5 mb-0 text-warning">Thu</p>
											<p class="mb-0">8</p>
										</div>
										<div class="d-flex flex-column flex-grow-1 fw-500 min-w-p60">
											<a href="#" class="text-dark hover-primary mb-1 fs-16 overflow-hidden text-nowrap text-overflow">Interview</a>
											<span class="fs-10 text-fade"><i class="fa fa-clock-o"></i> 9:00am - 11:30am </span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu">
											  <a class="dropdown-item" href="#">Action</a>
											  <a class="dropdown-item" href="#">Delete</a>
											</div>
										</div>
									</div>
								  	<div class="d-flex align-items-center mb-30 justify-content-between">
										<div class="me-15 text-center rounded15 box-shadowed h-50 w-50 d-table">
											<p class="mt-5 mb-0 text-warning">Fri</p>
											<p class="mb-0">10</p>
										</div>
										<div class="d-flex flex-column flex-grow-1 fw-500 min-w-p60">
											<a href="#" class="text-dark hover-primary mb-1 fs-16 overflow-hidden text-nowrap text-overflow">Organizational meeting</a>
											<span class="fs-10 text-fade"><i class="fa fa-clock-o"></i> 10:00am - 10:30am </span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu">
											  <a class="dropdown-item" href="#">Action</a>
											  <a class="dropdown-item" href="#">Delete</a>
											</div>
										</div>
									</div>
								  	<div class="d-flex align-items-center mb-30 justify-content-between">
										<div class="me-15 text-center rounded15 box-shadowed h-50 w-50 d-table">
											<p class="mt-5 mb-0 text-warning">Mon</p>
											<p class="mb-0">17</p>
										</div>
										<div class="d-flex flex-column flex-grow-1 fw-500 min-w-p60">
											<a href="#" class="text-dark hover-primary mb-1 fs-16 overflow-hidden text-nowrap text-overflow">Meeting with the manager</a>
											<span class="fs-10 text-fade"><i class="fa fa-clock-o"></i> 9:00am - 11:30am </span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu">
											  <a class="dropdown-item" href="#">Action</a>
											  <a class="dropdown-item" href="#">Delete</a>
											</div>
										</div>
									</div>
								  	<div class="d-flex align-items-center mb-30 justify-content-between">
										<div class="me-15 text-center rounded15 box-shadowed h-50 w-50 d-table">
											<p class="mt-5 mb-0 text-warning">Set</p>
											<p class="mb-0">18</p>
										</div>
										<div class="d-flex flex-column flex-grow-1 fw-500 min-w-p60">
											<a href="#" class="text-dark hover-primary mb-1 fs-16 overflow-hidden text-nowrap text-overflow">Interview</a>
											<span class="fs-10 text-fade"><i class="fa fa-clock-o"></i> 9:00am - 11:30am </span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu">
											  <a class="dropdown-item" href="#">Action</a>
											  <a class="dropdown-item" href="#">Delete</a>
											</div>
										</div>
									</div>
								  	<div class="d-flex align-items-center mb-20 justify-content-between">
										<div class="me-15 text-center rounded15 box-shadowed h-50 w-50 d-table">
											<p class="mt-5 mb-0 text-warning">Fri</p>
											<p class="mb-0">22</p>
										</div>
										<div class="d-flex flex-column flex-grow-1 fw-500 min-w-p60">
											<a href="#" class="text-dark hover-primary mb-1 fs-16 overflow-hidden text-nowrap text-overflow">Organizational meeting</a>
											<span class="fs-10 text-fade"><i class="fa fa-clock-o"></i> 10:00am - 10:30am </span>
										</div>
										<div class="dropdown">
											<a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i class="ti-more-alt"></i></a>
											<div class="dropdown-menu">
											  <a class="dropdown-item" href="#">Action</a>
											  <a class="dropdown-item" href="#">Delete</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-12">
				  <div class="box box-inverse bg-twitter">
					<div class="box-body">
					  <h3 class="text-white mt-0">Vivamus condimentum erat non turpis placerat.</h3>
					  <small>14 April, 2020 via web</small>
					  <div class="mt-20">
						<i class="fa fa-twitter fs-26"></i>
						<ul class="list-inline float-end mb-0">
						  <li class="list-inline-item">
							<i class="fa fa-heart"></i> 845
						  </li>
						  <li class="list-inline-item">
							<i class="fa fa-thumbs-up"></i> 956
						  </li>
						</ul>
					  </div>
					</div>
				  </div>
			    </div>
				<div class="col-lg-4 col-12">
				  <div class="box box-inverse bg-facebook">
					<div class="box-body">
					  <h3 class="text-white mt-0">Vivamus condimentum erat non turpis placerat.</h3>
					  <small>14 April, 2020 via web</small>
					  <div class="mt-20">
						<i class="fa fa-facebook fs-26"></i>
						<ul class="list-inline float-end mb-0">
						  <li class="list-inline-item">
							<i class="fa fa-thumbs-o-up"></i> 845
						  </li>
						  <li class="list-inline-item">
							<i class="fa fa-star"></i> 956
						  </li>
						</ul>
					  </div>
					</div>
				  </div>
			    </div>
				<div class="col-lg-4 col-12">
				  <div class="box box-inverse box-info">
					<div class="box-body">
					  <a class="avatar float-start me-20" href="javascript:void(0)">
						<img src="assets/img/avatar/5.jpg" alt="">
					  </a>
					  <div>
						<small class="float-end">Today, 16:05</small>
						<div class="fs-18">Johen Doe</div>
						<div class="fs-14 mb-10">Designer</div>
						<blockquote class="blockquote my-10 fs-16 text-white me-0">Vivamus condimentum erat non turpis placerat, at volutpat metus.</blockquote>
					  </div>
					</div>
				  </div>
			    </div>
			</div>
		</section>
		<!-- /.content -->
	  </div>
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right d-none d-sm-inline-block">
        <ul class="nav nav-primary nav-dotted nav-dot-separated justify-content-center justify-content-md-end">
		  <li class="nav-item">
			<a class="nav-link" href="javascript:void(0)">FAQ</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="#">Purchase Now</a>
		  </li>
		</ul>
    </div>
	  &copy; 2021 <a href="https://www.multipurposethemes.com/">Multipurpose Themes</a>. All Rights Reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar">
	  
	<div class="rpanel-title"><span class="pull-right btn btn-circle btn-danger"><i class="ion ion-close text-white" data-toggle="control-sidebar"></i></span> </div>  <!-- Create the tabs -->
    <ul class="nav nav-tabs control-sidebar-tabs">
      <li class="nav-item"><a href="#control-sidebar-home-tab" data-bs-toggle="tab" class="active"><i class="mdi mdi-message-text"></i></a></li>
      <li class="nav-item"><a href="#control-sidebar-settings-tab" data-bs-toggle="tab"><i class="mdi mdi-playlist-check"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
          <div class="flexbox">
			<a href="javascript:void(0)" class="text-grey">
				<i class="ti-more"></i>
			</a>	
			<p>Users</p>
			<a href="javascript:void(0)" class="text-end text-grey"><i class="ti-plus"></i></a>
		  </div>
		  <div class="lookup lookup-sm lookup-right d-none d-lg-block">
			<input type="text" name="s" placeholder="Search" class="w-p100">
		  </div>
          <div class="media-list media-list-hover mt-20">
			<div class="media py-10 px-0">
			  <a class="avatar avatar-lg status-success" href="#">
				<img src="assets/img/avatar/1.jpg" alt="...">
			  </a>
			  <div class="media-body">
				<p class="fs-16">
				  <a class="hover-primary" href="#"><strong>Tyler</strong></a>
				</p>
				<p>Praesent tristique diam...</p>
				  <span>Just now</span>
			  </div>
			</div>

			<div class="media py-10 px-0">
			  <a class="avatar avatar-lg status-danger" href="#">
				<img src="assets/img/avatar/2.jpg" alt="...">
			  </a>
			  <div class="media-body">
				<p class="fs-16">
				  <a class="hover-primary" href="#"><strong>Luke</strong></a>
				</p>
				<p>Cras tempor diam ...</p>
				  <span>33 min ago</span>
			  </div>
			</div>

			<div class="media py-10 px-0">
			  <a class="avatar avatar-lg status-warning" href="#">
				<img src="assets/img/avatar/3.jpg" alt="...">
			  </a>
			  <div class="media-body">
				<p class="fs-16">
				  <a class="hover-primary" href="#"><strong>Evan</strong></a>
				</p>
				<p>In posuere tortor vel...</p>
				  <span>42 min ago</span>
			  </div>
			</div>

			<div class="media py-10 px-0">
			  <a class="avatar avatar-lg status-primary" href="#">
				<img src="assets/img/avatar/4.jpg" alt="...">
			  </a>
			  <div class="media-body">
				<p class="fs-16">
				  <a class="hover-primary" href="#"><strong>Evan</strong></a>
				</p>
				<p>In posuere tortor vel...</p>
				  <span>42 min ago</span>
			  </div>
			</div>			
			
			<div class="media py-10 px-0">
			  <a class="avatar avatar-lg status-success" href="#">
				<img src="assets/img/avatar/1.jpg" alt="...">
			  </a>
			  <div class="media-body">
				<p class="fs-16">
				  <a class="hover-primary" href="#"><strong>Tyler</strong></a>
				</p>
				<p>Praesent tristique diam...</p>
				  <span>Just now</span>
			  </div>
			</div>

			<div class="media py-10 px-0">
			  <a class="avatar avatar-lg status-danger" href="#">
				<img src="assets/img/avatar/2.jpg" alt="...">
			  </a>
			  <div class="media-body">
				<p class="fs-16">
				  <a class="hover-primary" href="#"><strong>Luke</strong></a>
				</p>
				<p>Cras tempor diam ...</p>
				  <span>33 min ago</span>
			  </div>
			</div>

			<div class="media py-10 px-0">
			  <a class="avatar avatar-lg status-warning" href="#">
				<img src="assets/img/avatar/3.jpg" alt="...">
			  </a>
			  <div class="media-body">
				<p class="fs-16">
				  <a class="hover-primary" href="#"><strong>Evan</strong></a>
				</p>
				<p>In posuere tortor vel...</p>
				  <span>42 min ago</span>
			  </div>
			</div>

			<div class="media py-10 px-0">
			  <a class="avatar avatar-lg status-primary" href="#">
				<img src="assets/img/avatar/4.jpg" alt="...">
			  </a>
			  <div class="media-body">
				<p class="fs-16">
				  <a class="hover-primary" href="#"><strong>Evan</strong></a>
				</p>
				<p>In posuere tortor vel...</p>
				  <span>42 min ago</span>
			  </div>
			</div>
			  
		  </div>

      </div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
          <div class="flexbox">
			<a href="javascript:void(0)" class="text-grey">
				<i class="ti-more"></i>
			</a>	
			<p>Todo List</p>
			<a href="javascript:void(0)" class="text-end text-grey"><i class="ti-plus"></i></a>
		  </div>
        <ul class="todo-list mt-20">
			<li class="py-15 px-5 by-1">
			  <!-- checkbox -->
			  <input type="checkbox" id="basic_checkbox_1" class="filled-in">
			  <label for="basic_checkbox_1" class="mb-0 h-15"></label>
			  <!-- todo text -->
			  <span class="text-line">Nulla vitae purus</span>
			  <!-- Emphasis label -->
			  <small class="badge bg-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
			  <!-- General tools such as edit or delete-->
			  <div class="tools">
				<i class="fa fa-edit"></i>
				<i class="fa fa-trash-o"></i>
			  </div>
			</li>
			<li class="py-15 px-5">
			  <!-- checkbox -->
			  <input type="checkbox" id="basic_checkbox_2" class="filled-in">
			  <label for="basic_checkbox_2" class="mb-0 h-15"></label>
			  <span class="text-line">Phasellus interdum</span>
			  <small class="badge bg-info"><i class="fa fa-clock-o"></i> 4 hours</small>
			  <div class="tools">
				<i class="fa fa-edit"></i>
				<i class="fa fa-trash-o"></i>
			  </div>
			</li>
			<li class="py-15 px-5 by-1">
			  <!-- checkbox -->
			  <input type="checkbox" id="basic_checkbox_3" class="filled-in">
			  <label for="basic_checkbox_3" class="mb-0 h-15"></label>
			  <span class="text-line">Quisque sodales</span>
			  <small class="badge bg-warning"><i class="fa fa-clock-o"></i> 1 day</small>
			  <div class="tools">
				<i class="fa fa-edit"></i>
				<i class="fa fa-trash-o"></i>
			  </div>
			</li>
			<li class="py-15 px-5">
			  <!-- checkbox -->
			  <input type="checkbox" id="basic_checkbox_4" class="filled-in">
			  <label for="basic_checkbox_4" class="mb-0 h-15"></label>
			  <span class="text-line">Proin nec mi porta</span>
			  <small class="badge bg-success"><i class="fa fa-clock-o"></i> 3 days</small>
			  <div class="tools">
				<i class="fa fa-edit"></i>
				<i class="fa fa-trash-o"></i>
			  </div>
			</li>
			<li class="py-15 px-5 by-1">
			  <!-- checkbox -->
			  <input type="checkbox" id="basic_checkbox_5" class="filled-in">
			  <label for="basic_checkbox_5" class="mb-0 h-15"></label>
			  <span class="text-line">Maecenas scelerisque</span>
			  <small class="badge bg-primary"><i class="fa fa-clock-o"></i> 1 week</small>
			  <div class="tools">
				<i class="fa fa-edit"></i>
				<i class="fa fa-trash-o"></i>
			  </div>
			</li>
			<li class="py-15 px-5">
			  <!-- checkbox -->
			  <input type="checkbox" id="basic_checkbox_6" class="filled-in">
			  <label for="basic_checkbox_6" class="mb-0 h-15"></label>
			  <span class="text-line">Vivamus nec orci</span>
			  <small class="badge bg-info"><i class="fa fa-clock-o"></i> 1 month</small>
			  <div class="tools">
				<i class="fa fa-edit"></i>
				<i class="fa fa-trash-o"></i>
			  </div>
			</li>
			<li class="py-15 px-5 by-1">
			  <!-- checkbox -->
			  <input type="checkbox" id="basic_checkbox_7" class="filled-in">
			  <label for="basic_checkbox_7" class="mb-0 h-15"></label>
			  <!-- todo text -->
			  <span class="text-line">Nulla vitae purus</span>
			  <!-- Emphasis label -->
			  <small class="badge bg-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
			  <!-- General tools such as edit or delete-->
			  <div class="tools">
				<i class="fa fa-edit"></i>
				<i class="fa fa-trash-o"></i>
			  </div>
			</li>
			<li class="py-15 px-5">
			  <!-- checkbox -->
			  <input type="checkbox" id="basic_checkbox_8" class="filled-in">
			  <label for="basic_checkbox_8" class="mb-0 h-15"></label>
			  <span class="text-line">Phasellus interdum</span>
			  <small class="badge bg-info"><i class="fa fa-clock-o"></i> 4 hours</small>
			  <div class="tools">
				<i class="fa fa-edit"></i>
				<i class="fa fa-trash-o"></i>
			  </div>
			</li>
			<li class="py-15 px-5 by-1">
			  <!-- checkbox -->
			  <input type="checkbox" id="basic_checkbox_9" class="filled-in">
			  <label for="basic_checkbox_9" class="mb-0 h-15"></label>
			  <span class="text-line">Quisque sodales</span>
			  <small class="badge bg-warning"><i class="fa fa-clock-o"></i> 1 day</small>
			  <div class="tools">
				<i class="fa fa-edit"></i>
				<i class="fa fa-trash-o"></i>
			  </div>
			</li>
			<li class="py-15 px-5">
			  <!-- checkbox -->
			  <input type="checkbox" id="basic_checkbox_10" class="filled-in">
			  <label for="basic_checkbox_10" class="mb-0 h-15"></label>
			  <span class="text-line">Proin nec mi porta</span>
			  <small class="badge bg-success"><i class="fa fa-clock-o"></i> 3 days</small>
			  <div class="tools">
				<i class="fa fa-edit"></i>
				<i class="fa fa-trash-o"></i>
			  </div>
			</li>
		  </ul>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  
  <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
  
</div>
<!-- ./wrapper -->
	
	<!-- ./side demo panel -->
	<div class="sticky-toolbar">	    
	     <a href="https://themeforest.net/item/joblly-career-admin-dashboard-bootstrap-html/29916105" data-bs-toggle="tooltip" data-bs-placement="left" title="Buy Now" class="waves-effect waves-light btn btn-success btn-flat mb-5 btn-sm" target="_blank">
			<span class="icon-Money"><span class="path1"></span><span class="path2"></span></span>
		</a>
	    <a href="https://themeforest.net/user/multipurposethemes/portfolio" data-bs-toggle="tooltip" data-bs-placement="left" title="Portfolio" class="waves-effect waves-light btn btn-danger btn-flat mb-5 btn-sm" target="_blank">
			<span class="icon-Image"></span>
		</a>
	    <a id="chat-popup" href="#" data-bs-toggle="tooltip" data-bs-placement="left" title="Live Chat" class="waves-effect waves-light btn btn-warning btn-flat btn-sm">
			<span class="icon-Group-chat"><span class="path1"></span><span class="path2"></span></span>
		</a>
	</div>
	<!-- Sidebar -->
		
	<div id="chat-box-body">
		<div id="chat-circle" class="waves-effect waves-circle btn btn-circle btn-lg btn-warning l-h-70">
            <div id="chat-overlay"></div>
            <span class="icon-Group-chat fs-30"><span class="path1"></span><span class="path2"></span></span>
		</div>

		<div class="chat-box">
            <div class="chat-box-header p-15 d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button class="waves-effect waves-circle btn btn-circle btn-primary-light h-40 w-40 rounded-circle l-h-45" type="button" data-bs-toggle="dropdown">
                      <span class="icon-Add-user fs-22"><span class="path1"></span><span class="path2"></span></span>
                  </button>
                  <div class="dropdown-menu min-w-200">
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Color me-15"></span>
                        New Group</a>
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Clipboard me-15"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></span>
                        Contacts</a>
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Group me-15"><span class="path1"></span><span class="path2"></span></span>
                        Groups</a>
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Active-call me-15"><span class="path1"></span><span class="path2"></span></span>
                        Calls</a>
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Settings1 me-15"><span class="path1"></span><span class="path2"></span></span>
                        Settings</a>
                    <div class="dropdown-divider"></div>
					<a class="dropdown-item fs-16" href="#">
                        <span class="icon-Question-circle me-15"><span class="path1"></span><span class="path2"></span></span>
                        Help</a>
					<a class="dropdown-item fs-16" href="#">
                        <span class="icon-Notifications me-15"><span class="path1"></span><span class="path2"></span></span> 
                        Privacy</a>
                  </div>
                </div>
                <div class="text-center flex-grow-1">
                    <div class="text-dark fs-18">Mayra Sibley</div>
                    <div>
                        <span class="badge badge-sm badge-dot badge-primary"></span>
                        <span class="text-muted fs-12">Active</span>
                    </div>
                </div>
                <div class="chat-box-toggle">
                    <a id="chat-box-toggle" class="waves-effect waves-circle btn btn-circle btn-danger-light h-40 w-40 rounded-circle l-h-45" href="#">
                      <span class="icon-Close fs-22"><span class="path1"></span><span class="path2"></span></span>
                    </a>                    
                </div>
            </div>
            <div class="chat-box-body">
                <div class="chat-box-overlay">   
                </div>
                <div class="chat-logs">
                    <div class="chat-msg user">
                        <div class="d-flex align-items-center">
                            <span class="msg-avatar">
                                <img src="assets/img/avatar/2.jpg" class="avatar avatar-lg">
                            </span>
                            <div class="mx-10">
                                <a href="#" class="text-dark hover-primary fw-bold">Mayra Sibley</a>
                                <p class="text-muted fs-12 mb-0">2 Hours</p>
                            </div>
                        </div>
                        <div class="cm-msg-text">
                            Hi there, I'm Jesse and you?
                        </div>
                    </div>
                    <div class="chat-msg self">
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="mx-10">
                                <a href="#" class="text-dark hover-primary fw-bold">You</a>
                                <p class="text-muted fs-12 mb-0">3 minutes</p>
                            </div>
                            <span class="msg-avatar">
                                <img src="assets/img/avatar/3.jpg" class="avatar avatar-lg">
                            </span>
                        </div>
                        <div class="cm-msg-text">
                           My name is Anne Clarc.         
                        </div>        
                    </div>
                    <div class="chat-msg user">
                        <div class="d-flex align-items-center">
                            <span class="msg-avatar">
                                <img src="assets/img/avatar/2.jpg" class="avatar avatar-lg">
                            </span>
                            <div class="mx-10">
                                <a href="#" class="text-dark hover-primary fw-bold">Mayra Sibley</a>
                                <p class="text-muted fs-12 mb-0">40 seconds</p>
                            </div>
                        </div>
                        <div class="cm-msg-text">
                            Nice to meet you Anne.<br>How can i help you?
                        </div>
                    </div>
                </div><!--chat-log -->
            </div>
            <div class="chat-input">      
                <form>
                    <input type="text" id="chat-input" placeholder="Send a message..."/>
                    <button type="submit" class="chat-submit" id="chat-submit">
                        <span class="icon-Send fs-22"></span>
                    </button>
                </form>      
            </div>
		</div>
	</div>
	
	<!-- Page Content overlay -->
	
	


	
	
</body>
@endsection
<!-- Mirrored from joblly-admin-template-dashboard.multipurposethemes.com/bs5/main/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 20 Jul 2023 04:57:15 GMT -->
</html>

