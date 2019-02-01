<!DOCTYPE html>

<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8"/>

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport"/>

        <meta content="{{ csrf_token() }}" name="_token">

        @yield("meta")

        <link href="{{url('public/media/backend/css/bootstrap-latest.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{url('public/media/backend/css/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{url('public/media/backend/css/nprogress/nprogress.css')}}" rel="stylesheet" type="text/css">
        <link href="{{url('public/media/backend/css/animate.css/animate.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{url('public/media/backend/css/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet" type="text/css">

        <script src="{{url('public/media/backend/js/jquery.min.js')}}" type="text/javascript"></script>
        <script src="{{url('public/media/backend/js/jquery-v2.1.3.js')}}" type="text/javascript"></script>
        <link href="{{url('public/media/backend/css/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{url('public/media/backend/css/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{url('public/media/backend/css/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{url('public/media/backend/css/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{url('public/media/backend/css/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{url('public/media/backend/css/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
        <link href="{{url('public/media/backend/css/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
        <link href="{{url('public/media/front/js/foundation-datepicker-master/css/foundation-datepicker.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{url('public/media/backend/js/rangeslider.js-2.3.0/rangeslider.js-2.3.0/rangeslider.css')}}" rel="stylesheet">

        <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />

        <!-- Include Date Range Picker -->
        <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
        
        
        <!-- END THEME STYLES -->
        <link href="{{url('public/media/backend/css/custom.min.css')}}" rel="stylesheet">
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        <div class="clearfix"></div>
                        <div class="profile clearfix">
                            <div class="profile_pic">
                                
                                
                                @if(Auth::user()->userInformation->profile_picture!='')
                                    <img src="{{url('/storage/app/public/dispencery/'.Auth::user()->userInformation->profile_picture)}}" class="img-circle profile_img"/>
                                @else
                                <img src="{{url('public/media/backend/images/user-admin-default.png')}}" class="img-circle profile_img"/>
                                @endif
                            </div>
                            <div class="profile_info">
                                <span>Welcome,</span>
                                <h2>{{ Auth::user()->userInformation->first_name }} </h2>
                            </div>
                        </div>
                        <br />
                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                            @include('layouts.vendor-left')
                        </div>
                        <!-- /menu footer buttons -->
                    </div>
                    
                </div>
                <div class="top_nav">
                    <div class="nav_menu">
                        <nav>
                            <div class="nav toggle">
                                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                            </div>

                            
                          
                            <ul class="nav navbar-nav navbar-right">
                                <li class="">
                                    
                                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        {{ Auth::user()->userInformation->first_name." ".Auth::user()->userInformation->last_name }}
                                        <span class=" fa fa-angle-down"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                                        
                                        <li>
                                            <a href="{{url('dispensary/profile')}}">
                                                <i class="icon-user"></i> My Profile </a>
                                        </li>
                                        
                                        <li>
                                            <a href="{{url('dispencery/logout')}}">
                                                <i class="fa fa-sign-out pull-right"></i> Log Out </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <div class="right_col" role="main">
                    <div>@yield("content")</div>
                </div>
                <footer>
                    <div class="pull-right">
                        &copy; <?php echo date('Y'); ?> CliqueMJ. All right reserved.
                    </div>
                    <div class="clearfix"></div>
                </footer>
            </div>
        </div>



        <script src="{{url('public/media/backend/js/bootstrap.min.js')}}" type="text/javascript"></script>
        <script src="{{url('public/media/backend/js/bootstrap-hover-dropdown.min.js')}}" type="text/javascript"></script>
        <script src="{{url('public/media/backend/css/fastclick/lib/fastclick.js')}}"></script>
        <!-- NProgress -->
        <script src="{{url('public/media/backend/css/nprogress/nprogress.js')}}"></script>
        <script src="{{url('public/media/backend/css/bootstrap-progressbar/bootstrap-progressbar.min.js')}}"></script>
        <script src="{{url('public/media/backend/css/DateJS/build/date.js')}}"></script>
        <!-- bootstrap-daterangepicker -->
        <script src="{{url('public/media/backend/css/moment/min/moment.min.js')}}"></script>
        <script src="{{url('public/media/backend/css/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
        <script src="{{url('public/media/backend/css/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
        <script src="{{url('public/media/backend/js/jquery.autocomplete.min.js')}}"></script>

        <!-- Custom Theme Scripts -->
        <script src="{{url('public/media/backend/js/custom.min.js')}}"></script>

        <script type="text/javascript" src="{{url('public/media/backend/js/datatable/select2.min.js')}}"></script>
        <script type="text/javascript" src="{{url('public/media/backend/js/datatable/jquery.dataTables.min.js')}}"></script>
        <script type="text/javascript" src="{{url('public/media/backend/js/datatable/dataTables.bootstrap.js')}}"></script>
        <script type="text/javascript" src="{{url('public/media/backend/js/datatable/table-managed.js')}}"></script>
        <script type="text/javascript" src="{{url('public/media/backend/js/jquery.validate.js')}}"></script>
        <script type="text/javascript" src="{{url('public/media/backend/js/validation.js')}}"></script>
        <script type="text/javascript" src="{{url('public/media/backend/js/select-all-delete.js')}}"></script>
        <script type="text/javascript" src="{{url('public/media/front/js/foundation-datepicker-master/js/foundation-datepicker.js')}}"></script>
        <script type="text/javascript" src="{{url('public/media/backend/js/rangeslider.js-2.3.0/rangeslider.js-2.3.0/rangeslider.js')}}"></script>
        <script type="text/javascript" src="{{url('public/media/backend/js/rangeslider.js-2.3.0/rangeslider.js-2.3.0/rangeslider.min.js')}}"></script>
        
        
        <!-- END JAVASCRIPTS -->
    </body>
</html>
