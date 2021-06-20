@extends('dashboard.layout.master')


<!-- style -->
@section('style')
	<style type = "text/css">

		.profile-img-container {
			padding: 4px;

			border-color: currentColor;
			width: 250px;
			height: 304px;
		}

		.profile-img-container {
			position: relative;
			display: inline-block; /* added */
			overflow: hidden; /* added */
		}
		.pac-container {
			z-index: 99999 !important;
		}

		/*
			   .profile-img-container img {width:100%;} !* remove if using in grid system *!
		*/

		.profile-img-container img:hover {
			opacity: 0.5
		}

		.profile-img-container:hover a {
			opacity: 1; /* added */
			top: 0; /* added */
			z-index: 500;
		}

		/* added */
		.profile-img-container:hover a span {
			top: 50%;
			position: absolute;
			left: 0;
			right: 0;
			transform: translateY(-50%);
		}

		/* added */
		.profile-img-container a {
			display: block;
			position: absolute;
			top: -100%;
			opacity: 0;
			left: 0;
			bottom: 0;
			right: 0;
			text-align: center;
			color: inherit;
		}

		.modal .icon-camera {
			font-size: 100px;
			color: #797979
		}

		.modal input {
			margin-bottom: 4px
		}

		.reset {
			border: none;
			background: #fff;
			margin-right: 11px;
		}

		.icon-trash {
			margin-left: 8px;
			color: red;
		}

		.dropdown-menu {
			min-width: 88px;
		}

		#hidden {
			display: none;
		}
	</style>
@endsection
<!-- /style -->
@section('content')
	<div class = "panel panel-flat">
		<div class = "panel-heading">
			<h5 class = "panel-title">الفروع</h5>

		</div>

		<!-- buttons -->
		<div class = "panel-body">
			<div class = "row">
				<div class = "col-xs-6">
					<button class = "btn bg-blue btn-block btn-float btn-float-lg openAddModal"
					         type = "button" data-toggle = "modal"
					        data-target = "#exampleModal"><i class = "icon-plus3"></i> <span>اضافة  فرع</span>
					</button>
				</div>
				<div class = "col-xs-6">
					<button class = "btn bg-purple-300 btn-block btn-float btn-float-lg" type = "button"><i
							class = "icon-list-numbered"></i> <span>عدد الفروع: {{count($branches)}} </span>
					</button>
				</div>

			</div>
		</div>
		<!-- /buttons -->

		<table class = "table datatable-basic" data-order = "[]">
			<thead>
			<tr>
				<th>الإسم العربى</th>
				<th>الإسم إنجليزى</th>
				<th>العنوان</th>
				<th>بداية الدوام</th>
				<th>انتهاء الدوام</th>
				<th>تاريخ الاضافه</th>
				<th>التحكم</th>
			</tr>
			</thead>
			<tbody>
			@foreach($branches as $p)
				<tr>
					<td>{{$p->name_ar}}</td>
					<td>{{$p->name_en}}</td>
					<td>{{$p->location}}</td>
					<td>{{\Carbon\Carbon::parse($p->start_at)->format('h:i A')}}</td>
					<td>{{\Carbon\Carbon::parse($p->end_at)->format('h:i A')}}</td>
					<td>{{$p->created_at->diffForHumans()}}</td>
					<td>
						<ul class = "icons-list">
							<li class = "dropdown">
								<a href = "#" class = "dropdown-toggle" data-toggle = "dropdown">
									<i class = "icon-menu9"></i>
								</a>

								<ul class = "dropdown-menu dropdown-menu-right">
									<!-- edit button -->
									<li>
										<a href = "#" data-toggle = "modal" data-target = "#exampleModal2"
										   class = "openEditmodal" onclick = "EditOb({{$p}})">
											<i class = "icon-pencil7"></i>تعديل
										</a>
									</li>


									<!-- delete button -->
									<form action = "{{route('delete_branch')}}" method = "POST">
										{{csrf_field()}}
										<input type = "hidden" name = "id" value = "{{$p->id}}">
										<li>
											<button type = "submit" class = "generalDelete reset"><i
													class = "icon-trash"></i>حذف
											</button>
										</li>
									</form>
								</ul>
							</li>
						</ul>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>

		<!-- Add  Modal -->
		<div class = "modal fade" id = "exampleModal" tabindex = "-1" role = "dialog"
		     aria-labelledby = "exampleModalLabel" aria-hidden = "true">
			<div class = "modal-dialog" role = "document">
				<div class = "modal-content">
					<div class = "modal-header">
						<h5 class = "modal-title" id = "exampleModalLabel">أضافة قسم </h5>
					</div>
					<div class = "modal-body">
						<div class = "row">
							<form action = "{{route('add_branch')}}" method = "POST">
								{{csrf_field()}}

								<div class = "row">
									<div class = "col-sm-6" style = "margin-top: 10px">
										<label>الإسم العربى</label>
										<input type = "text" name = "name_ar"
										       class = "form-control" required>
									</div>
									<div class = "col-sm-6" style = "margin-top: 10px">
										<label>الإسم إنجليزى</label>
										<input type = "text" name = "name_en"
										       class = "form-control" required>
									</div>
									<div class = "col-sm-6" style = "margin-top: 10px">
										<label>بداية الدوام</label>
										<input type = "time" name = "start_at"
										       class = "form-control" required>
									</div>
									<div class = "col-sm-6" style = "margin-top: 10px">
										<label>انتهاء الدوام</label>
										<input type = "time" name = "end_at"
										       class = "form-control" required>
									</div>

									<div class = "col-sm-12" style = "margin-top: 10px">
										<label>العنوان</label>
										<input style = "margin-bottom: 5px"
										       class = "form-control" id = "pac-input" name = "location"
										       value = ""
										       placeholder = "ادخل مكانك">

									</div>

									<input type = "hidden" name = "lat" id = "lat"
									       value = "24.7135517" readonly>


									<input type = "hidden" name = "lng" id = "lng"
									       value = "46.67529569999999" readonly>

									<div class = "row">
										<div class = "col-sm-12"
										     style = "width: 500px; height: 300px; margin-right: 60px"
										     id = "add_map">
										</div>
									</div>

									<div class = "col-sm-12" style = "margin-top: 10px">
										<button type = "submit"
										        class = "btn btn-primary addCategory">
											اضافه
										</button>
										<button type = "button" class = "btn btn-secondary"
										        data-dismiss = "modal">
											أغلاق
										</button>
									</div>
								</div>

							</form>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!-- /Add  Modal -->

		<!-- Edit  Modal -->
		<div class = "modal fade" id = "exampleModal2" tabindex = "-1" role = "dialog"
		     aria-labelledby = "exampleModalLabel" aria-hidden = "true">
			<div class = "modal-dialog" role = "document">
				<div class = "modal-content">
					<div class = "modal-header">
						<h5 class = "modal-title" id = "exampleModalLabel"> تعديل قسم : <span
								class = "userName"></span></h5>
					</div>
					<div class = "modal-body">
						<form action = "{{route('update_branch')}}" method = "post">

							<!-- token and user id -->
							{{csrf_field()}}
							<input type = "hidden" name = "id" value = "">
							<div class = "row">
								<div class = "col-sm-6" style = "margin-top: 10px">
									<label>الإسم العربى</label>
									<input type = "text" name = "edit_name_ar"
									       class = "form-control" required>
								</div>
								<div class = "col-sm-6" style = "margin-top: 10px">
									<label>الإسم إنجليزى</label>
									<input type = "text" name = "edit_name_en"
									       class = "form-control" required>
								</div>
								<div class = "col-sm-6" style = "margin-top: 10px">
									<label>بداية الدوام</label>
									<input type = "time" name = "edit_start_at"
									       class = "form-control" required>
								</div>
								<div class = "col-sm-6" style = "margin-top: 10px">
									<label>انتهاء الدوام</label>
									<input type = "time" name = "edit_end_at"
									       class = "form-control" required>
								</div>

								<div class = "col-sm-12" style="margin-top: 10px">
									<label>العنوان</label>
									<input style = "margin-bottom: 5px"
									       class = "form-control" id = "edit-pac-input" name = "edit_location"
									       value = ""
									       placeholder = "ادخل مكانك">

								</div>

								<input type = "hidden" name = "edit_lat" id = "edit_lat" readonly>


								<input type = "hidden" name = "edit_lng" id = "edit_lng" readonly>

								<div class = "row">
									<div class = "col-sm-12"
									     style = "width: 500px; height: 300px; margin-right: 60px"
									     id = "edit_map">
									</div>
								</div>

								<div class = "col-sm-12" style = "margin-top: 10px">
									<button type = "submit"
									        class = "btn btn-primary addCategory">
										حفظ
									</button>
									<button type = "button" class = "btn btn-secondary"
									        data-dismiss = "modal">
										أغلاق
									</button>
								</div>
							</div>


						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- /Edit user Modal -->


	</div>
	<script type = "text/javascript"
	        src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyD8cUy05UAvneVcdyAUodgt2qk2I2uzHdw&libraries=places&callback=initMap&lang=ar" defer>
	</script>
	<!-- javascript -->
@section('script')
	<script type = "text/javascript"
	        src = "{{asset('dashboard/js/plugins/tables/datatables/datatables.min.js')}}"></script>
	<script type = "text/javascript" src = "{{asset('dashboard/js/plugins/forms/selects/select2.min.js')}}"></script>
	<script type = "text/javascript" src = "{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>
@endsection



<script type = "text/javascript">


</script>

<!-- other code -->
<script type = "text/javascript">

    var map, infoWindow, geocoder;

    function initMap() {

        geocoder = new google.maps.Geocoder();
        infowindow = new google.maps.InfoWindow();

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            innerHTML = " حدث خطا أتناء تحديد الموفع ";
        }

        function showPosition(position) {
            var Latitude = position.coords.latitude;
            var Longitude = position.coords.longitude;


            var myLatlng = new google.maps.LatLng( 24.7135517, 46.67529569999999 );
            var myOptions = {
                zoom: 7,
                center: myLatlng,
                disableDefaultUI: true,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            geocoder.geocode({'latLng': myLatlng}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        $("#pac-input").val(results[0].formatted_address);
                        infowindow.setContent(results[0].formatted_address);
                        //infowindow.open(map, marker);
                    }
                }
            });
            var map = new google.maps.Map( document.getElementById( 'add_map' ), myOptions );

            var marker = new google.maps.Marker( {
                position: myLatlng,
                map: map,
                draggable: true
            } );
            var searchBox1 = new google.maps.places.SearchBox( document.getElementById( 'pac-input' ) );
            google.maps.event.addListener( searchBox1, 'places_changed', function () {
                var places = searchBox1.getPlaces();
                var bounds = new google.maps.LatLngBounds();
                var i, place;
                for ( i = 0; place = places[ i ]; i++ ) {

                    bounds.extend( place.geometry.location );
                    marker.setPosition( place.geometry.location );

                }
                map.fitBounds( bounds );
                map.setZoom( 12 );
            } );

            google.maps.event.addListener( marker, 'position_changed', function () {
                var lat = marker.getPosition().lat();
                var lng = marker.getPosition().lng();
                $( '#lat' ).val( lat );
                $( '#lng' ).val( lng );
            } );
            google.maps.event.addListener(marker, 'dragend', function (event) {
                geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                    if (results[0]) {
                        $("#pac-input").val(results[0].formatted_address);
                        infowindow.setContent(results[0].formatted_address);
                        //infowindow.open(map, marker);
                    }
                });
            });


        }
    }

	function EditOb( ob ) {

		console.table(ob);

		//set values in modal inputs
		$( "input[name='id']" ).val( ob.id );
		$( "input[name='edit_name_ar']" ).val( ob.name_ar );
		$( "input[name='edit_name_en']" ).val( ob.name_en );
		$( "input[name='edit_location']" ).val( ob.location );
		$( "input[name='edit_lat']" ).val( ob.lat );
		$( "input[name='edit_lng']" ).val( ob.lng );
		$( "input[name='edit_start_at']" ).val( ob.start_at );
		$( "input[name='edit_end_at']" ).val( ob.end_at );


		var myLatlng = new google.maps.LatLng( ob.lat, ob.lng );
		var myOptions = {
			zoom: 7,
			center: myLatlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
        geocoder.geocode({'latLng': myLatlng}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $("#edit-pac-input").val(results[0].formatted_address);
                    infowindow.setContent(results[0].formatted_address);
                    //infowindow.open(map, marker);
                }
            }
        });
		map = new google.maps.Map( document.getElementById( "edit_map" ), myOptions );
		marker = new google.maps.Marker( {
			                                 position: myLatlng,
			                                 map: map,
			                                 draggable: true
		                                 } );

		document.getElementById( "edit_lat" ).value = ob.lat;
		document.getElementById( "edit_lng" ).value = ob.lng;

		var searchBox = new google.maps.places.SearchBox( document.getElementById( 'edit-pac-input' ) );
		google.maps.event.addListener( searchBox, 'places_changed', function () {
			var places = searchBox.getPlaces();
			var bounds = new google.maps.LatLngBounds();
			var i, place;
			for ( i = 0; place = places[ i ]; i++ ) {

				bounds.extend( place.geometry.location );
				marker.setPosition( place.geometry.location );

			}
			map.fitBounds( bounds );
			map.setZoom( 12 );
		} );

		google.maps.event.addListener( marker, 'position_changed', function () {
			var lat = marker.getPosition().lat();
			var lng = marker.getPosition().lng();
			$( '#edit_lat' ).val( lat );
			$( '#edit_lng' ).val( lng );
		} );
        google.maps.event.addListener(marker, 'dragend', function (event) {
            geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                if (results[0]) {
                    $("#edit-pac-input").val(results[0].formatted_address);
                    infowindow.setContent(results[0].formatted_address);
                    //infowindow.open(map, marker);
                }
            });
        });

	}


</script>
<!-- /other code -->

@endsection
