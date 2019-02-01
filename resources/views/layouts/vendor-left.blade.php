<?php
$segments = Request::segment(2);
$segment_prameter = '';
$segment_value = '';
switch ($segments) {
    case 'dashboard':
        $segment_prameter = 'dashboard';
        $segment_value = 'dashboard';
        break;
    case 'product':
        $segment_prameter = 'product';
        $segment_value = 'product';
        break;
    case 'customer':
        $segment_prameter = 'customer';
        $segment_value = 'customer';
        break;
    case 'report':
        $segment_prameter = 'report';
        $segment_value = 'report';
        break;


}
?>
<div class="menu_section">
    <h3>General</h3>
    <ul class="nav side-menu">


        <li @if($segments=='dashboard') class="current-page" @endif><a href="{{url("dispensary/dashboard")}}"><i class="fa fa-home"></i> Dashboard</a>

        </li>
        @if(Auth::user()->hasPermission('view.products') || Auth::user()->isSuperadmin() || (Auth::user()->userInformation->user_type=='2'))
        <li @if($segments=='product') class="current-page" @endif><a href="{{url('dispencery/product/list')}}"><i class="fa fa-shopping-bag"></i> My Products</a>
        </li>
        @endif
        @if(Auth::user()->hasPermission('view.products') || Auth::user()->isSuperadmin() || (Auth::user()->userInformation->user_type=='2'))
            <li @if($segments=='customer') class="current-page" @endif><a href="{{url('/dispencery/customer/list')}}"><i class="fa fa-users"></i> My Customers</a>
            </li>
        @endif
        @if(Auth::user()->hasPermission('view.products') || Auth::user()->isSuperadmin() || (Auth::user()->userInformation->user_type=='2'))
            <li @if($segments=='report') class="current-page" @endif><a href="{{url('/dispensary/report/list')}}"><i class="fa fa-bar-chart"></i> Report</a>
            </li>
        @endif
        @if(Auth::user()->hasPermission('view.products') || Auth::user()->isSuperadmin() || (Auth::user()->userInformation->user_type=='2'))
            <li @if($segments=='report') class="current-page" @endif><a href="{{url('/dispensary/feedback/list')}}"><i class="fa fa-envelope"></i> Feedback's</a>
            </li>
        @endif
    </ul>
</div>
