@extends('dashboard.layout.master')


<!-- style -->
@section('style')
    <style type="text/css">

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


        .toggle.btn {
            min-width: 100px;
        }

        .toggle-group  .toggle-on {
            background-color: red !important;
        }

        .toggle-group  .toggle-off {
            background-color: #2196F3 !important;
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

        .panel-body + .dataTables_wrapper {
            padding-top: 10px;
            overflow: auto;
        }

        button.dt-button, div.dt-button, a.dt-button {
            margin-left: 10px;
        }


    </style>



    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">

@endsection
<!-- /style -->
@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title"> قائمة العروض</h5>

        </div>

        <!-- buttons -->
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-4">
                    <button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal"
                            onclick="initialize()" type="button" data-toggle="modal"
                            data-target="#exampleModal"><i class="icon-plus3"></i>
                        <span> اضافة عرض</span>
                    </button>
                </div>
                <div class="col-xs-4">
                    <button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i
                                class="icon-list-numbered"></i> <span>عدد العروض: {{count($ads)}} </span>
                    </button>
                </div>
                <div class="col-xs-4">
                    <a href="{{route('logout')}}" class="btn bg-warning-400 btn-block btn-float btn-float-lg"
                       type="button"><i class="icon-switch"></i> <span>خروج</span></a>
                </div>

            </div>
        </div>
        <!-- /buttons -->

        <table class="table table-hover" id="example" data-order="[]">
            <thead>
            <tr>
                <th>الصوره</th>
                <th>الاسم</th>
                <th>السعر</th>
                <th>السعر الكلى</th>
                <th>التقييم</th>
                <th>الحاله</th>
                <th>المساحة بالمتر المربع</th>
                <th>عدد المشاهدات</th>
                <th>التفعيل</th>
                <th style="min-width: 300px">الوصف</th>
                <th>المستخدم</th>
                <th>البريد الإلكترونى</th>
                <th>المحادثات</th>
                <th>صور العرض</th>
                <th>تاريخ الاضافه</th>
                <th>التحكم</th>
            </tr>
            </thead>
            <tbody>
            @foreach($ads as $ad)

                <script>

                </script>
                <tr>
                    <td><img src="{{asset('dashboard/uploads/adss/'.$ad->image[0]->image)}}"
                             style="width:40px;height: 40px" class="img-circle" alt=""></td>
                    <td>{{$ad->name_ar}}</td>
                    <td>{{$ad->cost}}</td>
                    <td>{{round( $ad -> cost + (float)( $ad -> cost * $ad -> tax / 100.00 ) + (float)( $ad -> cost * $ad -> vat / 100.00 ),
                                           2 )}}</td>
                    <td>{{rate($ad->id)}}</td>
                    <td>{{$ad->is_rent ? 'للبيع':'للايجار'}}</td>
                    <td>{{$ad->area}}</td>
                    <td>{{count($ad->view)}}</td>
                    <!---------- active or deactivate user ---------->
                    <td>
                        <input class="status" type="checkbox" data-toggle="toggle" data-on="الغاء التفعيل"
                               data-off="تفعيل" data-id="{{$ad->id}}"
                               value="{{$ad->active}}" {{$ad->active ?'checked' :''}}>
                    </td>
                    <td style="min-width: 300px">{{$ad->desc}}</td>
                    <td>{{$ad->user->name}}</td>
                    <td>{{$ad->user->email}}</td>
                    <td>
                        @if(count($ad->chat))
                            <a href="{{route('ad_rooms',[$ad->id])}}">كل المحادثات</a>
                        @else
                            لا يوجد محادثات
                        @endif
                    </td>
                    <td><a class="glyphicon glyphicon-th" href="{{route('ad_images', $ad->id)}}"> </a></td>
                    <td>{{$ad->created_at->diffForHumans()}}</td>

                    <td>
                        <ul class="icons-list">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-right">
                                    <!-- edit button -->
                                    <li>
                                        <a href="#"
                                           onclick="initializeEdit({{$ad}})"
                                           data-toggle="modal" data-target="#exampleModal2"
                                           class="openEditmodal">
                                            <i class="icon-pencil7"></i>تعديل
                                        </a>
                                    </li>


                                    <!-- delete button -->
                                    <form action="{{route('delete_ad')}}" method="POST">
                                        {{csrf_field()}}
                                        <input type="hidden" name="id" value="{{$ad->id}}">
                                        <li>
                                            <button type="submit" class="generalDelete reset"><i
                                                        class="icon-trash"></i>حذف
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

        <!-- Add ad Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">أضافة عرض جديد</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form action="{{route('add_ad')}}" method="POST"
                                  enctype="multipart/form-data">
                                {{csrf_field()}}

                                <input type="hidden" name="type" value="1">

                                <div class="row">
                                    <div class="col-sm-3 text-center">
                                        <label style="margin-bottom: 0">اختيار صور العرض</label>
                                        <i class="icon-camera" onclick="addChooseFile()"
                                           style="cursor: pointer;"></i>
                                        <div class="images-upload-block">
                                            <input type="file" name="avatar[]" multiple required>
                                        </div>
                                    </div>
                                    <div class="col-sm-9" style="margin-top: 20px">
                                        <input type="text" name="name_ar" id="address"
                                               class="form-control" placeholder="الاسم العربى"
                                               style="margin-bottom: 10px" required>
                                        <input type="text" name="name_en" id="address"
                                               class="form-control" placeholder="الاسم الانجليزى"
                                               style="margin-bottom: 10px" required>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="number" min="0" step=".01" name="cost"
                                               class="form-control"
                                               placeholder="السعر " required>

                                        <input type="number" min="0" step=".01" name="area"
                                               class="form-control"
                                               placeholder="المساحة بالمتر المربع" required>

                                        {{--<input type = "text" name = "code" class = "form-control"--}}
                                        {{--placeholder = "كود العرض ">--}}
                                    </div>

                                    <div class="col-sm-6">
                                        <select name="category" class="form-control"
                                                style="margin-bottom: 5px" required>
                                            <option selected hidden disabled>-- إختر القسم --</option>
                                            @foreach($categories as $category)
                                                <option
                                                        value="{{$category->id}}">{{$category->name_ar}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <select name="is_rent" class="form-control" required>
                                            <option selected hidden disabled>--الحالة --</option>
                                            <option value="0">للايجار</option>
                                            <option value="1">للبيع</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" name="area_number"
                                               class="form-control"
                                               placeholder="رقم القطعة " required>
                                    </div>

                                    <div class="col-sm-6">
                                        <input type="text" name="block_number"
                                               class="form-control"
                                               placeholder="رقم البلوك " required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="text" name="video" class="form-control"
                                               placeholder=" رابط مختصر الفيديو (اختيارى)">

                                    </div>
                                    <div class="col-sm-12">
										<textarea
                                                style="margin-bottom: 5px"
                                                class="form-control" id="desc" name="desc" rows="2"
                                                placeholder="وصف قصير" required></textarea>

                                    </div>

                                    <div class="col-sm-12">
										<textarea
                                                style="margin-bottom: 5px"
                                                class="form-control" id="details" name="details"
                                                rows="8"
                                                placeholder="التفاصيل" required></textarea>

                                    </div>

                                    <div class="col-sm-12">
                                        <input style="margin-bottom: 5px"
                                               class="form-control" id="pac-input" name="pac-input"
                                               value=""
                                               placeholder="ادخل مكانك">

                                    </div>

                                    <input type="hidden" name="lat" id="lat"
                                           value="24.7135517" readonly>


                                    <input type="hidden" name="lng" id="lng"
                                           value="46.67529569999999" readonly>

                                    <div class="row">
                                        <div class="col-sm-12"
                                             style="width: 500px; height: 300px; margin-right: 60px"
                                             id="add_map">
                                        </div>
                                    </div>


                                </div>

                                <div class="col-sm-12" style="margin-top: 10px">
                                    <button type="submit" class="btn btn-primary addCategory">اضافه
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        أغلاق
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- /Add user Modal -->

        <!-- Edit ad Modal -->
        <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> تعديل العرض : <span
                                    class="userName"></span></h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('update_ad')}}" method="post"
                              enctype="multipart/form-data">

                            <!-- token and user id -->
                            {{csrf_field()}}

                            <input type="hidden" name="type" value="1">

                            <input type="hidden" name="id" value="">
                            <div class="row">

                                <div class="col-sm-6" style="margin-top: 20px">
                                    <label>الاسم العربى</label>
                                    <input type="text" name="edit_name_ar" id="address"
                                           class="form-control" placeholder="الاسم العربى"
                                           style="margin-bottom: 10px" required>

                                </div>
                                <div class="col-sm-6" style="margin-top: 20px">
                                    <label>الاسم الانجليزى</label>
                                    <input type="text" name="edit_name_en" id="address"
                                           class="form-control" placeholder="الاسم الانجليزى"
                                           style="margin-bottom: 10px" required>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="number" name="edit_cost" class="form-control"
                                           placeholder="السعر " required>

                                    <input type="number" min="0" step=".01" name="edit_area"
                                           class="form-control"
                                           placeholder="المساحة بالمتر المربع" required>


                                    {{--<input type = "text" name = "edit_code" class = "form-control"--}}
                                    {{--placeholder = "كود العرض ">--}}
                                </div>

                                <div class="col-sm-6">
                                    <select name="edit_category" id="edit_category" class="form-control"
                                            style="margin-bottom: 5px" required>
                                        <option hidden disabled>-- إختر القسم --</option>
                                        @foreach($categories as $category)
                                            <option
                                                    value="{{$category->id}}">{{$category->name_ar}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-6">
                                    <select name="edit_is_rent" id="edit_is_rent" class="form-control" required>
                                        <option value="0">للايجار</option>
                                        <option value="1">للبيع</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="text" name="edit_area_number"
                                           class="form-control"
                                           placeholder="رقم القطعة " required>
                                </div>

                                <div class="col-sm-6">
                                    <input type="text" name="edit_block_number"
                                           class="form-control"
                                           placeholder="رقم البلوك " required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="text" name="edit_video" class="form-control"
                                           placeholder="رابط مختصر الفيديو (اختيارى)">
                                </div>
                                <div class="col-sm-12">
										<textarea
                                                style="margin-bottom: 5px"
                                                class="form-control" id="edit_desc" name="edit_desc"
                                                rows="2"
                                                placeholder="وصف قصير" required></textarea>
                                </div>

                                <div class="col-sm-12">
										<textarea
                                                style="margin-bottom: 5px"
                                                class="form-control" id="edit_details"
                                                name="edit_details"
                                                rows="8"
                                                placeholder="التفاصيل" required></textarea>
                                </div>

                                <div class="col-sm-12">
                                    <input style="margin-bottom: 5px"
                                           class="form-control" id="edit-pac-input" name="pac-input"
                                           value=""
                                           placeholder="ادخل مكانك">

                                </div>

                                <input type="hidden" name="edit_lat" id="edit_lat" readonly>


                                <input type="hidden" name="edit_lng" id="edit_lng" readonly>


                                <div class="col-sm-6" style="width: 500px; height: 300px; margin-right: 30px"
                                     id="edit_map">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12" style="margin-top: 10px">
                                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
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
    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD8cUy05UAvneVcdyAUodgt2qk2I2uzHdw&libraries=places">
    </script>
    <!-- javascript -->
    <!-- javascript -->
@section('script')
    <script type="text/javascript"
            src="{{asset('dashboard/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('dashboard/js/plugins/forms/selects/select2.min.js       ')}}"></script>
    {{--<script type = "text/javascript" src = "{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>--}}


    <script type="text/javascript"
            src="{{asset('dashboard/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('dashboard/js/plugins/forms/selects/select2.min.js')}}"></script>
    {{--<script type = "text/javascript" src = "{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>--}}


    {{--<script src="https://code.jquery.com/jquery-3.3.1.js" type="text/javascript"></script>--}}
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"
            type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"
            type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"
            type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(function () {


            //active and deactivate ad

            $('.toggle').on('click', function () {

                let tog = $(this).find(".status");
                let v = tog.val();
                let id = tog.data('id');

                $.ajax({
                    type: "POST",
                    url: "{{route('change_ad_status')}}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: id,
                        status: v == 1 ? 0 : 1
                    },
                    cache: false,
                    success: function (data) {


                        if (data.status == 1) {

                            v == 1 ? tog.val(0) : tog.val(1);
                        } else {
                            alert('لا يمكن تغيير حالة هذا العرض');

                            tog.bootstrapToggle('on');
                        }
                    }, error: function () {
                        tog.bootstrapToggle('on');
                    }
                });
            });


            $('#example').DataTable({

                // "scrollX": true,
                dom: 'Bfrtip',
                buttons: [
                    // 'copyHtml5',
                    'excelHtml5',
                    // 'csvHtml5',
                    // 'pdfHtml5'
                ]
            });
        });
    </script>


@endsection


<script type="text/javascript">


    function initializeEdit(ad) {

        var myLatlng = new google.maps.LatLng( ad.lat, ad.lng );
        var myOptions = {
            zoom: 7,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        map = new google.maps.Map( document.getElementById( "edit_map" ), myOptions );
        marker = new google.maps.Marker( {
            position: myLatlng,
            map: map,
            draggable: true
        } );
        //set values in modal inputs

        $("input[name='id']").val(ad.id)
        $("input[name='edit_name_ar']").val(ad.name_ar)
        $("input[name='edit_name_en']").val(ad.name_en)
        $("input[name='edit_cost']").val(ad.cost)
        $("input[name='edit_area']").val(ad.area)
        // $("input[name='edit_code']").val(ad.code)
        $("input[name='edit_video']").val(ad.video)
        $('#edit_desc').val(ad.desc)
        $('#edit_details').val(ad.details)
        $("input[name='edit_lat']").val(ad.lat)
        $("input[name='edit_lng']").val(ad.lng)
        $("input[name='edit_area_number']").val(ad.area_number)
        $("input[name='edit_block_number']").val(ad.block_number)

        $('#edit_category option').each(function () {
            if ($(this).val() == ad.category_id) {
                $(this).attr('selected', '')
            }
        });

        $('#edit_is_rent option').each(function () {
            if ($(this).val() == ad.is_rent) {
                $(this).attr('selected', '')
            }
        });


        document.getElementById( "edit_lat" ).value = ad.lat;
        document.getElementById( "edit_lng" ).value = ad.lng;

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

        var map = new google.maps.Map( document.getElementById( 'add_map' ), myOptions );

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
            $( '#lat' ).val( lat );
            $( '#lng' ).val( lng );
        } );

    }

</script>

<!-- other code -->
<script type="text/javascript">


    function addChooseFile() {
        $("input[name='avatar']").click()
    }

    //stay in current tab after reload
    $(function () {
        // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            // save the latest tab; use cookies if you like 'em better:
            localStorage.setItem('lastTab', $(this).attr('href'));
        });

        // go to the latest tab, if it exists:
        var lastTab = localStorage.getItem('lastTab');
        if (lastTab) {
            $('[href="' + lastTab + '"]').tab('show');
        }
    });
    $(function () {
        $("input[type='submit']").click(function () {
            var $fileUpload = $("input[type='file']");
            if (parseInt($fileUpload.get(0).files.length) > 11) {
                alert("أقصى عدد ممكن 11 صورة");
            }
        });
    });

</script>
<!-- /other code -->

@endsection
