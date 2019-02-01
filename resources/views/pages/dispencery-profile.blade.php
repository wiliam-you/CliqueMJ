@extends('layouts.vendor')

@section("meta")

<title>Dispensary Profile</title>

@endsection

@section('content')
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{url('dispencery/dashboard')}}">Dashboard</a>

            </li>
            <li>
                <a href="javascript:void(0)">Dispensary User Profile</a>
            </li>
        </ul>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="x_panel">
                    <div class="x_title"><h2> Update your profile information</h2>
                        <div class="clearfix"></div>
                    </div>


                    <div class="x_content">
                            <ul class="nav nav-tabs">
                                <li class="@if(!($errors->has('email') || $errors->has('confirm_email')|| $errors->has('logo_picture')|| $errors->has('current_password')|| $errors->has('new_password') || $errors->has('confirm_password') || session('password-update-fail'))) active @endif">
                                    <a href="#tab_1_1" data-toggle="tab">Personal Information</a>
                                </li>
                                <li class="@if($errors->has('email') || $errors->has('confirm_email')) active @endif">
                                    <a href="#tab_1_3" data-toggle="tab">Change Email</a>
                                </li>
                                <li class="@if($errors->has('current_password')|| $errors->has('new_password') || $errors->has('confirm_password') || session('password-update-fail')!='') active @endif">
                                    <a href="#tab_1_2" data-toggle="tab">Change Password</a>
                                </li>


                            </ul>

                        @if (session('profile-updated'))
                        <div class="alert alert-success">
                            {{ session('profile-updated') }}
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        </div>
                        @endif
                        @if (session('password-update-fail'))
                        <div class="alert alert-danger">
                            {{ session('password-update-fail') }}
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        </div>
                        @endif
                        <div style="padding: 10px;">
                            <div class="tab-content">
                                 <!--PERSONAL INFO TAB--> 
                                <div class="tab-pane @if(!($errors->has('email') || $errors->has('confirm_email')|| $errors->has('profile_picture')|| $errors->has('current_password')|| $errors->has('new_password') || $errors->has('confirm_password') || session('password-update-fail'))) active @endif" id="tab_1_1">
                                    <form name="update_profile"  id="update_profile" role="form" method="POST" action="{{ url('/dispencery/update-profile-post') }}" enctype="multipart/form-data">
                                        {!! csrf_field() !!}
                                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                            <label class="control-label">First Name <sup style='color:red;'>*</sup></label>

                                            <input type="text" class="form-control" name="first_name" value="{{old('first_name',$user_info->userInformation->first_name)}}">
                                            @if ($errors->has('first_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('first_name') }}</strong>
                                            </span>
                                            @endif

                                        </div>

                                        <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                            <label class="control-label">Last Name <sup style='color:red;'>*</sup></label>

                                            <input type="text" class="form-control" name="last_name" value="{{old('last_name',$user_info->userInformation->last_name)}}">
                                            @if ($errors->has('last_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('last_name') }}</strong>
                                            </span>
                                            @endif

                                        </div>
                                        <div class="form-group {{ $errors->has('state') ? ' has-error' : '' }}">
                                            <label class="control-label">State:<sup>*</sup></label>

                                            <select required type="text" class="form-control" id="state" name="state" onchange="getCities()">
                                                @foreach($states as $state)
                                                    <option value="{{$state->id}}" @if(old('state',$user_info->userInformation->state_id)==$state->id) selected @endif>{{$state->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('state'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                            @endif

                                        </div>
                                        <div class="form-group {{ $errors->has('city') ? ' has-error' : '' }}">
                                            <label class="control-label">City:<sup>*</sup></label>

                                            <select required type="text" class="form-control" id="city" name="city">
                                                @foreach($cities as $city)
                                                    <option value="{{$city->id}}" @if(old('city',$user_info->userInformation->city_id)==$city->id) selected @endif>{{$city->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('city'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                            @endif

                                        </div>
                                        <div class="form-group {{ $errors->has('post_code') ? ' has-error' : '' }}">
                                            <label class="control-label">Post Code:<sup>*</sup></label>
                                            <input required type="text" class="form-control" id="post_code" name="post_code" value="{{old('post_code',$user_info->userInformation->post_code)}}">
                                            @if ($errors->has('post_code'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('post_code') }}</strong>
                                                 </span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('photo') ? ' has-error' : '' }}">
                                            <label class="control-label">Store Logo</label>

                                            <input type="file" class="form-control" name="photo" accept="image/*">
                                            @if ($errors->has('photo'))
                                                <span class="help-block">
                                                <strong>{{ $errors->first('photo') }}</strong>
                                            </span>
                                            @endif

                                        </div>
                                        @if($user_info->userInformation->profile_picture!='')
                                            <div class="form-group{{ $errors->has('photo') ? ' has-error' : '' }}">
                                                <img width="100px" src="{{url('/storage/app/public/dispencery/'.$user_info->userInformation->profile_picture)}}" alt="Patient image">
                                            </div>
                                        @endif
                                        <div class="form-group{{ $errors->has('user_mobile') ? ' has-error' : '' }}">
                                            <label class="control-label">Contact Number</label>

                                            <input type="text" class="form-control" name="user_mobile" value="{{old('user_mobile',$user_info->userInformation->user_mobile)}}">
                                            @if ($errors->has('user_mobile'))
                                                <span class="help-block">
                                                <strong>{{ $errors->first('user_mobile') }}</strong>
                                            </span>
                                            @endif

                                        </div>
                                        <div class="form-group {{ $errors->has('user_birth_date') ? ' has-error' : '' }}">
                                            <label class="control-label">Date of birth:</label>

                                            <input required class="form-control" id="user_birth_date" name="user_birth_date" value="{{old('user_birth_date',$user_info->userInformation->user_birth_date)}}">
                                            @if ($errors->has('user_birth_date'))
                                                <span class="help-block">
                                    <strong>{{ $errors->first('user_birth_date') }}</strong>
                                 </span>
                                            @endif
                                        </div>

                                        <div class="form-group {{ $errors->has('store_time') ? ' has-error' : '' }}">
                                            <label class="control-label">Store_time:</label>
                                            <div class="">
                                                <div class="row">
                                                    <label class="control-label">Opening Time:</label>
                                                    <select class="form-control" name="opening_hour" value="{{old('opening_hour')}}">
                                                        @for($i=1;$i<=12;$i++)
                                                            <option value="{{$i}}" @if(old('opening_hour',$user_info->userInformation->opening_hour)==$i) selected @endif>{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                    <input required class="form-control" type="number" name="opening_minut" placeholder="minut" value="{{$user_info->userInformation->opening_minut}}">
                                                    <select class="form-control" name="opening">
                                                        <option value="am" @if(old('opening',$user_info->userInformation->opening)=='am') selected @endif>am</option>
                                                        <option value="pm" @if(old('opening',$user_info->userInformation->opening)=='pm') selected @endif>pm</option>
                                                    </select>
                                                </div>

                                                <div class="row">
                                                    <label class="control-label">Closing Time:</label>
                                                    <select class="form-control" name="closing_hour">
                                                        @for($i=1;$i<=12;$i++)
                                                            <option value="{{$i}}" @if(old('closing_hour',$user_info->userInformation->closing_hour)==$i) selected @endif>{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                    <input required class="form-control" type="number" name="closing_minut" placeholder="minut" value="{{$user_info->userInformation->closing_minut}}">
                                                    <select class="form-control" name="closing">
                                                        <option value="am" @if(old('closing',$user_info->userInformation->closing)=='am') selected @endif>am</option>
                                                        <option value="pm" @if(old('closing',$user_info->userInformation->closing)=='pm') selected @endif>pm</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Address:</label>

                                            <textarea name="address" class="form-control" id="searchTextField" type="text" size="50" style="text-align: left;width:357px;direction: ltr;">{{old('address',$user_info->userInformation->address)}}</textarea>
                                            @if ($errors->has('Latitude'))
                                                <span class="help-block">
                                    <strong>Please select location</strong>
                                 </span>
                                            @endif

                                            <input id="lat" name="latitude" class="MapLat" value="{{old('latitude',$user_info->userInformation->lat)}}" type="hidden" placeholder="Latitude" style="width: 161px;">
                                            <input id="long" name="longitude" class="MapLon" value="{{old('longitude',$user_info->userInformation->long)}}" type="hidden" placeholder="Longitude" style="width: 161px;">

                                            <div id="map_canvas" class="form-group" style="height: 350px;width: 100%;margin: 0.6em;"></div>
                                        </div>
                                        <div class="margiv-top-10">
                                            <input type="submit" class="btn btn-primary" value="Save Changes">
                                            <a href="{{url('vendor/dashboard')}}" class="btn default">
                                                Cancel 
                                            </a>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane @if($errors->has('email') || $errors->has('confirm_email'))  active @endif" id="tab_1_3">
                                    <form name="update_email"  id="update_email" role="form" method="POST" action="{{ url('/dispencery/update-vendor-email') }}">
                                        {!! csrf_field() !!}
                                        <div class="form-group">
                                            <label class="control-label">Current Email: </label>
                                            <label class="control-label">{{$user_info->email}}</label>
                                        </div>
                                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                            <label class="control-label">New Email</label>

                                            <input type="text" class="form-control" id="email" name="email" value="{{old('email')}}">
                                            @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                            @endif

                                        </div>
                                        <div class="form-group{{ $errors->has('confirm_email') ? ' has-error' : '' }}">
                                            <label class="control-label">Confirm Email</label>
                                            <input type="text" class="form-control" id="confirm_email" name="confirm_email" value="{{old('confirm_email')}}">
                                            @if ($errors->has('confirm_email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('confirm_email') }}</strong>
                                            </span>
                                            @endif

                                        </div>
                                        <div class="margiv-top-10">
                                            <input type="submit" class="btn btn-primary" value="Change Email">
                                            <a href="{{url('vendor/dashboard')}}" class="btn default">
                                                Cancel 
                                            </a>
                                        </div>
                                    </form>
                                </div>
                                 <!--CHANGE PASSWORD TAB--> 
                                <div class="tab-pane @if($errors->has('current_password')|| $errors->has('new_password') || $errors->has('confirm_password') || session('password-update-fail')) active @endif" id="tab_1_2">
                                    <form name="update_password"  id="update_password" role="form" method="POST" action="{{ url('/dispencery/change-password-post') }}">
                                        {!! csrf_field() !!}

                                        <div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
                                            <label class="control-label">Current Password</label>

                                            <input type="password" class="form-control" id="current_password" name="current_password" value="{{old('current_password')}}">
                                            @if ($errors->has('current_password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('current_password') }}</strong>
                                            </span>
                                            @endif

                                        </div>
                                        <div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
                                            <label class="control-label">New Password</label>

                                            <input type="password" class="form-control" id="new_password" name="new_password" value="{{old('new_password')}}">
                                            @if ($errors->has('new_password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('new_password') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('confirm_password') ? ' has-error' : '' }}">
                                            <label class="control-label">Confirm Password</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="{{old('confirm_password')}}">
                                            @if ($errors->has('confirm_password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('confirm_password') }}</strong>
                                            </span>
                                            @endif

                                        </div>
                                        <div class="margiv-top-10">
                                            <input type="submit" class="btn btn-primary" value="Change Password">
                                            <a href="{{url('vendor/dashboard')}}" class="btn default">
                                                Cancel 
                                            </a>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane @if($errors->has('logo_picture')) active @endif" id="tab_1_4">
                                    <form name="update_logo_picture"  id="update_profile_picture" role="form" method="POST" action="{{ url('vendor/change-logo') }}" enctype="multipart/form-data">
                                        {!! csrf_field() !!}

                                        <div class="form-group{{ $errors->has('logo_picture') ? ' has-error' : '' }}">
                                            <label class="control-label">Store logo</label>

                                            <input type="file" class="form-control" id="current_password" name="logo_picture" value="{{old('logo_picture')}}" accept="image/*">
                                            @if ($errors->has('profile_picture'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('logo_picture') }}</strong>
                                            </span>
                                            @endif

                                        </div>
                                        <div class="margiv-top-10">
                                            <input type="submit" class="btn btn-primary" value="Change Logo">
                                            <a href="{{url('vendor/dashboard')}}" class="btn default">
                                                Cancel 
                                            </a>
                                        </div>
                                    </form>
                                        </div>

                                <!-- END CHANGE PASSWORD TAB -->
                                <!-- PRIVACY SETTINGS TAB -->

                                <!-- END PRIVACY SETTINGS TAB -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // This example requires the Places library. Include the libraries=places
            // parameter when you first load the API. For example:
            // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
            $(document).ready(function(){
//        getCities();
            })

            var nowTemp = new Date();
            $(function() {
                $('input[name="user_birth_date"]').daterangepicker({
                    startDate:$('#user_birth_date').val(),
                    singleDatePicker: true,
                    showDropdowns: true,
                    maxDate: nowTemp
                });
            });

            function getCities()
            {
                if(state!='' && state!=0)
                {
                    var state = $('#state').val();
                    $.ajax({
                        url:"{{url('/admin/cities/getAllCities')}}/"+state,
                        method:'get',
                        success:function(data)
                        {

                            $("#city").html(data);

                        }

                    });
                }
            }

            function initMap() {
                var map = new google.maps.Map(document.getElementById('map_canvas'), {
                    center: {lat: {{$user_info->userInformation->lat}}, lng: {{$user_info->userInformation->long}}},
                    zoom: 13
                });
                var card = document.getElementById('pac-card');
                var input = document.getElementById('searchTextField');
                var types = document.getElementById('type-selector');
                var strictBounds = document.getElementById('strict-bounds-selector');

                map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

                var autocomplete = new google.maps.places.Autocomplete(input);

                // Bind the map's bounds (viewport) property to the autocomplete object,
                // so that the autocomplete requests use the current map bounds for the
                // bounds option in the request.
                autocomplete.bindTo('bounds', map);

                var infowindow = new google.maps.InfoWindow();
                var infowindowContent = document.getElementById('infowindow-content');
                infowindow.setContent(infowindowContent);
                var marker = new google.maps.Marker({
                    map: map,
                    anchorPoint: new google.maps.Point(0, -29)
                });

                autocomplete.addListener('place_changed', function() {
                    infowindow.close();
                    marker.setVisible(false);
                    var place = autocomplete.getPlace();
                    if (!place.geometry) {
                        // User entered the name of a Place that was not suggested and
                        // pressed the Enter key, or the Place Details request failed.
                        window.alert("No details available for input: '" + place.name + "'");
                        return;
                    }

                    // If the place has a geometry, then present it on a map.
                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(17);  // Why 17? Because it looks good.
                    }
                    marker.setPosition(place.geometry.location);
                    marker.setVisible(true);

                    var address = '';
                    if (place.address_components) {
                        address = [
                            (place.address_components[0] && place.address_components[0].short_name || ''),
                            (place.address_components[1] && place.address_components[1].short_name || ''),
                            (place.address_components[2] && place.address_components[2].short_name || '')
                        ].join(' ');
                    }

                    $('#lat').val(place.geometry.location.lat());
                    $('#long').val(place.geometry.location.lng());

                    infowindowContent.children['place-icon'].src = place.icon;
                    infowindowContent.children['place-name'].textContent = place.name;
                    infowindowContent.children['place-address'].textContent = address;
                    infowindow.open(map, marker);
                });

                // Sets a listener on a radio button to change the filter type on Places
                // Autocomplete.
                function setupClickListener(id, types) {
                    var radioButton = document.getElementById(id);
                    radioButton.addEventListener('click', function() {
                        autocomplete.setTypes(types);
                    });
                }

                setupClickListener('changetype-all', []);
                setupClickListener('changetype-address', ['address']);
                setupClickListener('changetype-establishment', ['establishment']);
                setupClickListener('changetype-geocode', ['geocode']);

                document.getElementById('use-strict-bounds')
                    .addEventListener('click', function() {
                        console.log('Checkbox clicked! New state=' + this.checked);
                        autocomplete.setOptions({strictBounds: this.checked});
                    });
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTJvBo3xYQU9t6Vlmw4rS7WO7TUyt_h9o&libraries=places&callback=initMap"
                async defer></script>
@endsection
