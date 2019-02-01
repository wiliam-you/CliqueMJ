@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

	<title>Admin Dashboard</title>

@endsection

@section('content')
	<div class="page-content-wrapper">
		<div class="page-content">

			<!-- BEGIN PAGE BREADCRUMB -->
			<ul class="page-breadcrumb breadcrumb hide">
				<li>
					<a href="#">Home</a><i class="fa fa-circle"></i>
				</li>
				<li class="active">
					Dashboard
				</li>
			</ul>
			<!-- END PAGE BREADCRUMB -->
			<!-- BEGIN PAGE CONTENT INNER -->
			<div class="row margin-top-10">
{{--				@if(Auth::user()->hasPermission('view.admin-users')==true || Auth::user()->isSuperadmin())--}}
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="dashboard-stat2">
							<div class="display">
								<div class="number">
									<h3 class="font-purple-soft">{{$admin_user_count}}</h3>
									<small>SUB USERS</small>
								</div>
								<div class="icon">
									<i class="icon-users"></i>
								</div>
							</div>
							<div class="progress-info">
								<div class="progress">
								<span style="width: 100%;" class="progress-bar progress-bar-success purple-soft">
								
								</span>
								</div>
								<div class="status">
									<div class="status-title">
										<a href="{{url('/admin/admin-users')}}"> Click Here to see more </a>
									</div>

								</div>
							</div>

						</div>

					</div>
				{{--@endif--}}
{{--				@if(Auth::user()->hasPermission('view.registered-users')==true || Auth::user()->isSuperadmin())--}}
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="dashboard-stat2">
							<div class="display">
								<div class="number">
									<h3 class="font-purple-soft">{{$resistered_dispencery_count}}</h3>
									<small>Dispensary Users</small>
								</div>
								<div class="icon">
									<i class="icon-users"></i>
								</div>
							</div>
							<div class="progress-info">
								<div class="progress">
								<span style="width: 100%;" class="progress-bar progress-bar-success purple-soft">
								
								</span>
								</div>
								<div class="status">
									<div class="status-title">
										<a href="{{url('/admin/manage-dispensary-user')}}"> Click Here to see more </a>
									</div>

								</div>
							</div>

						</div>
					</div>
				{{--@endif--}}
{{--				@if(Auth::user()->hasPermission('view.registered-users')==true || Auth::user()->isSuperadmin())--}}
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="dashboard-stat2">
							<div class="display">
								<div class="number">
									<h3 class="font-purple-soft">{{$resistered_patient_count}}</h3>
									<small>Patient Users</small>
								</div>
								<div class="icon">
									<i class="icon-users"></i>
								</div>
							</div>
							<div class="progress-info">
								<div class="progress">
								<span style="width: 100%;" class="progress-bar progress-bar-success purple-soft">

								</span>
								</div>
								<div class="status">
									<div class="status-title">
										<a href="{{url('/admin/manage-patient')}}"> Click Here to see more </a>
									</div>

								</div>
							</div>

						</div>
					</div>
				{{--@endif--}}

{{--				@if(Auth::user()->hasPermission('view.registered-users')==true || Auth::user()->isSuperadmin())--}}
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="dashboard-stat2">
							<div class="display">
								<div class="number">
									<h3 class="font-purple-soft">{{Auth::user()->getCountOfAllFeedback()->count()}}</h3>
									<small>Total Feedback's</small>
									<br><hr>
									@if(Auth::user()->getCountOfNewFeedback()) <small class="sm-txt">Unread Feedback's <span class="badge badge-error">{{Auth::user()->getCountOfNewFeedback()}}</span></small>@endif
								</div>
								<div class="icon">
									<i class="icon-credit-card"></i>
								</div>
							</div>
							<div class="progress-info">
								<div class="progress">
								<span style="width: 100%;" class="progress-bar progress-bar-success purple-soft">

								</span>
								</div>
								<div class="status">
									<div class="status-title">
										<a href="{{url('/admin/feedback/list')}}"> Click Here to see more </a>
									</div>

								</div>
							</div>
						</div>
					</div>
				{{--@endif--}}

			</div>

		</div>
	</div>
@endsection
