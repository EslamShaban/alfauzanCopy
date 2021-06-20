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

		.bold-title{
			font-weight: bold;
			font-size: 14px;
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
			<h5 class = "panel-title">الأقسام</h5>

		</div>

		<!-- buttons -->
		<div class = "panel-body">
			<div class = "row">
				<div class = "col-xs-12">
					<button class = "btn bg-purple-300 btn-block btn-float btn-float-lg" type = "button"><i
							class = "icon-list-numbered"></i>
						<span>عدد طلبات العملاء: {{count($auctions)}} </span>
					</button>
				</div>
			</div>
		</div>
		<!-- /buttons -->

		<table class = "table datatable-basic" data-order = "[]">
			<thead>
			<tr>
				<th>الموقع</th>
				<th>هاتف العميل</th>
				<th>مستلم الطلب</th>
				<th>كل التفاصيل</th>
				<th>تاريخ الاضافه</th>
				<th>التحكم</th>
			</tr>
			</thead>
			<tbody>
			@foreach($auctions as $auction)
				<tr>
					<td>{{$auction->area}}</td>
					<td>{{$auction->client_phone}}</td>
					<td>{{$auction->received_employ}}</td>
					<td><a href = "#" class = "btn btn-primary" data-toggle = "modal"
					       data-target = "#exampleModal2"
					       onclick = "editModel({{$auction}})">كل التفاصيل</a></td>
					<td>{{$auction->created_at->diffForHumans()}}</td>
					<td>


						<!-- delete button -->
						<form action = "{{route('delete_auction')}}" method = "POST">
							{{csrf_field()}}
							<input type = "hidden" name = "id" value = "{{$auction->id}}">
							<li>
								<button type = "submit" class = "btn btn-danger generalDelete">حذف
								</button>
							</li>
						</form>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>

		<!-- info Modal -->
		<div class = "modal fade" id = "exampleModal2" tabindex = "-1" role = "dialog"
		     aria-labelledby = "exampleModalLabel" aria-hidden = "true">
			<div class = "modal-dialog" role = "document">
				<div class = "modal-content">
					<div class = "modal-header">
						<h5 class = "modal-title" id = "exampleModalLabel"> تفاصيل الطلب<span
								class = "userName"></span></h5>
					</div>
					<div class = "modal-body">
						<div class = "row">
							<div class = "col-sm-12 bold-title">نوع العقار</div>
							<div class = "col-sm-12" id = "build_type"></div>
						</div>

						<hr>

						<div class = "row">
							<div class = "col-sm-3 bold-title">الموقع</div>
							<div class = "col-sm-9" id = "project_name"></div>
						</div>

						<hr>

						<div class = "row">

							<div class = "col-sm-3 bold-title">المساحه المطلوبه</div>
							<div class = "col-sm-9" id = "area"></div>
						</div>

						<hr>

						<div class = "row">
							<div class = "col-sm-3 bold-title">النشاط العقارى</div>
							<div class = "col-sm-9" id = "activity"></div>
						</div>

						<hr>

						<div class = "row">
							<div class = "col-sm-12 bold-title">نوع العميل المستأجر</div>
							<div class = "col-sm-12" id = "user_type"></div>
						</div>

						<hr>

						<div class = "row">
							<div class = "col-sm-3 bold-title">رقم جوال العميل</div>
							<div class = "col-sm-9" id = "client_phone"></div>
						</div>

						<hr>

						<div class = "row">
							<div class = "col-sm-3 bold-title">كيفية الوصول للشركة</div>
							<div class = "col-sm-9" id = "company_rout"></div>
						</div>

						<hr>

						<div class = "row">

							<div class = "col-sm-3 bold-title">مستلم الطلب</div>
							<div class = "col-sm-9" id = "received_employ"></div>
						</div>

						<hr>

						<div class = "row">

							<div class = "col-sm-3 bold-title">وظيفة مستلم الطلب</div>
							<div class = "col-sm-9" id = "employ_job"></div>
						</div>

						<hr>

						<div class = "row">
							<div class = "col-sm-3 bold-title">رقم جوال مستلم الطلب</div>
							<div class = "col-sm-9" id = "employ_phone"></div>
						</div>

						<hr>

						<div class = "row">
							<div class = "col-sm-3 bold-title">تاريخ استلم الطلب</div>
							<div class = "col-sm-9" id = "receive_date"></div>
						</div>

						<hr>

						<div class = "row">
							<div class = "col-sm-3 bold-title">توفر الطلب</div>
							<div class = "col-sm-9" id = "auction_available"></div>
						</div>
						<hr>

						<div class = "row">
							<div class = "col-sm-3 bold-title">وضع الطلب وإنهاءه</div>
							<div class = "col-sm-9" id = "order_status"></div>
						</div>
						<hr>

						<div class = "row">
							<div class = "col-sm-12 bold-title">ملاحظات عامة على الطلب</div>
							<div class = "col-sm-12" id = "details"></div>
						</div>

						<hr>

					</div>
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

	function editModel( ob ) {

		//build types
		let builds = JSON.parse( ob.build_type );
		let build_type = '';
		$.each( builds, function ( index, value ) {
			if ( value != "" )
				build_type += value + ' - ';
		} );


		//user types
		let users = JSON.parse( ob.user_type );
		let user_type = '';
		$.each( users, function ( index, value ) {
			if ( value != "" )
				user_type += value + ' - ';
		} );


		$( '#build_type' ).html( build_type );
		$( '#project_name' ).html( ob.project_name );
		$( '#area' ).html( ob.area );
		$( '#activity' ).html( ob.activity );
		$( '#user_type' ).html( user_type );
		$( '#client_phone' ).html( ob.client_phone );
		$( '#company_rout' ).html( ob.company_rout );
		$( '#received_employ' ).html( ob.received_employ );
		$( '#employ_job' ).html( ob.employ_job );
		$( '#employ_phone' ).html( ob.employ_phone );
		$( '#receive_date' ).html( ob.receive_date );
		$( '#auction_available' ).html( ob.auction_available );
		$( '#order_status' ).html( ob.order_status );
		$( '#details' ).html( ob.details );
	}

</script>

<!-- other code -->
<script type = "text/javascript">


</script>
<!-- /other code -->

@endsection
