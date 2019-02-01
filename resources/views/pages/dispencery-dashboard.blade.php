@extends('layouts.vendor')

@section("meta")

<title>Dashboard</title>


@endsection

@section('content')
<div class="row top_tiles">

		@if(Auth::user()->userInformation->user_type == "2"|| Auth::user()->isSuperadmin())
			<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="tile-stats">
						<div class="icon"><i class="fa fa-users"></i></div>
						<div class="count">{{Auth::user()->customerCount()}}</div>
						<h3>Total Customers</h3>
						<p>Total customers till the date<br><a href="{{url('dispencery/customer/list')}}">Click to view</a></p>
					</div>
				</div>
		@endif
		@if(Auth::user()->userInformation->user_type == "2" || Auth::user()->isSuperadmin())
				<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="tile-stats">
						<div class="icon"><i class="fa fa-shopping-bag"></i></div>
						<div class="count">{{Auth::user()->productCount()}}</div>
						<h3>Total Products</h3>
						<p>Total product in the store<br><a href="{{url('dispencery/product/list')}}">Click to view</a></p>
					</div>
				</div>
		@endif
			@if(Auth::user()->userInformation->user_type == "2" || Auth::user()->isSuperadmin())
				<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="tile-stats">
						<div class="icon"><i class="fa fa-envelope"></i></div>
						<div class="count">{{Auth::user()->totalFeedbackOfDispensary->Count()}}</div>
						<p>Total feedback in the store<br><a href="{{url('dispensary/feedback/list')}}">Click to view</a></p>
					</div>
				</div>
			@endif
	</div>
    @endsection