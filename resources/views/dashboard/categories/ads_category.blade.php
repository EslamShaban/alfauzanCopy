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
	</style>
@endsection
<!-- /style -->
@section('content')
	<div class = "panel panel-flat">
		<div class = "panel-heading">
			<h5 class = "panel-title">أقسام المخططات</h5>
			
		</div>

		<!-- buttons -->
		<div class = "panel-body">
			<div class = "row">
				<div class = "col-xs-4">
					<button class = "btn bg-blue btn-block btn-float btn-float-lg openAddModal"
					        onclick = "initialize()" type = "button" data-toggle = "modal"
					        data-target = "#exampleModal"><i class = "icon-plus3"></i> <span>اضافة  قسم</span>
					</button>
				</div>
				<div class = "col-xs-4">
					<button class = "btn bg-purple-300 btn-block btn-float btn-float-lg" type = "button"><i
							   class = "icon-list-numbered"></i> <span>عدد : {{count($categories)}} </span>
					</button>
				</div>
				<div class = "col-xs-4">
					<a href = "{{route('logout')}}" class = "btn bg-warning-400 btn-block btn-float btn-float-lg"
					   type = "button"><i class = "icon-switch"></i> <span>خروج</span></a>
				</div>
			</div>
		</div>
		<!-- /buttons -->

		<table class = "table datatable-basic"  data-order="[]">
			<thead>
			<tr>
				<th>الإسم</th>
				<th>الإسم إنجليزى</th>
				<th>تاريخ الاضافه</th>
				<th>التحكم</th>
			</tr>
			</thead>
			<tbody>
			@foreach($categories as $p)
				<tr>
					<td>{{$p->name_ar}}</td>
					<td>{{$p->name_en}}</td>
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
										   class = "openEditmodal" data-id = "{{$p->id}}"
										   data-name = "{{$p->name_ar}}" data-name_en = "{{$p->name_en}}"
										   data-project_id = "{{$p->project_id}}">
											<i class = "icon-pencil7"></i>تعديل
										</a>
									</li>


									<!-- delete button -->
									<form action = "{{route('delete_p_category')}}" method = "POST">
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

		<!-- Add category Modal -->
		<div class = "modal fade" id = "exampleModal" tabindex = "-1" role = "dialog"
		     aria-labelledby = "exampleModalLabel" aria-hidden = "true">
			<div class = "modal-dialog" role = "document">
				<div class = "modal-content">
					<div class = "modal-header">
						<h5 class = "modal-title" id = "exampleModalLabel">أضافة قسم </h5>
					</div>
					<div class = "modal-body">
						<div class = "row">
							<form action = "{{route('add_p_category')}}" method = "POST">
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
										<label>المخطط</label>
										<select name = "project_id" class = "form-control"
										        style = "margin-bottom: 5px" required>
											<option disabled hidden selected>--إختر المخطط --</option>
											@foreach($projects as $project)
												<option value = "{{$project->id}}">{{$project->name_ar}}</option>
											@endforeach
										</select>
									</div>

								</div>


								<div class = "col-sm-12" style = "margin-top: 10px">
									<button type = "submit"
									        class = "btn btn-primary addCategory">
										اضافه
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
		<!-- /Add user Modal -->

		<!-- Edit user Modal -->
		<div class = "modal fade" id = "exampleModal2" tabindex = "-1" role = "dialog"
		     aria-labelledby = "exampleModalLabel" aria-hidden = "true">
			<div class = "modal-dialog" role = "document">
				<div class = "modal-content">
					<div class = "modal-header">
						<h5 class = "modal-title" id = "exampleModalLabel"> تعديل قسم : <span
								   class = "userName"></span></h5>
					</div>
					<div class = "modal-body">
						<form action = "{{route('update_p_category')}}" method = "post">

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
								<div class = "col-sm-12" style = "margin-top: 10px" required>
									<label>المخطط</label>
									<select name = "edit_project_id" id="edit_project_id" class = "form-control"
									        style = "margin-bottom: 5px">
										<option disabled hidden selected>--إختر المخطط --</option>
										@foreach($projects as $project)
											<option value = "{{$project->id}}">{{$project->name_ar}}</option>
										@endforeach
									</select>
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

    $('.openEditmodal').on('click', function () {
        //get valus
        var id = $(this).data('id')
        var name = $(this).data('name')
        var name_en = $(this).data('name_en')
        var project_id = $(this).data('project_id')
        //set values in modal inputs
        $("input[name='id']").val(id)
        $("input[name='edit_name']").val(name)
        $("input[name='edit_name_en']").val(name_en)
        $('#edit_project_id option').each(function () {

            if ($(this).val() == project_id) {
                $(this).attr('selected', '')
            }
        });
    })

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
</script>
<!-- /other code -->

@endsection
