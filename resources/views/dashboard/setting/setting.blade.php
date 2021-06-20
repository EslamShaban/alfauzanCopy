@extends('dashboard.layout.master')
	
<!-- style -->
@section('style')
<link href="{{asset('dashboard/fileinput/css/fileinput.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('dashboard/fileinput/css/fileinput-rtl.min.css')}}" rel="stylesheet" type="text/css">

@endsection
<!-- /style -->
@section('content')


<div class="row">
	<div class="col-md-12">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h6 class="panel-title">الاعدادات</h6>
				
			</div>

			<div class="panel-body">
				<div class="tabbable">
					<ul class="nav nav-tabs" style="font-size: 13px">
						<!-- site setting -->
						<li class="active"><a href="#basic-tab1" data-toggle="tab">اعدادات الموقع</a></li>
						<!-- social media -->
						<li><a href="#basic-tab2" data-toggle="tab">مواقع التواصل</a></li>
						{{--<!-- email and sms -->--}}
						{{--<li><a href="#basic-tab3" data-toggle="tab">الرسائل و الايميل</a></li>--}}
						<!-- copyright -->
						{{--<li><a href="#basic-tab4" data-toggle="tab">حقوق الموقع </a></li>--}}
						{{--<!-- email template -->--}}
						{{--<li><a href="#basic-tab5" data-toggle="tab">قالب الايميل  </a></li>--}}
						{{--<!-- notification -->--}}
						{{--<li><a href="#basic-tab6" data-toggle="tab">الاشعارات </a></li>--}}
						{{--<!-- api -->--}}
						{{--<li><a href="#basic-tab7" data-toggle="tab">API </a></li>--}}
					</ul>

					<div class="tab-content">

						<!-- site setting -->
						<div class="tab-pane active" id="basic-tab1">
							<div class="row">
								<!-- main setting -->
								<div class="col-md-6">
										<div class="panel panel-flat">
											<div class="panel-heading">
												<h5 class="panel-title">اعدادات عامه </h5>
												<div class="heading-elements">
													<ul class="icons-list">
								                		<li><a data-action="collapse"></a></li>
								                		<li><a data-action="reload"></a></li>
								                	</ul>
							                	</div>
											</div>

											<div class="panel-body">
												<form action="{{route('updatesitesetting')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
													{{csrf_field()}}
													<div class="form-group">
														<label class="col-lg-3 control-label">اسم الموقع:</label>
														<div class="col-lg-9">
															<input type="text" value="{{$SiteSetting->site_name}}" name="site_name" class="form-control" placeholder="اسم الموقع">
														</div>
													</div>
                                                    <div class="form-group">
                                                        <label class="col-lg-3 control-label">رقم الجوال :</label>
                                                        <div class="col-lg-9">
                                                            <input type="text" value="{{$SiteSetting->site_phone}}" name="phone" class="form-control" placeholder="رقم الجوال">
                                                        </div>
                                                    </div>

													<div class="form-group">
														<label class="col-lg-3 control-label">لوجو الموقع:</label>
														<div class="col-lg-6">
															<img src="{{asset('/dashboard/uploads/setting/site_logo/'.$SiteSetting->site_logo)}}" title="اختيار لوجو" onclick="sitelogo()" style="height: 210px; width: 210px;cursor: pointer;border-radius:100%">
															<input type="file" name="logo" id="hidden">
														</div>
													</div>

													<div class="text-left">
														<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
													</div>
												</form>
											</div>
										</div>
								</div>
								<!-- seo setting -->
								<div class="col-md-6">
									{{csrf_field()}}
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h5 class="panel-title">SEO</h5>
											<div class="heading-elements">
												<ul class="icons-list">
							                		<li><a data-action="collapse"></a></li>
							                		<li><a data-action="reload"></a></li>
							                	</ul>
						                	</div>
										</div>
										<div class="panel-body">
											<form action="{{route('updateseo')}}" method="post" class="form-horizontal">
												{{csrf_field()}}

												<div class="form-group">
													<label class="col-lg-3 control-label">وصف الموقع باللغة العربية :</label>
													<div class="col-lg-9">
														<textarea rows="5" cols="5" name="site_description" class="form-control" placeholder="وصف الموقع باللغة العربية">{{$SiteSetting->site_description}}</textarea>
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">وصف الموقع باللغة الانجليزية :</label>
													<div class="col-lg-9">
														<textarea rows="5" cols="5" name="site_description_en" class="form-control" placeholder="وصف الموقع باللغة الانجليزية">{{$SiteSetting->site_description_en}}</textarea>
													</div>
												</div>

												<div class="form-group">
													<label class="col-lg-3 control-label">الاحكام والشروط باللغة العربية :</label>
													<div class="col-lg-9">
														<textarea rows="5" cols="5" name="site_copyrigth" class="form-control" placeholder="الاحكام والشروط باللغة العربية">{{$SiteSetting->site_copyrigth}}</textarea>
													</div>
												</div>

                                                <div class="form-group">
                                                    <label class="col-lg-3 control-label">الاحكام والشروط باللغة الانجليزية :</label>
                                                    <div class="col-lg-9">
                                                        <textarea rows="5" cols="5" name="site_copyrigth_en" class="form-control" placeholder="الاحكام والشروط باللغة الانجليزية">{{$SiteSetting->site_copyrigth_en}}</textarea>
                                                    </div>
                                                </div>

												<div class="text-left">
													<button type="submit" class="btn btn-primary">حفظ التعديلات</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- social media -->
						<div class="tab-pane" id="basic-tab2">
								<div class="col-md-12">
									{{csrf_field()}}
									<div class="panel panel-flat">
										<div class="panel-heading">
											<h5 class="panel-title">مواقع التواصل </h5>
											<div class="heading-elements">
												<ul class="icons-list">
													{{--<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> اضافة موقع </button>--}}
							                	</ul>
						                	</div>
										</div>

										<div class="panel-body">

											<table class="table datatable-basic">
												<thead>
													<tr>
														<th>اللوجو</th>
														<th>الاسم</th>
														<th>اللينك</th>
														<th>تاريخ الاضافه</th>
														<th>التحكم</th>
													</tr>
												</thead>
												<tbody>
													@foreach($socials as $social)
														<tr>
															<td><img src="{{asset('dashboard/uploads/socialicon/'.$social->logo)}}" style="width:40px;height: 40px" class="img-circle" alt=""></td>
															<td>{{$social->name}}</td>
															<td>{{str_limit($social->link,30)}}</td>
															<td>{{$social->created_at->diffForHumans()}}</td>
															<td>
															<ul class="icons-list">
																<li class="dropdown">
																	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
																		<i class="icon-menu9"></i>
																	</a>

																	<ul class="dropdown-menu dropdown-menu-right">
																		<li>
																			<a href="#" data-toggle="modal" data-target="#exampleModal2" class="openEditmodal" 
																			data-id  ="{{$social->id}}" 
																			data-name="{{$social->name}}"
																			data-link="{{$social->link}}"
																			data-logo="{{$social->logo}}">
																			<i class="icon-pencil7"></i>تعديل
																			</a>
																		</li>
																		{{--<form action="{{route('deletesocial')}}" method="post">--}}
																			{{--{{csrf_field()}}--}}
																			{{--<input type="hidden" name="id" value="{{$social->id}}">--}}
																			{{--<li><button type="submit" class="generalDelete reset"><i class="icon-trash"></i>حذف</button></li>--}}
																		{{--</form>--}}
																	</ul>
																</li>
															</ul>
															</td>
														</tr>
													@endforeach
													@if(count($socials) == 0) <tr><td></td><td></td><td>لا توجد مواقع تواصل</td></tr>  @endif
												</tbody>
											</table>

										</div>
									</div>
								</div>
						</div>
					</div>
				</div>
			</div>
		</div>



		{{--<!-- Add Modal -->--}}
		{{--<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
		  {{--<div class="modal-dialog" role="document">--}}
		    {{--<div class="modal-content">--}}
		      {{--<div class="modal-header">--}}
		        {{--<h5 class="modal-title" id="exampleModalLabel">أضافة موقع تواصل جديد</h5>--}}
		      {{--</div>--}}
		      {{--<div class="modal-body">--}}
		        {{--<div class="row">--}}
		        	{{--<form action="{{route('addsocials')}}" method="POST" enctype="multipart/form-data">--}}
		        		{{--{{csrf_field()}}--}}
		        		{{--<div class="col-sm-3 text-center">--}}
		        			{{--<label style="margin-bottom: 0">اختيار لوجو</label>--}}
		        			{{--<i class="icon-camera"  onclick="add()" style="cursor: pointer;"></i>--}}
		        			{{--<div class="images-upload-block">--}}
		        				{{--<input type="file" name="add_logo" class="image-uploader" id="hidden" required>--}}
		        			{{--</div>--}}
		        		{{--</div>--}}

		        		{{--<div class="col-sm-9" style="margin-top: 35px">--}}
		        			{{--<input type="text" name="site_name" class="form-control" placeholder="اسم الموقع " required>--}}
		        			{{--<input type="text" name="site_link" class="form-control" placeholder="لينك الموقع " required>--}}
		        		{{--</div>--}}

				        {{--<div class="col-sm-12" style="margin-top: 10px">--}}
					      	{{--<button type="submit" class="btn btn-primary addCategory"">اضافه</button>--}}
					        {{--<button type="button" class="btn btn-secondary" onclick = "resetForm()" data-dismiss="modal">أغلاق</button>--}}
				        {{--</div>--}}

		        	{{--</form>--}}
		        {{--</div>--}}
		      {{--</div>--}}

		    {{--</div>--}}
		  {{--</div>--}}
		{{--</div>--}}
		{{--<!-- /Add Modal -->--}}

		<!-- Edit Modal -->
		<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel"> تعديل : <span class="editingName"></span> </h5>
		      </div>
		      <div class="modal-body">
		        <div class="row">
		        	<form action="{{route('updatesocials')}}" method="post" enctype="multipart/form-data">

		        		<!-- token and born id -->
		        		{{csrf_field()}}
		        		<input type="hidden" name="id" value="">
		        		<!-- /token and born id -->

		        		<div class="col-sm-3 text-center">
		        			{{--<label>اختيار لوجو</label>--}}
		        			<img src="" class="replaceImage" style="width: 120px;height: 120px;"><!--cursor: pointer" onclick="edit()">-->
		        			{{--<input type="file" name="edit_logo" style="display: none;">--}}
		        		</div>
		        		<div class="col-sm-9" style="margin-top: 18px">
		        			{{--<label>اسم الموقع</label>--}}
		        			{{--<input type="text" name="edit_site_name" class="form-control">--}}
		        			<label>لينك الموقع</label>
		        			<input type="text" name="edit_site_link" class="form-control">
		        		</div>

				      <div class="col-sm-12" style="margin-top: 10px">
				      	<button type="submit" class="btn btn-primary" >حفظ التعديلات</button>
				        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
				      </div>
		        	</form>
		        </div>
		      </div>
		    </div>
		  </div>
		</div>
		<!-- /Edit Modal -->



	</div>
</div>


<!-- javascript -->
@section('script')
<script src="{{asset('dashboard/bgrins/spectrum.js')}}"></script>

<script type="text/javascript">

	//open edit modal
	$(document).on('click','.openEditmodal',function(){
		//get valus
		var id    = $(this).data('id')
		var name  = $(this).data('name')
		var link  = $(this).data('link')
		var logo  = $(this).data('logo')

		//set values in modal inputs
		$("input[name='id']")           .val(id)
		$("input[name='edit_site_name']")    .val(name)
		$("input[name='edit_site_link']")    .val(link)
		var link = "{{asset('dashboard/uploads/socialicon/')}}" +'/'+ logo
		$(".replaceImage").attr('src',link)
		$('.editingName').text(name)
	})

	//select logo
	function add(){$("input[name='add_logo']").click()}
	function sitelogo(){$("input[name='logo']").click()}
	function edit(){$("input[name='edit_logo']").click()}
    function resetForm() {
        $( "input, select, textarea, checkbox" ).val( "" );

        $('input[name="_token"]').val('{{ csrf_token() }}');
    }

	//stay in current tab after reload
	$(function() {
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


$(document).on('change','#color',function(){
	console.log($(this).val());
});
</script>

<script type="text/javascript" src="{{asset('dashboard/fileinput/js/fileinput.min.js')}}"></script>

@endsection
<!-- /javascript -->

@endsection
