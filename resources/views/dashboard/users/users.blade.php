@extends('dashboard.layout.master')

<!-- style -->
@section('style')
    <style type = "text/css">
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

        .dropup .dropdown-menu, .navbar-fixed-bottom .dropdown .dropdown-menu {
            top: 100%;
            bottom: auto;
        }

        .panel-body + .dataTables_wrapper {
            padding-top: 10px;
        }

        button.dt-button, div.dt-button, a.dt-button {
            margin-left: 10px;
        }
    </style>


    <link rel = "stylesheet" type = "text/css"
          href = "https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel = "stylesheet" type = "text/css"
          href = "https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
@endsection
<!-- /style -->
@section('content')
    <div class = "panel panel-flat">
        <div class = "panel-heading">
            <h5 class = "panel-title">قائمة الاعضاء</h5>

        </div>

        <!-- buttons -->
        <div class = "panel-body">
            <div class = "row">
                <div class = "col-xs-3">
                    <button class = "btn bg-blue btn-block btn-float btn-float-lg openAddModal" type = "button"
                            data-toggle = "modal" data-target = "#exampleModal"><i class = "icon-plus3"></i> <span>اضافة عضو</span>
                    </button>
                </div>
                <div class = "col-xs-3">
                    <button class = "btn bg-purple-300 btn-block btn-float btn-float-lg" type = "button"><i
                            class = "icon-list-numbered"></i> <span>عدد الاعضاء : {{count($users)}} </span>
                    </button>
                </div>
                <div class = "col-xs-3">
                    <button class = "btn bg-teal-400 btn-block btn-float btn-float-lg correspondent"
                            type = "button" data-toggle = "modal" data-target = "#exampleModal3"><i
                            class = " icon-station"></i> <span>مراسلة الاعضاء</span></button>
                </div>
                <div class = "col-xs-3">
                    <a href = "{{route('logout')}}" class = "btn bg-warning-400 btn-block btn-float btn-float-lg"
                       type = "button"><i class = "icon-switch"></i> <span>خروج</span></a>
                </div>
            </div>
        </div>
        <!-- /buttons -->

        <table class = "table  table-hover display" data-order = "[]" id = "example">
            <thead>
            <tr>
                <th>الصوره</th>
                <th>الاسم</th>
                <th>البريد</th>
                <th>الهاتف</th>
                <th>الصلاحيه</th>
                <th>الحاله</th>
                <th>تاريخ الاضافه</th>
                <th>التحكم</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $u)
                <tr>
                    <td><img src = "{{asset('dashboard/uploads/users/'.$u->avatar)}}"
                             style = "width:40px;height: 40px" class = "img-circle" alt = ""></td>
                    <td>{{$u->name}}</td>
                    <td>{{$u->email}}</td>
                    <td>{{$u->phone}}</td>
                    @if(is_null($u->role) ||$u->role < 1 )
                        <td>عضو</td>
                    @else
                        <td>{{$u->Role->role}}</td>
                    @endif
                    @if($u->block)
                        <td><span class = "label label-danger">حظر</span></td>
                    @else
                        <td><span class = "label label-success">نشط</span></td>
                    @endif
                    <td>{{$u->created_at->diffForHumans()}}</td>
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
                                           class = "openEditmodal"
                                           data-id = "{{$u->id}}"
                                           data-phone = "{{$u->phone}}"
                                           data-name = "{{$u->name}}"
                                           data-email = "{{$u->email}}"
                                           data-photo = "{{asset('dashboard/uploads/users/'.$u->avatar)}}"
                                           data-block = "{{$u->block}}"
                                           data-permission = "{{$u->role}}">
                                            <i class = "icon-pencil7"></i>تعديل
                                        </a>
                                    </li>
                                    <!-- send message button -->
                                    <li>
                                        <a href = "#" data-toggle = "modal" data-target = "#exampleModal4"
                                           class = "SendMessageUser"
                                           data-id = "{{$u->id}}"
                                           data-name = "{{$u->name}}"
                                           data-phone = "{{$u->phone}}"
                                           data-device_id = "{{$u->device_id}}"
                                           data-email = "{{$u->email}}">
                                            <i class = " icon-bubble9"></i>مراسله
                                        </a>
                                    </li>
                                    <!-- delete button -->
                                    <form action = "{{route('deleteuser')}}" method = "POST">
                                        {{csrf_field()}}
                                        <input type = "hidden" name = "id" value = "{{$u->id}}">
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

        <!-- Add user Modal -->
        <div class = "modal fade" id = "exampleModal" tabindex = "-1" role = "dialog"
             aria-labelledby = "exampleModalLabel" aria-hidden = "true">
            <div class = "modal-dialog" role = "document">
                <div class = "modal-content">
                    <div class = "modal-header">
                        <h5 class = "modal-title" id = "exampleModalLabel">أضافة عضو جديد</h5>
                    </div>
                    <div class = "modal-body">
                        <div class = "row">
                            <form action = "{{route('adduser')}}" method = "POST"
                                  enctype = "multipart/form-data">
                                {{csrf_field()}}

                                <div class = "row">
                                    <div class = "col-sm-3 text-center">
                                        <label style = "margin-bottom: 0">اختيار صوره</label>
                                        {{--<i class = "icon-camera" onclick = "addChooseFile()"--}}
                                        {{--style = "cursor: pointer;"></i>--}}
                                        <div class = "text-center">
                                            <div class = "images-upload-block single-image">
                                                <label class = "upload-img">
                                                    <input type = "file" name = "avatar" id = "image" accept = "image/*"
                                                           class = "image-uploader" required>
                                                    <i class = "icofont icofont-plus"></i>
                                                </label>

                                                <div class = "upload-area"></div>
                                            </div>
                                        </div>
                                        {{--<img src = "{{asset('dashboard/uploads/users/')}}" id="u_avatar" onclick = "addChooseFile()" class = "photo"--}}
                                        {{--style = "width: 120px;height: 120px;cursor: pointer">--}}
                                        <div class = "images-upload-block">
                                            {{--<input type = "file" name = "avatar" class = "image-uploader"--}}
                                            {{--id = "hidden">--}}
                                        </div>
                                    </div>
                                    <div class = "col-sm-9" style = "margin-top: 20px">
                                        <input type = "text" name = "name" class = "form-control"
                                               placeholder = "الاسم" style = "margin-bottom: 10px" required>
                                        <input type = "text" name = "email" class = "form-control"
                                               placeholder = "البريد " required>
                                    </div>
                                </div>

                                <div class = "row">
                                    <div class = "col-sm-6">
                                        <input type = "number" name = "phone" class = "form-control"
                                               placeholder = "الهاتف " required>
                                        <input type = "text" name = "password" class = "form-control"
                                               placeholder = "الرقم السرى " required>
                                    </div>

                                    <div class = "col-sm-6">
                                        <select name = "role" class = " form-control" id = "permissions" required>
                                            <option value = "" hidden disabled selected>الصلاحية</option>
                                            @foreach($roles as $role)
                                                <option value = "{{$role->id}}">{{$role->role}}</option>
                                            @endforeach
                                        </select>
                                        <div style = "margin-top: 13px">
                                            <label class = "checkbox" style = "margin-bottom: 0">
                                                <label style = "padding-right: 0"> حظر</label>
                                                <input type = "checkbox" name = "block" value = "1">
                                                <i class = "icon-checkbox"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class = "col-sm-12" style = "margin-top: 10px">
                                    <button type = "submit" class = "btn btn-primary addCategory">اضافه</button>
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
        <!-- /Add user Modal -->

        <!-- Edit user Modal -->
        <div class = "modal fade" id = "exampleModal2" tabindex = "-1" role = "dialog"
             aria-labelledby = "exampleModalLabel" aria-hidden = "true">
            <div class = "modal-dialog" role = "document">
                <div class = "modal-content">
                    <div class = "modal-header">
                        <h5 class = "modal-title" id = "exampleModalLabel"> تعديل عضو : <span
                                class = "userName"></span></h5>
                    </div>
                    <div class = "modal-body">
                        <form action = "{{route('updateuser')}}" method = "post" enctype = "multipart/form-data">

                            <!-- token and user id -->
                            {{csrf_field()}}
                            <input type = "hidden" name = "id" value = "">
                            <!-- /token and user id -->
                            <div class = "row">
                                <div class = "col-sm-3 text-center">
                                    <label>اختيار صوره</label>
                                    <img src = "{{asset('dashboard/uploads/users/'.$u->avatar)}}" id = "u_avatar"
                                         class = "photo"
                                         style = "width: 120px;height: 120px;cursor: pointer"

                                         onclick = "ChooseFile()">
                                    <input type = "file" name = "edit_avatar" style = "display: none;">
                                </div>
                                <div class = "col-sm-9" style = "margin-top: 10px">
                                    <label>الاسم</label>
                                    <input type = "text" name = "edit_name" class = "form-control">
                                    <label>البريد</label>
                                    <input type = "text" name = "edit_email" class = "form-control">
                                </div>
                            </div>
                            <div class = "row">

                                <div class = "col-sm-6" style = "margin-top: 5px">
                                    <label>الهاتف</label>
                                    <input type = "number" name = "edit_phone" class = "form-control">
                                    <label>الرقم السرى</label>
                                    <input type = "text" name = "edit_password" class = "form-control">
                                </div>

                                <div class = "col-sm-6 " style = "margin-top:9px">
                                    <select name = "role" class = "form-control" id = "permissions"
                                            style = "margin-top: 22px" required>
                                        @foreach($roles as $role)
                                            <option value = "{{$role->id}}">{{$role->role}}</option>
                                        @endforeach
                                    </select>
                                    <div style = "margin-top: 30px">
                                        <label class = "checkbox" style = "margin-bottom: 0">
                                            <label style = "padding-right: 0"> حظر</label>
                                            <input type = "checkbox" name = "block" id = "editActive"
                                                   value = "0">
                                            <i class = "icon-checkbox"></i>
                                        </label>
                                    </div>
                                </div>

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

        <!-- correspondent for all users Modal -->
        <div class = "modal fade" id = "exampleModal3" tabindex = "-1" role = "dialog"
             aria-labelledby = "exampleModalLabel" aria-hidden = "true">
            <div class = "modal-dialog" role = "document">
                <div class = "modal-content">
                    <div class = "modal-header">
                        <h5 class = "modal-title" id = "exampleModalLabel">مراسلة جميع الاعضاء</span></h5>
                    </div>
                    <div class = "modal-body">
                        <div class = "row">
                            <div class = "tabbable">
                                <ul class = "nav nav-tabs bg-slate nav-tabs-component nav-justified">
                                    <!-- email -->
                                    <li class = "active"><a href = "#colored-rounded-justified-tab1"
                                                            data-toggle = "tab">ايميل</a></li>
                                    <!-- sms -->
                                    <li><a href = "#colored-rounded-justified-tab2" data-toggle = "tab">رساله
                                                                                                        SMS</a>
                                    </li>
                                </ul>

                                <div class = "tab-content">
                                    <!-- email -->
                                    <div class = "tab-pane active" id = "colored-rounded-justified-tab1">
                                        <div class = "row">
                                            <form action = "{{route('emailallusers')}}" method = "POST"
                                                  enctype = "multipart/form-data">
                                                {{csrf_field()}}
                                                <div class = "col-sm-12">
													<textarea rows = "15" name = "email_message"
                                                              class = "form-control"
                                                              placeholder = "نص رسالة الـ Email "></textarea>
                                                </div>

                                                <div class = "col-sm-12" style = "margin-top: 10px">
                                                    <button type = "submit"
                                                            class = "btn btn-primary addCategory"
                                                    ">ارسال</button>
                                                    <button type = "button" class = "btn btn-secondary"
                                                            data-dismiss = "modal">أغلاق
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- sms -->
                                    <div class = "tab-pane" id = "colored-rounded-justified-tab2">
                                        <div class = "row">
                                            <form action = "{{route('smsallusers')}}" method = "POST"
                                                  enctype = "multipart/form-data">
                                                {{csrf_field()}}
                                                <div class = "col-sm-12">
													<textarea rows = "15" name = "sms_message"
                                                              class = "form-control"
                                                              placeholder = "نص رسالة الـ SMS "></textarea>
                                                </div>

                                                <div class = "col-sm-12" style = "margin-top: 10px">
                                                    <button type = "submit"
                                                            class = "btn btn-primary addCategory"
                                                    ">ارسال</button>
                                                    <button type = "button" class = "btn btn-secondary"
                                                            data-dismiss = "modal">أغلاق
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- /correspondent for all users Modal -->

        <!-- correspondent for one user Modal -->
        <div class = "modal fade" id = "exampleModal4" tabindex = "-1" role = "dialog"
             aria-labelledby = "exampleModalLabel" aria-hidden = "true">
            <div class = "modal-dialog" role = "document">
                <div class = "modal-content">
                    <div class = "modal-header">
                        <h5 class = "modal-title" id = "exampleModalLabel">مراسلة : <span
                                class = "reverName"></span></h5>
                    </div>
                    <div class = "modal-body">
                        <div class = "row">
                            <div class = "tabbable">
                                <ul class = "nav nav-tabs bg-slate nav-tabs-component nav-justified">
                                    <!-- email -->
                                    <li class = "active"><a href = "#colored-rounded-justified-tab10"
                                                            data-toggle = "tab">ايميل</a></li>
                                    <!-- sms -->
                                    <li><a href = "#colored-rounded-justified-tab20" data-toggle = "tab">رساله
                                                                                                         SMS</a>
                                    </li>

                                </ul>

                                <div class = "tab-content">
                                    <!-- email -->
                                    <div class = "tab-pane active" id = "colored-rounded-justified-tab10">
                                        <div class = "row">
                                            <form action = "{{route('sendcurrentemail')}}" method = "POST"
                                                  enctype = "multipart/form-data">
                                                {{csrf_field()}}
                                                <input type = "hidden" name = "email" value = "">
                                                <div class = "col-sm-12">
													<textarea rows = "15" name = "email_message"
                                                              class = "form-control"
                                                              placeholder = "نص رسالة الـ Email "></textarea>
                                                </div>

                                                <div class = "col-sm-12" style = "margin-top: 10px">
                                                    <button type = "submit"
                                                            class = "btn btn-primary addCategory"
                                                    ">ارسال</button>
                                                    <button type = "button" class = "btn btn-secondary"
                                                            data-dismiss = "modal">أغلاق
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- sms -->
                                    <div class = "tab-pane" id = "colored-rounded-justified-tab20">
                                        <div class = "row">
                                            <form action = "{{route('sendcurrentsms')}}" method = "POST"
                                                  enctype = "multipart/form-data">
                                                {{csrf_field()}}
                                                <input type = "hidden" name = "phone" value = "">
                                                <div class = "col-sm-12">
													<textarea rows = "15" name = "sms_message"
                                                              class = "form-control"
                                                              placeholder = "نص رسالة الـ SMS "></textarea>
                                                </div>

                                                <div class = "col-sm-12" style = "margin-top: 10px">
                                                    <button type = "submit"
                                                            class = "btn btn-primary addCategory"
                                                    ">ارسال</button>
                                                    <button type = "button" class = "btn btn-secondary"
                                                            data-dismiss = "modal">أغلاق
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- /correspondent for one user Modal -->

    </div>

    <!-- javascript -->
@section('script')
    <script type = "text/javascript"
            src = "{{asset('dashboard/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <script type = "text/javascript" src = "{{asset('dashboard/js/plugins/forms/selects/select2.min.js')}}"></script>
    {{--<script type = "text/javascript" src = "{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>--}}


    {{--<script src="https://code.jquery.com/jquery-3.3.1.js" type="text/javascript"></script>--}}
    <script src = "https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type = "text/javascript"></script>
    <script src = "https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"
            type = "text/javascript"></script>
    <script src = "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type = "text/javascript"></script>
    <script src = "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"
            type = "text/javascript"></script>
    <script src = "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"
            type = "text/javascript"></script>
    <script src = "https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js" type = "text/javascript"></script>

    <script type = "text/javascript">
	    $( function () {
		    $( '#example' ).DataTable( {
			                               dom: 'Bfrtip',
			                               buttons: [
				                               // 'copyHtml5',
				                               'excelHtml5',
				                               // 'csvHtml5',
				                               // 'pdfHtml5'
			                               ]
		                               } );
	    } );
    </script>


@endsection



<script type = "text/javascript">

	$( '.openEditmodal' ).on( 'click', function () {
		//get valus
		var id = $( this ).data( 'id' )
		var name = $( this ).data( 'name' )
		var photo = $( this ).data( 'photo' )
		var phone = $( this ).data( 'phone' )
		var email = $( this ).data( 'email' )
		var permission = $( this ).data( 'permission' )
		var block = $( this ).data( 'block' );
		console.log('ssssss',block);

		//set values in modal inputs
		$( "input[name='id']" ).val( id )
		$( "input[name='edit_name']" ).val( name )
		$( "input[name='edit_phone']" ).val( phone )
		$( "input[name='edit_email']" ).val( email )
		var link = "{{asset('dashboard/uploads/users/')}}" + '/' + photo
		$( ".photo" ).attr( 'src', photo )
		$( '.userName' ).text( name )


		//select role
		$( '#permissions option' ).each( function () {
			if ( $( this ).val() == permission ) {
				$( this ).attr( 'selected', '' )
			}
		} );

		//block input check
		if ( block == 1 ) {
			$( '#editActive' ).attr( 'checked', '' )
		}


	} )

	//open send message modal
	$( '.SendMessageUser' ).on( 'click', function () {

		var name = $( this ).data( 'name' );
		var phone = $( this ).data( 'phone' );
		var email = $( this ).data( 'email' );
		var device_id = $( this ).data( 'device_id' );
		$( '.reverName' ).html( name );
		$( 'input[name="phone"]' ).val( phone );
		$( 'input[name="email"]' ).val( email );
		$( 'input[name="device_id"]' ).val( device_id );
	} )

    {{--function resetForm() {--}}
    {{--$( "input, select, textarea, checkbox" ).val( "" );--}}
    {{--$('input[name="_token"]').val('{{ csrf_token() }}');--}}

    {{--}--}}


</script>

<!-- other code -->
<script type = "text/javascript">

	function ChooseFile() {
		$( "input[name='edit_avatar']" ).click()
	}

	function addChooseFile() {
		$( "input[name='avatar']" ).click()
	}

	//stay in current tab after reload
	$( function () {
		// for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
		$( 'a[data-toggle="tab"]' ).on( 'shown.bs.tab', function ( e ) {
			// save the latest tab; use cookies if you like 'em better:
			localStorage.setItem( 'lastTab', $( this ).attr( 'href' ) );
		} );

		// go to the latest tab, if it exists:
		var lastTab = localStorage.getItem( 'lastTab' );
		if ( lastTab ) {
			$( '[href="' + lastTab + '"]' ).tab( 'show' );
		}
	} );

</script>
<!-- /other code -->

@endsection
