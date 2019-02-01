<?php
$segments=Request::segment(2);
$segment_prameter='';
$segment_value='';
switch ($segments) {
    case 'manage-roles':
        $segment_prameter = 'role';
        $segment_value = 'global';
        break;

    case 'update-role':
        $segment_prameter = 'role';
        $segment_value = 'global';
        break;

    case 'roles':
        $segment_prameter = 'role';
        $segment_value = 'global';
        break;

    case 'global-settings':
        $segment_prameter = 'globalsetting';
        $segment_value = 'global';
        break;

    case 'update-global-setting':
        $segment_prameter = 'globalsetting';
        $segment_value = 'global';
        break;

    case 'countries':
        $segment_prameter = 'countries';
        $segment_value = 'global';
        break;

    case 'countries':
        $segment_prameter = 'countries';
        $segment_value = 'global';
        break;

    case 'states':
        $segment_prameter = 'states';
        $segment_value = 'global';
        break;

    case 'cities':
        $segment_prameter = 'cities';
        $segment_value = 'global';
        break;

    case 'admin-users':
        $segment_prameter = 'admin-users';
        $segment_value = 'user';
        break;
    case 'manage-patient':
        $segment_prameter = 'manage-patient';
        $segment_value = 'user';
        break;

    case 'manage-dispencery-user':
        $segment_prameter = 'manage-dispencery-user';
        $segment_value = 'user';
        break;

    case 'create-user':
        $segment_prameter = 'admin-users';
        $segment_value = 'user';
        break;

    case 'update-admin-user':
        $segment_prameter = 'admin-users';
        $segment_value = 'user';
        break;

    case 'update-registered-user':
        $segment_prameter = 'register-user';
        $segment_value = 'user';
        break;

    case 'create-user':
        $segment_prameter = 'admin-user';
        $segment_value = 'user';
        break;
    case 'update-admin-user':
        $segment_prameter = 'admin-user';
        $segment_value = 'user';
        break;

    case 'dispensary-users':
        $segment_prameter = 'dispensary-users';
        $segment_value = 'user';
        break;
    case 'update-registered-user':
        $segment_prameter = 'register-user';
        $segment_value = 'user';
        break;
    case 'create-registered-user':
        $segment_prameter = 'register-user';
        $segment_value = 'user';
        break;

    case 'content-pages':
        $segment_prameter = 'content-pages';
        $segment_value = 'cms';
        break;

    case 'email-templates':
        $segment_prameter = 'email-template';
        $segment_value = 'email';
        break;

    case 'categories-list':
        $segment_prameter = 'category';
        $segment_value = 'category';
        break;

    case 'category':
        $segment_prameter = 'category';
        $segment_value = 'category';
        break;

    case 'contact-request-categories':
        $segment_prameter = 'contact-request-categories';
        $segment_value = 'contact';
        break;

    case 'contact-request-category':
        $segment_prameter = 'contact-request-categories';
        $segment_value = 'contact';
        break;


    case 'contact-requests':
        $segment_prameter = 'contact';
        $segment_value = 'contact';
        break;

    case 'faq-categories':
        $segment_value = 'faq';
        $segment_prameter = 'faq-categories';
        break;
    case 'faq-category':
        $segment_value = 'faq';
        $segment_prameter = 'faq-categories';
        break;

    case 'faqs':
        $segment_prameter = 'faq';
        $segment_value = 'faq';
        $segment_prameter = 'faqs';
        break;

    case 'faq':
        $segment_value = 'faq';
        $segment_prameter = 'faqs';
        break;

    case 'blog-categories':
        $segment_prameter = 'blog-category';
        $segment_value = 'blog';
        $segment_prameter = 'blog-categories';
        break;

    case 'blog-category':
        $segment_value = 'blog';
        $segment_prameter = 'blog-categories';
        break;

    case 'blog':
        $segment_prameter = 'blog';
        $segment_value = 'blog';
        break;


    case 'blog-post':
        $segment_prameter = 'blog';
        $segment_value = 'blog';
        break;


    case 'testimonials':
        $segment_value = 'testimonial';
        break;

    case 'property':
        $segment_value = 'property';
        $segment_prameter='property';
        break;

    case 'newsletters':
        $segment_value = 'newsletters';
        break;

    case 'product':
        $segment_value = 'product';
        $segment_prameter='product';
        break;

    case 'property':
        $segment_value = 'property';
        break;

    case 'feedback':
        $segment_prameter = 'feedback';
        break;

    case 'dispencary-section':
        $segment_prameter = 'dispencary-section';
        break;

    case 'advertise-section':
        $segment_prameter = 'advertise-section';
        break;

    case 'get-started-section':
        $segment_prameter = 'get-started-section';
        break;

    case 'screen-shot-section':
        $segment_prameter = 'screen-shot-section';
        break;

    case 'zone':
        $segment_value = 'zone';
        $segment_prameter='zone';
        break;

    case 'coupon':
        $segment_value = 'coupon';
        $segment_prameter='coupon';
        break;

    case 'sale-report':
        $segment_value = 'report';
        $segment_prameter='sale-report';
        break;

    case 'patient-report':
        $segment_value = 'report';
        $segment_prameter='patient-report';
        break;

    case 'coupon-report':
        $segment_value = 'report';
        $segment_prameter='coupon-report';
        break;

    case 'share-report':
        $segment_value = 'report';
        $segment_prameter='share-report';
        break;

    case 'advertisement-report':
        $segment_value = 'report';
        $segment_prameter='advertisement-report';
        break;

    case 'advertisement':
        $segment_value = 'advertisement';
        $segment_prameter='advertisement';
        break;
    case 'genericmessages':
        $segment_value = 'genericmessages';
        $segment_prameter='genericmessages';
        break;

    case 'advertisementbrand':
        $segment_value = 'advertisementbrand';
        $segment_prameter='advertisementbrand';
        break;

}


?>
<div class="page-sidebar-wrapper">

    <div class="page-sidebar navbar-collapse collapse">

        <ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <li class="start active ">
                <a href="{{url("admin/dashboard")}}">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                </a>
            </li>

            <li  class="@if($segment_value=='global') open @endif">
                <a href="javascript:void(0);">
                    <i class="glyphicon glyphicon-cog"></i>
                    <span class="title">Manage Global Values</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu" @if($segment_value=='global') style='display:block' @endif>
                    @if(Auth::user()->hasPermission('view.roles')==true || Auth::user()->isSuperadmin())
                        <li class="@if($segment_prameter=='role') active @endif">
                            <a href="{{url('admin/manage-roles')}}">
                                <i class="glyphicon glyphicon-check"></i> Manage Roles {{ Route::getCurrentRoute()->getPrefix()}}
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->hasPermission('view.global-settings')==true || Auth::user()->isSuperadmin())
                        <li class="@if($segment_prameter=='globalsetting') active @endif">
                            <a href="{{url('admin/global-settings')}}">
                                <i class="glyphicon glyphicon-cog"></i> Manage Global Settings
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->hasPermission('view.manage-countries')==true || Auth::user()->isSuperadmin())
                        <li class="@if($segment_prameter=='states') active @endif">
                            <a href="{{url('admin/states/list')}}">
                                <i class="glyphicon glyphicon-globe"></i> Manage States
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->hasPermission('view.manage-cities')==true || Auth::user()->isSuperadmin())
                        <li class="@if($segment_prameter=='cities') active @endif">
                            <a href="{{url('admin/cities/list')}}">
                                <i class="glyphicon glyphicon-globe"></i> Manage Cities
                            </a>
                        </li>
                    @endif

                </ul>
            </li>
            <li  class="@if($segment_value=='user') open @endif">
                <a href="javascript:void(0);">
                    <i class="icon-user"></i>
                    <span class="title">Manage Users</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu" @if($segment_value=='user') style='display:block' @endif>
                    @if(Auth::user()->hasPermission('view.admin-users')==true || Auth::user()->isSuperadmin())

                        <li class="@if($segment_prameter=='admin-users') active @endif">
                            <a href="{{url('admin/admin-users')}}">
                                <i class="icon-user"></i> Manage Sub Users</a>
                        </li>
                    @endif

                    @if(Auth::user()->hasPermission('view.registered-users')==true || Auth::user()->isSuperadmin())

                        <li class="@if($segment_prameter=='manage-registered-user') active @endif">
                            <a href="{{url('admin/manage-dispensary-user')}}">
                                <i class="icon-user"></i> Manage Dispensary Users</a>
                        </li>
                    @endif


                    @if(Auth::user()->hasPermission('view.patient-users')==true || Auth::user()->isSuperadmin())

                        <li class="@if($segment_prameter=='manage-patient') active @endif">
                            <a href="{{url('/admin/manage-patient')}}">
                                <i class="icon-user"></i> Manage Patient Users</a>
                        </li>
                    @endif
                </ul>
            </li>

            <li  class="@if($segment_value=='report') open @endif">
                <a href="javascript:void(0);">
                    <i class="icon-bar-chart"></i>
                    <span class="title">Manage Report</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu" @if($segment_value=='report') style='display:block' @endif>
                    @if(Auth::user()->hasPermission('view.sale-report')==true || Auth::user()->isSuperadmin())
                        <li class="@if($segment_prameter=='sale-report') active @endif">
                            <a href="{{url('admin/sale-report/list')}}">
                                <i class="glyphicon glyphicon-globe"></i> Sales Report
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->hasPermission('view.patient-report')==true || Auth::user()->isSuperadmin())
                        <li class="@if($segment_prameter=='patient-report') active @endif">
                            <a href="{{url('admin/patient-report/list')}}">
                                <i class="glyphicon glyphicon-globe"></i> Patients Report
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->hasPermission('view.coupon-report')==true || Auth::user()->isSuperadmin())
                        <li class="@if($segment_prameter=='coupon-report') active @endif">
                            <a href="{{url('admin/coupon-report/list')}}">
                                <i class="glyphicon glyphicon-globe"></i> Coupons Report
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->hasPermission('view.share-report')==true || Auth::user()->isSuperadmin())
                        <li class="@if($segment_prameter=='share-report') active @endif">
                            <a href="{{url('admin/share-report/list')}}">
                                <i class="glyphicon glyphicon-globe"></i> Advertise Offer Shared Report
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->hasPermission('view.advertisement-report')==true || Auth::user()->isSuperadmin())
                        <li class="@if($segment_prameter=='advertisement-report') active @endif">
                            <a href="{{url('admin/advertisement-report/list')}}">
                                <i class="glyphicon glyphicon-globe"></i> Advertisement Offer Report
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->hasPermission('view.coupon-offer-view-report')==true || Auth::user()->isSuperadmin())
                        <li class="@if($segment_prameter=='coupon-offer-view') active @endif">
                            <a href="{{url('admin/coupon-offer-view')}}">
                                <i class="glyphicon glyphicon-globe"></i> Coupon And Offer View Report
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->hasPermission('view.global-offer-report')==true || Auth::user()->isSuperadmin())
                        <li class="@if($segment_prameter=='global-offer-report') active @endif">
                            <a href="{{url('admin/global-offer-report')}}">
                                <i class="glyphicon glyphicon-globe"></i> Global Offer Report
                            </a>
                        </li>
                    @endif

                </ul>
            </li>



            @if(Auth::user()->hasPermission('view.content-pages')==true || Auth::user()->isSuperadmin())

                <li class="@if($segment_prameter=='content-pages') active @endif">
                    <a href="{{url("admin/content-pages/list")}}">
                        <i class="icon-list"></i>
                        <span class="title">Manage CMS Pages</span>
                    </a>
                </li>
            @endif

            @if(Auth::user()->hasPermission('view.email-templates')==true || Auth::user()->isSuperadmin())

                <li class="@if($segment_prameter=='email-template') active @endif">
                    <a href="{{url("admin/email-templates/list")}}">
                        <i class="icon-list"></i>
                        <span class="title">Manage Email template</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->hasPermission('view.property')==true || Auth::user()->isSuperadmin())

                <li class="@if($segment_prameter=='property') active @endif">
                    <a href="{{url("admin/property/list")}}">
                        <i class="icon-list"></i>
                        <span class="title">Manage Product Property</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->hasPermission('view.product')==true || Auth::user()->isSuperadmin())
                <li class="@if($segment_prameter=='product') active @endif">
                    <a href="{{url("admin/product/list")}}">
                        <i class="icon-list"></i>
                        <span class="title">Manage Products</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->hasPermission('view.zone')==true || Auth::user()->isSuperadmin())
                <li class="@if($segment_prameter=='zone') active @endif">
                    <a href="{{url("admin/zone/list")}}">
                        <i class="icon-list"></i>
                        <span class="title">Manage Zones</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->hasPermission('view.advertisementbrand')==true || Auth::user()->isSuperadmin())

                <li class="@if($segment_prameter=='advertisementbrand') active @endif">
                    <a href="{{url("admin/advertisementbrand/list")}}">
                        <i class="icon-list"></i>
                        <span class="title">Manage Advertisement Brands</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->hasPermission('view.advertisement')==true || Auth::user()->isSuperadmin())

                <li class="@if($segment_prameter=='advertisement' && \Request::Segment(3) == 'list') active @endif">
                    <a href="{{url("admin/advertisement/list")}}">
                        <i class="icon-list"></i>
                        <span class="title">Manage Clique Offers</span>
                    </a>
                </li>
            @endif

            @if(Auth::user()->hasPermission('list.advertisement.global')==true || Auth::user()->isSuperadmin())
                <li  class="@if($segment_value=='advertisement'  && (\Request::Segment(3) == 'list-global-offer' || \Request::Segment(3) == 'create-global-offer')) open @endif">
                    <a href="javascript:void(0);">
                        <i class="icon-list"></i>
                        <span class="title">Manage Global Offers</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu" @if($segment_value=='advertisement') style='display:block' @endif>
                        @if(Auth::user()->hasPermission('list.advertisement.global') == true || Auth::user()->isSuperadmin())
                            <li class="@if($segment_prameter=='advertisement' && \Request::Segment(3) == 'list-global-offer') active @endif">
                                <a href="{{url('admin/advertisement/list-global-offer')}}">
                                    <i class="icon-list"></i> Global Clique Offers
                                </a>
                            </li>
                            <li class="@if($segment_prameter=='advertisement' && \Request::Segment(3) == 'create-global-offer') active @endif">
                                <a href="{{url("admin/advertisement/create-global-offer")}}">
                                    <i class="icon-list"></i>
                                    <span class="title">Create Global Offers</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if(Auth::user()->hasPermission('view.genericmessages')==true || Auth::user()->isSuperadmin())
                <li  class="@if($segment_value=='genericmessages') open @endif">
                    <a href="javascript:void(0);">
                        <i class="icon-list"></i>
                        <span class="title">Manage Generic Message</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu" @if($segment_value=='genericmessages') style='display:block' @endif>
                        @if(Auth::user()->hasPermission('view.genericmessages') == true || Auth::user()->isSuperadmin())
                            <li class="@if($segment_prameter=='genericmessages' && \Request::Segment(3) == 'list') active @endif">
                                <a href="{{url('admin/genericmessages/list')}}">
                                    <i class="icon-list"></i> Generic Messages
                                </a>
                            </li>
                            <li class="@if($segment_prameter=='genericmessages' && \Request::Segment(3) == 'create-generic-msg') active @endif">
                                <a href="{{url("admin/genericmessages/create-generic-msg")}}">
                                    <i class="icon-list"></i>
                                    <span class="title">Create Generic Message</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if(Auth::user()->hasPermission('view.coupon')==true || Auth::user()->isSuperadmin())
                <li class="@if($segment_prameter=='coupon') active @endif">
                    <a href="{{url("admin/coupon/cluster/list")}}">
                        <i class="icon-list"></i>
                        <span class="title">Manage MJ Offers</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->hasPermission('view.feedback')==true || Auth::user()->isSuperadmin())
                <li class="@if($segment_prameter=='feedback') active @endif">
                    <a href="{{url("admin/feedback/list")}}">
                        <i class="icon-list"></i>
                        <span class="title">Manage Feedbacks @if(Auth::user()->getCountOfNewFeedback()) <span class="badge badge-error">{{Auth::user()->getCountOfNewFeedback()}}</span> @endif</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->hasPermission('view.get-started')==true || Auth::user()->isSuperadmin())

                <li class="@if($segment_prameter=='get-started-section') active @endif">
                    <a href="{{url("admin/get-started-section/list")}}">
                        <i class="icon-list"></i>
                        <span class="title">Manage How It Works Section</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->hasPermission('view.feature')==true || Auth::user()->isSuperadmin())

                <li class="@if($segment_prameter=='feature') active @endif">
                    <a href="{{url("admin/feature/list")}}">
                        <i class="icon-list"></i>
                        <span class="title">Manage Features Section</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->hasPermission('view.chooseus')==true || Auth::user()->isSuperadmin())

                <li class="@if($segment_prameter=='chooseus') active @endif">
                    <a href="{{url("admin/chooseus/list")}}">
                        <i class="icon-list"></i>
                        <span class="title">Manage Why Choose Us Section</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->hasPermission('view.screenshot')==true || Auth::user()->isSuperadmin())

                <li class="@if($segment_prameter=='screen-shot-section') active @endif">
                    <a href="{{url("admin/screen-shot-section/list")}}">
                        <i class="icon-list"></i>
                        <span class="title">Manage Screen Shot Section</span>
                    </a>
                </li>
            @endif

        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
</div>