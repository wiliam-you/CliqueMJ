@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

    <title>Create dispensary user</title>

    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 100%;
        }
        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        #description {
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
        }

        #infowindow-content .title {
            font-weight: bold;
        }

        #infowindow-content {
            display: none;
        }

        #map #infowindow-content {
            display: inline;
        }

        .pac-card {
            margin: 10px 10px 0 0;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            background-color: #fff;
            font-family: Roboto;
        }

        #pac-container {
            padding-bottom: 12px;
            margin-right: 12px;
        }

        .pac-controls {
            display: inline-block;
            padding: 5px 11px;
        }

        .pac-controls label {
            font-family: Roboto;
            font-size: 13px;
            font-weight: 300;
        }

        #pac-input {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 400px;
        }

        #pac-input:focus {
            border-color: #4d90fe;
        }

        #title {
            color: #fff;
            background-color: #4d90fe;
            font-size: 25px;
            font-weight: 500;
            padding: 6px 12px;
        }
    </style>
@endsection

@section('content')

    <div class="page-content-wrapper">
        <div class="page-content">
            <!-- BEGIN PAGE BREADCRUMB -->
            <ul class="page-breadcrumb breadcrumb">
                <li>
                    <a href="{{url('admin/dashboard')}}">Dashboard</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="{{url('admin/manage-dispensary-user')}}">Manage Dispensary Users</a>
                    <i class="fa fa-circle"></i>

                </li>
                <li>
                    <a href="javascript:void(0)">Create New Dispensary User</a>

                </li>
            </ul>

            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gift"></i> Create dispensary user
                    </div>

                </div>
                <div class="portlet-body form">
                    <form role="form" class="form-horizontal"  method="post" enctype="multipart/form-data">
                        <input type='hidden' name='user_type' id='user_type' value='2'>
                        {!! csrf_field() !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-8">
                                        <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
                                            <label class="col-md-6 control-label">First Name:<sup>*</sup></label>

                                            <div class="col-md-6">
                                                <input required name="first_name" type="text" class="form-control" id="first_name" value="{{old('first_name')}}">
                                                @if ($errors->has('first_name'))
                                                    <span class="help-block">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                                                @endif
                                            </div>

                                        </div>

                                        <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
                                            <label class="col-md-6 control-label">Last Name:<sup>*</sup></label>
                                            <div class="col-md-6">
                                                <input required type="text" class="form-control" id="last_name" name="last_name" value="{{old('last_name')}}">

                                                @if ($errors->has('last_name'))
                                                    <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group {{ $errors->has('dispensary_name') ? ' has-error' : '' }}">
                                            <label class="col-md-6 control-label">Dispensary Name:<sup>*</sup></label>
                                            <div class="col-md-6">
                                                <input required type="text" class="form-control" name="dispensary_name" value="{{old('dispensary_name')}}">

                                                @if ($errors->has('dispensary_name'))
                                                    <span class="help-block">
                                        <strong>{{ $errors->first('dispensary_name') }}</strong>
                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group {{ $errors->has('state') ? ' has-error' : '' }}">
                                            <label class="col-md-6 control-label">State:<sup>*</sup></label>
                                            <div class="col-md-6">
                                                <select required type="text" class="form-control" id="state" name="state" onchange="getCities()">
                                                    @foreach($states as $state)
                                                        <option value="{{$state->id}}" @if(old('state')==$state->id) selected @endif>{{$state->name}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('state'))
                                                    <span class="help-block">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group {{ $errors->has('city') ? ' has-error' : '' }}">
                                            <label class="col-md-6 control-label">City:<sup>*</sup></label>
                                            <div class="col-md-6">
                                                <select required type="text" class="form-control" id="city" name="city">

                                                </select>
                                                @if ($errors->has('city'))
                                                    <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group {{ $errors->has('post_code') ? ' has-error' : '' }}">
                                            <label class="col-md-6 control-label">Zip Code:<sup>*</sup></label>
                                            <div class="col-md-6">
                                                <input required type="text" class="form-control" id="post_code" name="post_code" value="{{old('post_code')}}">
                                                @if ($errors->has('post_code'))
                                                    <span class="help-block">
                                    <strong>{{ $errors->first('post_code') }}</strong>
                                 </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                            <label class="col-md-6 control-label">Email:<sup>*</sup></label>
                                            <div class="col-md-6">
                                                <input required type="email" class="form-control" id="email" name="email" value="{{old('email')}}">
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                 </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                            <label class="col-md-6 control-label">Password:<sup>*</sup></label>
                                            <div class="col-md-6">
                                                <input required type="Password" class="form-control" id="password" name="password" value="{{old('password')}}">
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                 </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                            <label class="col-md-6 control-label">Confirm Password:<sup>*</sup></label>
                                            <div class="col-md-6">
                                                <input required type="Password" class="form-control" id="password_confirmation" name="password_confirmation" value="{{old('password_confirmation')}}">
                                                @if ($errors->has('password_confirmation'))
                                                    <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                 </span>
                                                @endif
                                            </div>
                                        </div>
                                        @if ($errors->has('gender'))
                                            <span class="help-block">
                            <strong>{{ $errors->first('gender') }}</strong>
                        </span>
                                        @endif

                                        <div class="form-group {{ $errors->has('user_mobile') ? ' has-error' : '' }}">
                                            <label class="col-md-6 control-label">Mobile<sup>*</sup>:</label>
                                            <div class="col-md-6">
                                                <input required type="number" class="form-control" id="user_mobile" name="user_mobile" value="{{old('user_mobile')}}">
                                                @if ($errors->has('user_mobile'))
                                                    <span class="help-block">
                                    <strong>{{ $errors->first('user_mobile') }}</strong>
                                 </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group {{ $errors->has('user_birth_date')? ' has-error' : '' }}">
                                            <label class="col-md-6 control-label">Date of birth<sup>*</sup>:</label>
                                            <div class="col-md-6">
                                                <input required class="form-control" id="user_birth_date" name="user_birth_date" value="{{old('user_birth_date')}}">
                                                @if ($errors->has('user_birth_date'))
                                                    <span class="help-block">
                                    <strong>{{ $errors->first('user_birth_date') }}</strong>
                                 </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('photo')) has-error @endif">
                                            <label class="col-md-6 control-label">Logo<sup>*</sup></label>
                                            <div class="col-md-6">
                                                <input required class="form-control"  type="file" name="photo" accept="image/*">
                                                @if ($errors->has('photo'))
                                                    <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('photo') }}</strong>
                                </span>
                                                @endif

                                            </div>

                                        </div>
                                        <div class="form-group {{ $errors->has('store_time') ? ' has-error' : '' }}">
                                            <label class="col-md-6 control-label">Store_time<sup>*</sup>:</label>
                                            <div class="col-md-6">
                                                <div class="row col-md-6">
                                                    <label class="col-md-6 control-label">Opening Time:</label>
                                                    <select class="form-control" name="opening_hour" value="{{old('opening_hour')}}">
                                                        @for($i=1;$i<=12;$i++)
                                                            <option value="{{$i}}" @if(old('opening_hour')==$i) selected @endif>{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                    <input  class="form-control" type="number" name="opening_minut" placeholder="minut" min="0" value="00">
                                                    <select class="form-control" name="opening">
                                                        <option value="am" @if(old('opening')=='am') selected @endif>am</option>
                                                        <option value="pm" @if(old('opening')=='pm') selected @endif>pm</option>
                                                    </select>
                                                </div>

                                                <div class="row col-md-6">
                                                    <label class="col-md-6 control-label">Closing Time:</label>
                                                    <select class="form-control" name="closing_hour" value="{{old('closing_hour')}}">
                                                        @for($i=1;$i<=12;$i++)
                                                            <option value="{{$i}}" @if(old('closing_hour')==$i) selected @endif>{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                    <input  class="form-control" type="number" name="closing_minut" placeholder="minut" min="0" value="00">
                                                    <select class="form-control" name="closing">
                                                        <option value="am" @if(old('closing')=='am') selected @endif>am</option>
                                                        <option value="pm" @if(old('closing')=='pm') selected @endif>pm</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-6 control-label">Address<sup>*</sup>:</label>
                                            <div class="col-md-6">
                                                <textarea required class="form-control" name="address" id="searchTextField" type="text" size="50" style="text-align: left;width:357px;direction: ltr;">{{old('address')}}</textarea>
                                                @if ($errors->has('Latitude'))
                                                    <span class="help-block">
                                    <strong>Please select location</strong>
                                 </span>
                                                @endif
                                                <input name="latitude" id="lat" class="MapLat" value="{{old('latitude')}}" type="hidden" placeholder="Latitude" style="width: 161px;">
                                                <input name="longitude" id="long" class="MapLon" value="{{old('longitude')}}" type="hidden" placeholder="Longitude" style="width: 161px;">
                                            </div>
                                        </div>

                                    </div>
                                    <div id="map_canvas" style="height: 350px;width: 100%;margin: 0.6em;"></div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <button type="submit" id="submit" class="btn btn-primary  pull-right">Create</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>

                </form>
            </div>
        </div>
    </div>
    </div>

    <script>
        // This example requires the Places library. Include the libraries=places
        // parameter when you first load the API. For example:
        $(document).ready(function(){
            getCities();
        })

        var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

        $(function() {
            $('input[name="user_birth_date"]').daterangepicker({
                startDate:nowTemp,
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
                center: {lat: -33.8688, lng: 151.2195},
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
                console.log(place.geometry.location);
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