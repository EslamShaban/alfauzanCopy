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

		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			font-family: Arial;
		}

		.header {
			text-align: center;
			padding: 32px;
		}

		.row {
			display: -ms-flexbox; /* IE10 */
			display: flex;
			-ms-flex-wrap: wrap; /* IE10 */
			flex-wrap: wrap;
			padding: 0 4px;
		}

		/* Create four equal columns that sits next to each other */
		.column {
			-ms-flex: 25%; /* IE10 */
			flex: 25%;
			max-width: 25%;
			padding: 0 4px;
		}

		.column img {
			margin-top: 8px;
			vertical-align: middle;
		}

		/* Responsive layout - makes a two column-layout instead of four columns */
		@media screen and (max-width: 800px) {
			.column {
				-ms-flex: 50%;
				flex: 50%;
				max-width: 50%;
			}
		}

		/* Responsive layout - makes the two columns stack on top of each other instead of next to each other */
		@media screen and (max-width: 600px) {
			.column {
				-ms-flex: 100%;
				flex: 100%;
				max-width: 100%;
			}
		}
	</style>
@endsection
<!-- /style -->
@section('content')
	<div class = "panel panel-flat">
		<div class = "panel-heading">
			<h5 class = "panel-title">قائمة الصور</h5>
			
		</div>

		<!-- buttons -->
		<div class = "panel-body">
			<div class = "row">
				<div class = "col-xs-4">
					<button class = "btn bg-blue btn-block btn-float btn-float-lg openAddModal" type = "button"
					        data-toggle = "modal" data-target = "#exampleModal"><i class = "icon-plus3"></i> <span>اضافة صور</span>
					</button>

				</div>
				<div class = "col-xs-4">
					<button class = "btn bg-purple-300 btn-block btn-float btn-float-lg" type = "button"><i
							   class = "icon-list-numbered"></i> <span>عدد الصور: <span id="img_num"> </span>
					</button>
				</div>
				<div class = "col-xs-4">
					<a href = "{{route('logout')}}" class = "btn bg-warning-400 btn-block btn-float btn-float-lg"
					   type = "button"><i class = "icon-switch"></i> <span>خروج</span></a>
				</div>
			</div>
		</div>
		<!-- /buttons -->


		<div class = "modal fade" id = "exampleModal" tabindex = "-1" role = "dialog"
		     aria-labelledby = "exampleModalLabel" aria-hidden = "true">
			<div class = "modal-dialog" role = "document">
				<div class = "modal-content">
					<div class = "modal-header">
						<h5 class = "modal-title" id = "exampleModalLabel">أضافة صور</h5>
					</div>
					<div class = "modal-body">
						<div class = "row">
							<form action = "{{route('add_image')}}" method = "POST"
							      enctype = "multipart/form-data">
								{{csrf_field()}}
								<input type = "hidden" value = "{{$ad->id}}" name = "ad_id">
								<div class = "row">
									<div class = "col-sm-12 text-center">
										<label style = "margin-bottom: 0 ; float: right">اختيار صور </label>
                                        <br>
										<i class = "icon-camera"  onclick = "addChooseFile()"
										   style = "cursor: pointer; float: right"></i><br>
										<div class = "images-upload-block">
											<input type = "file" name = "avatar[]" class = "image-uploader"
											       multiple required>
										</div>
									</div>

								</div>


								<div class = "col-sm-12" style = "margin-top: 10px">
									<button type = "submit" class = "btn btn-primary addCategory"
									">اضافه</button>
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


		<div class = "row">
			<div class = "row">
				@foreach($images as $image)

					<div style = "height:200px;" class = "col-xs-3 profile-img-container">
						<img src = "{{asset('dashboard/uploads/adss/'.$image->image)}}"
						     style = "width:100%;    height: 95%;">
						<a class="t-remove" href = "#"><span data-id = "{{$image->id}}" class = "glyphicon glyphicon-remove"
						                    style = "font-size: 74px;color: #eb5555;"></span></a>
					</div>

				@endforeach

			</div>

		</div>
	</div>


	<script type = "text/javascript">

        var img_num={{count($images)}};
        $('#img_num').empty();
        $('#img_num').html(img_num);


         $('.glyphicon-remove').on('click', function () {
             var  item = $(this);
             var result = confirm('هل تريد تأكيد الحذف؟ ');
             if (result == false) {
                 e.preventDefault()
             } else {
                 // $(this).parent().parent().hide();
                 var id = $(this).data('id');
                 $.ajax({
                     type: "GET",
                     url: "{{route('delete_image')}}",
                     data: {id: id},
                     cache: false,
                     success: function (data) {

                         if (data.status == 1){
                             item.parent().parent().hide();
                             img_num--;
                             $('#img_num').empty();
                             $('#img_num').html(img_num);
                         }else{
                             alert('لا يمكن حذف اخر صورة');
                         }
                     }
                 });
             }

         })


	</script>

	<!-- other code -->
	<script type = "text/javascript">

         function ChooseFile() {
             $("input[name='edit_avatar']").click()
         }

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
