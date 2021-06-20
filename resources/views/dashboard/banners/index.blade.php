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
            <h5 class = "panel-title">البنرات الاعلانية</h5>

        </div>

        <!-- buttons -->
        <div class = "panel-body">
            <div class = "row">
                <div class = "col-xs-6">
                    <button class = "btn bg-blue btn-block btn-float btn-float-lg openAddModal"
                            onclick = "initialize()" type = "button" data-toggle = "modal"
                            data-target = "#exampleModal"><i class = "icon-plus3"></i> <span>اضافة  بنر اعلاني</span>
                    </button>
                </div>
                <div class = "col-xs-6">
                    <button class = "btn bg-purple-300 btn-block btn-float btn-float-lg" type = "button"><i
                                class = "icon-list-numbered"></i> <span>عدد البنرات الاعلانية: {{count($banners)}} </span>
                    </button>
                </div>

            </div>
        </div>
        <!-- /buttons -->

        <table class = "table datatable-basic" data-order = "[]">
            <thead>
            <tr>
                <th>الصورة</th>
                <th>الرابط</th>
                <th>تاريخ الاضافه</th>
                <th>التحكم</th>
            </tr>
            </thead>
            <tbody>
            @foreach($banners as $p)
                <tr>
                    <td> <a target="_blank" href="{{url('dashboard/uploads/banners/'.$p->image)}}"> <img src = "{{asset('dashboard/uploads/banners/'.$p->image)}}" style = "width:70px;height: 70px"  alt = ""></a></td>
                    <td>{{$p->url}}</td>
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
                                    <form action = "{{route('delete_banner')}}" method = "POST">
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
                        <h5 class = "modal-title" id = "exampleModalLabel">أضافة بنر اعلاني </h5>
                    </div>
                    <div class = "modal-body">
                        <div class = "row">
                            <form action = "{{route('add_banner')}}" method = "POST" enctype="multipart/form-data">
                                {{csrf_field()}}

                                <div class = "row">
                                    <div class = "col-sm-12 text-center">
                                        <label style = "margin-bottom: 0">اختيار صوره</label>
                                        <div class = "text-center">
                                            <div class = "images-upload-block single-image">
                                                <label class = "upload-img">
                                                    <input type = "file" name = "image"
                                                           accept = "image/*" class = "image-uploader" required>
                                                    <i class = "icofont icofont-plus"></i>
                                                </label>

                                                <div class = " upload-area"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "col-sm-12" style = "margin-top: 10px">
                                        <label>الرابط</label>
                                        <input type = "url" name = "url" class = "form-control" required>
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
                        <h5 class = "modal-title" id = "exampleModalLabel"> تعديل بنر اعلاني : <span
                                    class = "userName"></span></h5>
                    </div>
                    <div class = "modal-body">
                        <form action = "{{route('update_banner')}}" method = "post" enctype="multipart/form-data">

                            <!-- token and user id -->
                            {{csrf_field()}}
                            <input type = "hidden" name = "id" id="id" value = "">
                            <div class = "row">
                                <!--------- image --------->
                                <div class = "col-sm-12 text-center">
                                    <label style = "margin-bottom: 0">اختيار صوره</label>
                                    <div class = "text-center">
                                        <div class = "images-upload-block single-image">
                                            <label class = "upload-img">
                                                <input type = "file" name = "image" id = "edit_image"
                                                       accept = "image/*" class = "image-uploader">
                                                <i class = "icofont icofont-plus"></i>
                                            </label>

                                            <div class = "edit_image upload-area"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class = "col-sm-12" style = "margin-top: 10px">
                                    <label>الرابط</label>
                                    <input type = "url" name = "url" id="url" class = "form-control" required>
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


    function EditOb( ob ) {

        console.table(ob);

        //set values in modal inputs
        $( "#id" ).val( ob.id );
        $( "#url" ).val( ob.url );


        let image = $( '.edit_image' );
        let link = "{{asset('dashboard/uploads/banners/')}}" + '/' + ob.image;
        image.empty();
        image.append( '<div class="uploaded-block" data-count-order="1">' +
            '<img src="' + link + '">' +
            '<button class="close">&times;</button>' +
            '</div>' );

    }


</script>
<!-- /other code -->

@endsection
