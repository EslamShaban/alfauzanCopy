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

        .pac-container {
            z-index: 99999 !important;
        }

    </style>
@endsection
<!-- /style -->
@section('content')
    <div class = "panel panel-flat">
        <div class = "panel-heading">
            <h5 class = "panel-title">المخططات</h5>

        </div>

        <!-- buttons -->
        <div class = "panel-body">
            <div class = "row">
                <div class = "col-xs-3">
                    <button class = "btn bg-blue btn-block btn-float btn-float-lg openAddModal"
                            onclick = "initialize()" type = "button" data-toggle = "modal"
                            data-target = "#exampleModal"><i class = "icon-plus3"></i> <span>اضافة  مخطط</span>
                    </button>
                </div>
                <div class = "col-xs-3">
                    <button class = "btn bg-purple-300 btn-block btn-float btn-float-lg" type = "button"><i
                            class = "icon-list-numbered"></i> <span>عدد : {{count($projects)}} </span>
                    </button>
                </div>
                <div class = "col-xs-3">
                    <a href = "{{route('p_categories')}}"
                       class = "btn bg-teal-400 btn-block btn-float btn-float-lg correspondent">
                        <i class = " icon-station"></i> <span>الاقسام الفرعية</span>
                    </a>
                </div>
                <div class = "col-xs-3">
                    <a href = "{{route('logout')}}" class = "btn bg-warning-400 btn-block btn-float btn-float-lg"
                       type = "button"><i class = "icon-switch"></i> <span>خروج</span></a>
                </div>
            </div>
        </div>
        <!-- /buttons -->

        <table class = "table datatable-basic" data-order = "[]">
            <thead>
            <tr>
                <th>الإسم</th>
                <th>الإسم إنجليزى</th>
                <th>الاقسام</th>
                <th>تاريخ الاضافه</th>
                <th>التحكم</th>
            </tr>
            </thead>
            <tbody>
            @foreach($projects as $p)
                <tr>
                    <td>{{$p->name_ar}}</td>
                    <td>{{$p->name_en}}</td>
                    <td>
                        @foreach($p->categories as $cat)
                            {{$cat->name_ar}} <br>
                        @endforeach
                    </td>
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
                                           onclick = "initializeEdit({{$p}})" class = "openEditmodal">
                                            <i class = "icon-pencil7"></i>تعديل
                                        </a>
                                    </li>


                                    <!-- delete button -->
                                    <form action = "{{route('delete_project')}}" method = "POST">
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

        <!-- Add project Modal -->
        <div class = "modal fade" id = "exampleModal" tabindex = "-1" role = "dialog"
             aria-labelledby = "exampleModalLabel" aria-hidden = "true">
            <div class = "modal-dialog" role = "document">
                <div class = "modal-content">
                    <div class = "modal-header">
                        <h5 class = "modal-title" id = "exampleModalLabel">أضافة مخطط </h5>
                    </div>
                    <div class = "modal-body">
                        <div class = "row">
                            <form action = "{{route('add_project')}}" method = "POST">
                                {{csrf_field()}}


                                <div class = "row">
                                    <div class = "col-sm-12" style = "margin-top: 10px">
                                        <label>الإسم</label>
                                        <input type = "text" name = "name_ar" id = "name"
                                               class = "form-control" required>
                                    </div>
                                    <div class = "col-sm-12" style = "margin-top: 10px">
                                        <label>الإسم إنجليزى</label>
                                        <input type = "text" name = "name_en" id = "name_en"
                                               class = "form-control" required>
                                    </div>
                                    <div class = "col-sm-12" style = "margin-top: 10px">
                                        <label> القسم (اختر قسم واحد على الاقل)</label>
                                        <div class = "row">

                                            @foreach($categories as $category)
                                                <div class = "col-sm-3">
                                                    <label class = "checkbox">
                                                        <input type = "checkbox" name = "category[]"
                                                               value = "{{$category->id}}">
                                                        <i class = "icon-checkbox par"></i>
                                                        <label class = "checkbox"
                                                               style = "padding-right:8px">{{$category->name_ar}}</label>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class = "col-sm-12">
                                    <input style = "margin-bottom: 5px"
                                           class = "form-control" id = "pac-input" name = "pac-input"
                                           value = ""
                                           placeholder = "ادخل مكانك">

                                </div>


                                <div id = "add_map">
                                </div>

                                <div class = "col-sm-6" style = "margin-top: 5px">
                                    <input type = "hidden" name = "lat_add" id = "lat_add"
                                           value = "24.7135517" readonly>
                                </div>
                                <div class = "col-sm-6" style = "margin-top: 5px">
                                    <input type = "hidden" name = "lng_add" id = "lng_add"
                                           value = "46.67529569999999" readonly>
                                </div>
                                <div class = "row">
                                    <div class = "col-sm-12"
                                         style = "width: 500px; height: 300px; margin-right: 60px"
                                         id = "map_add">
                                    </div>
                                </div>

                                <div class = "col-sm-12" style = "margin-top: 10px">
                                    <button type = "submit" class = "btn btn-primary addCategory">اضافه
                                    </button>
                                    <button type = "button" class = "btn btn-secondary" data-dismiss = "modal">
                                        أغلاق
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- /Add project Modal -->

        <!-- Edit project Modal -->
        <div class = "modal fade" id = "exampleModal2" tabindex = "-1" role = "dialog"
             aria-labelledby = "exampleModalLabel" aria-hidden = "true">
            <div class = "modal-dialog" role = "document">
                <div class = "modal-content">
                    <div class = "modal-header">
                        <h5 class = "modal-title" id = "exampleModalLabel"> تعديل مخطط : <span
                                class = "userName"></span></h5>
                    </div>
                    <div class = "modal-body">
                        <form action = "{{route('update_project')}}" method = "post">

                            <!-- token and user id -->
                            {{csrf_field()}}
                            <input type = "hidden" name = "id" value = "">
                            <!-- /token and user id -->
                            <div class = "row">
                                <div class = "col-sm-12" style = "margin-top: 10px">
                                    <label>الإسم</label>
                                    <input type = "text" name = "edit_name" id = "edit_name"
                                           class = "form-control" required>
                                </div>
                                <div class = "col-sm-12" style = "margin-top: 10px">
                                    <label>الإسم أنجليزى</label>
                                    <input type = "text" name = "edit_name_en" id = "edit_name_en"
                                           class = "form-control" required>
                                </div>

                                <div class = "col-sm-12" style = "margin-top: 10px">
                                    <label>القسم (اختر قسم واحد على الاقل)</label>
                                    <div class = "row">

                                        @foreach($categories as $category)
                                            <div class = "col-sm-3">
                                                <label class = "checkbox">
                                                    <input type = "checkbox" name = "edit_category[]"
                                                           id = "cat_{{$category->id}}"
                                                           class = "cats"
                                                           value = "{{$category->id}}">
                                                    <i class = "icon-checkbox par"></i>
                                                    <label class = "checkbox"
                                                           style = "padding-right:8px">{{$category->name_ar}}</label>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class = "col-sm-12">
                                <input style = "margin-bottom: 5px"
                                       class = "form-control" id = "edit-pac-input" name = "pac-input" value = ""
                                       placeholder = "ادخل مكانك">

                            </div>

                            <div class = "col-sm-6" style = "margin-top: 5px">
                                <input type = "hidden" name = "edit_lat" id = "edit_lat" readonly>
                            </div>
                            <div class = "col-sm-6" style = "margin-top: 5px">
                                <input type = "hidden" name = "edit_lng" id = "edit_lng" readonly>
                            </div>
                            <div class = "row">

                                <div class = "col-sm-9"
                                     style = "width: 500px; height: 300px; margin-right: 30px"
                                     id = "map_edit">
                                </div>
                            </div>


                            <div class = "row">
                                <div class = "col-sm-12" style = "margin-top: 10px">
                                    <button type = "submit" class = "btn btn-primary">حفظ التعديلات</button>
                                    <button type = "button" class = "btn btn-secondary" data-dismiss = "modal">
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
            src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyD8cUy05UAvneVcdyAUodgt2qk2I2uzHdw&libraries=places">
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


    function initializeEdit( pro ) {

        $( '.cats' ).prop( "checked", false );
        //set values in modal inputs

        $( "input[name='id']" ).val( pro.id )
        $( "input[name='edit_name']" ).val( pro.name_ar );
        $( "input[name='edit_name_en']" ).val( pro.name_en );
        $( "input[name='edit_lat']" ).val( pro.lat );
        $( "input[name='edit_lng']" ).val( pro.lng );

        $.each( pro.categories, function ( key, value ) {
            // $( '#cat_' + value.id ).attr( 'checked', 'checked' );
            $( '#cat_' + value.id ).prop( "checked", true );
        } );

        var myLatlng = new google.maps.LatLng( pro.lat, pro.lng );
        var myOptions = {
            zoom: 7,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        map = new google.maps.Map( document.getElementById( "map_edit" ), myOptions );
        marker = new google.maps.Marker( {
                                             position: myLatlng,
                                             map: map,
                                             draggable: true
                                         } );


        document.getElementById( "edit_lat" ).value = pro.lat;
        document.getElementById( "edit_lng" ).value = pro.lng;


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


    }

    function initialize() {

        var myLatlng = new google.maps.LatLng( 24.7135517, 46.67529569999999 );

        var myOptions = {
            zoom: 7,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }

        var map = new google.maps.Map( document.getElementById( 'map_add' ), myOptions );

        var marker = new google.maps.Marker( {
                                                 position: myLatlng,
                                                 map: map,
                                                 draggable: true
                                             } );

        var searchBox = new google.maps.places.SearchBox( document.getElementById( 'pac-input' ) );
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
            $( '#lat_add' ).val( lat );
            $( '#lng_add' ).val( lng );
        } );

    }


</script>
<!-- /other code -->

@endsection
