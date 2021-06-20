@extends('dashboard.layout.master')

<!-- style -->
@section('style')

	<style>
		.panel-body + .dataTables_wrapper {
			padding-top: 10px;
			overflow: auto;
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


	<div class = "row">
		<div class = "col-md-12">
			<div class = "panel panel-flat">
				<div class = "panel-heading">
					<h6 class = "panel-title">طلبات الحجز</h6>

				</div>
				<!-- buttons -->
				<div class = "panel-body">
					<div class = "row">
						<div class = "col-xs-4">
							<a href = "{{route('reasons')}}"
							   class = "btn bg-blue btn-block btn-float btn-float-lg openAddModal"
							   type = "button"
							   data-target = "#exampleModal"><i class = "icon-plus3"></i>
								<span>اهداف الحجز</span>
							</a>
						</div>
						<div class = "col-xs-4">
							<button class = "btn bg-purple-300 btn-block btn-float btn-float-lg" type = "button">
								<i
									class = "icon-list-numbered"></i>
								<span>عدد الطلبات: {{count($orders)}} </span>
							</button>
						</div>
						<div class = "col-xs-4">
							<a href = "{{route('logout')}}"
							   class = "btn bg-warning-400 btn-block btn-float btn-float-lg"
							   type = "button"><i class = "icon-switch"></i> <span>خروج</span></a>
						</div>

					</div>
				</div>


				<table class = "table table-hover display" id = "example" data-order = "[]">
					<thead>
					<tr>
						<th>الاسم</th>
						<th>البريد الالكترونى</th>
						<th>رقم الهاتف</th>
						<th>اسم الاعلان</th>
						<th>هدف الحجز</th>
						<th>الحالة</th>
						<th>التحكم</th>
					</tr>
					</thead>
					<tbody>
					@foreach($orders as $order)
						<tr>
							<td>{{$order->name}}</td>

							<td>{{$order->user->email}}</td>

							<td>{{$order->phone}}</td>

							<td>{{$order->ads->name_ar}}</td>

							<td>{{$order->reason->name_ar}}</td>


							@if($order->accept == 0)
								<td>بانتظار التفعيل</td>
							@elseif($order->accept==1)
								<td>تم القبول</td>
							@else
								<td>تم الرفض</td>
							@endif


							<td style = "min-width: 170px">

								@if($order->accept==0)
									<form action = "{{route('accept_order')}}"
									      method = "post" class = " display-inline-block">
										{{csrf_field()}}
										<input type = "hidden" value = "{{$order->id}}"
										       name = "id">
										<button type = "submit"
										        onclick = "return confirm('هل تريد قبول الطلب ؟');"
										        class = "btn btn-xs btn-success "
										        name = "">قبول
										</button>
									</form>

									<form action = "{{route('refuse_order')}}"
									      method = "post" class = "display-inline-block">
										{{csrf_field()}}
										<input type = "hidden" value = "{{$order->id}}"
										       name = "id">
										<button type = "submit"
										        onclick = "return confirm('هل تريد رفض الطلب ؟');"
										        class = "btn btn-xs btn-primary "
										        name = "">رفض
										</button>
									</form>

							@endif
							<!-- delete button -->
								<form action = "{{route('delete_order')}}"
								      method = "post" class = "display-inline-block">
									{{csrf_field()}}
									<input type = "hidden" value = "{{$order->id}}"
									       name = "id">
									<button type = "submit"
									        onclick = "return confirm('هل تريد حذف الطلب ؟');"
									        class = "btn btn-xs btn-danger "
									        name = "">حذف
									</button>
								</form>
							</td>

						</tr>
					@endforeach
					</tbody>
				</table>

			</div>

		</div>
	</div>
	<script type = "text/javascript"
	        src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyD8cUy05UAvneVcdyAUodgt2qk2I2uzHdw&libraries=places">
	</script>
	<!-- javascript -->
@section('script')
	<script type = "text/javascript"
	        src = "{{asset('dashboard/js/plugins/tables/datatables/datatables.min.js')}}"></script>
	<script type = "text/javascript" src = "{{asset('dashboard/js/plugins/forms/selects/select2.min.js')}}"></script>
	{{--<script type = "text/javascript" src = "{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>--}}


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

				                           // "scrollX": true,
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



<!-- /javascript -->

@endsection

{{--<!-- javascript -->--}}
{{--@section('script')--}}


{{--<script src="https://code.jquery.com/jquery-3.3.1.js" type="text/javascript"></script>--}}
{{--<script type = "text/javascript"--}}
{{--src = "{{asset('dashboard/js/plugins/tables/datatables/datatables.min.js')}}"></script>--}}
{{--<script type = "text/javascript" src = "{{asset('dashboard/js/plugins/forms/selects/select2.min.js')}}"></script>--}}
{{--<script type = "text/javascript" src = "{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>--}}


{{--<script type = "text/javascript"--}}
{{--src = "{{asset('dashboard/js/plugins/tables/datatables/datatables.min.js')}}"></script>--}}
{{--<script type = "text/javascript" src = "{{asset('dashboard/js/plugins/forms/selects/select2.min.js')}}"></script>--}}
{{--<script type = "text/javascript" src = "{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>--}}


{{--<script src="https://code.jquery.com/jquery-3.3.1.js" type="text/javascript"></script>--}}
{{--<script src = "https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type = "text/javascript"></script>--}}
{{--<script src = "https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"--}}
{{--type = "text/javascript"></script>--}}
{{--<script src = "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type = "text/javascript"></script>--}}
{{--<script src = "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"--}}
{{--type = "text/javascript"></script>--}}
{{--<script src = "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"--}}
{{--type = "text/javascript"></script>--}}
{{--<script src = "https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js" type = "text/javascript"></script>--}}

{{--<script type = "text/javascript">--}}
{{--$( function () {--}}
{{--$( '#example' ).DataTable( {--}}

{{--// "scrollX": true,--}}
{{--dom: 'Bfrtip',--}}
{{--buttons: [--}}
{{--// 'copyHtml5',--}}
{{--'excelHtml5',--}}
{{--// 'csvHtml5',--}}
{{--// 'pdfHtml5'--}}
{{--]--}}
{{--} );--}}
{{--} );--}}
{{--</script>--}}


{{--@endsection--}}
