@extends('dashboard.layout.master')

<!-- style -->
@section('style')

@endsection
<!-- /style -->

@section('content')


	<div class = "row">
		<div class = "col-md-12">
			<div class = "panel panel-flat">
				<div class = "panel-heading">
					<h6 class = "panel-title">طلبات العمل</h6>
					
				</div>
				<!-- buttons -->
				<div class = "panel-body">
					<div class = "row">

						<div class = "col-xs-6">
							<button class = "btn bg-purple-300 btn-block btn-float btn-float-lg" type = "button">
								<i
									   class = "icon-list-numbered"></i>
								<span>عدد الطلبات: {{count($jobs)}} </span>
							</button>
						</div>
						<div class = "col-xs-6">
							<a href = "{{route('logout')}}"
							   class = "btn bg-warning-400 btn-block btn-float btn-float-lg"
							   type = "button"><i class = "icon-switch"></i> <span>خروج</span></a>
						</div>

					</div>
				</div>
				<!-- /buttons -->
				<div class = "panel-body">
					<div class = "tabbable">


						<!--  reports  -->
						<div class = "tab-pane" id = "basic-tab2">

							<table class = "table datatable-basic"  data-order="[]">
								<thead>
								<tr>
									<th>الاسم</th>
									<th>البريد الالكترونى</th>
									<th>رقم الهاتف</th>
									<th>السيرة الذاتية</th>
									<th>التفاصيل</th>
									<th>التحكم</th>
								</tr>
								</thead>
								<tbody>
								@foreach($jobs as $job)
									<tr>
										<td>{{$job->name}}</td>

										<td>{{$job->email}}</td>

										<td>{{$job->phone}}</td>
										
										<td><a  target="_blank" href="{{asset('dashboard/uploads/cv/'.$job->cv)}}">السيرة الذاتية</a></td>

										{{--<td>{{$job->location}}</td>--}}

										<td>{{$job->details}}</td>
										<td>
											<form action = "{{route('delete_job')}}"
											      method = "post">
												{{csrf_field()}}
												<input type = "hidden" value = "{{$job->id}}"
												       name = "id">
												<button type = "submit"
												        class = "btn btn-xs btn-danger generalDelete"
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
			</div>

		</div>
	</div>

    <!-- javascript -->
@section('script')
    <script type = "text/javascript"
            src = "{{asset('dashboard/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <script type = "text/javascript" src = "{{asset('dashboard/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script type = "text/javascript" src = "{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>
@endsection



<!-- javascript -->
@section('script')
	<script type = "text/javascript">
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

@endsection
<!-- /javascript -->

@endsection
