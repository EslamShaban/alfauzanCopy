<!DOCTYPE html>
<html>

<head>
	<link href = "https://fonts.googleapis.com/icon?family=Material+Icons" rel = "stylesheet">
	<link rel = "stylesheet"
	      href = "https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-alpha.1/css/materialize.min.css">
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0"/>
	<title>
		google form
	</title>
	<style>
		body {
			direction: rtl;
			text-align: right;
			background: #f7f7f7;
		}

		label {
			display: flex;
		}

		label span {
			padding-left: 0 !important;
			padding-right: 30px;
		}

		label span::after {
			right: 0 !important;
			left: unset !important;
		}

		label span::before {
			right: 11px !important;
			left: unset !important;
		}

		label.radio span::before {
			right: 0 !important;
			left: unset !important;
		}

		input[type=text].text-in {
			height: 27px !important;
		}

		.form-group {
			margin-bottom: 40px;
		}

		.select-wrapper .caret {
			left: 0;
			right: unset;
		}

		input[type=number]::-webkit-inner-spin-button,
		input[type=number]::-webkit-outer-spin-button {
			-webkit-appearance: none;
			margin: 0;
		}

		h5 {
			font-weight: bold;
			font-size: 17px;
		}

		.in-form {
			background: #fff;
			padding: 15px;
			margin: 20px 0;
			border-radius: 20px;
		}
	</style>
</head>

<body>
<div class = "form-google">
	<div class = "container">
		<div class = "in-form">
			<div class = "title">
				<h4>طلبات العملاء</h4>
				<p>نموذج لاستقبال اي طلب عقاري من العملاء بواسطة موظفي شركة علي الفوزان واولاده العقارية</p>
				<span class = "red-text">* مطلوب</span>
			</div>
			<form action = "{{route('auction')}}" method = "post">
				{{csrf_field()}}
				<div class = "form-group">
					<h5>نوع العقار <span class = "red-text">*</span></h5>
					<p>
						<label>
							<input type = "checkbox" name = "build_type[]" value = "مصنع" class = "filled-in"/>
							<span>مصنع</span>
						</label>
					</p>
					<p>
						<label>
							<input type = "checkbox" name = "build_type[]" value = "مستودع" class = "filled-in"/>
							<span>مستودع</span>
						</label>
					</p>
					<p>
						<label>
							<input type = "checkbox" name = "build_type[]" value = "ورشة" class = "filled-in"/>
							<span>ورشة</span>
						</label>
					</p>
					<p>
						<label>
							<input type = "checkbox" name = "build_type[]" value = "حوش" class = "filled-in"/>
							<span>حوش</span>
						</label>
					</p>
					<p>
						<label>
							<input type = "checkbox" name = "build_type[]" value = "أرض فضاء"
							       class = "filled-in"/>
							<span>أرض فضاء</span>
						</label>
					</p>
					<p>
						<label>
							<input type = "checkbox" name = "build_type[]" value = "مكتب إداري"
							       class = "filled-in"/>
							<span>مكتب إداري</span>
						</label>
					</p>
					<p>
						<label>
							<input type = "checkbox" name = "build_type[]" value = "معرض" class = "filled-in"/>
							<span>معرض</span>
						</label>
					</p>
					<p>
						<label>
							<input type = "checkbox" name = "build_type[]" value = "غرفة سكنية"
							       class = "filled-in"/>
							<span>غرفة سكنية</span>
						</label>
					</p>
					<p>
						<label>
							<input type = "checkbox" name = "build_type[]" value = "استديو سكني"
							       class = "filled-in"/>
							<span>استديو سكني</span>
						</label>
					</p>
					<p>
						<label>
							<input type = "checkbox" name = "build_type[]" value = "شقة سكنية"
							       class = "filled-in"/>
							<span>شقة سكنية</span>
						</label>
					</p>
					<p>
						<label>
							<input type = "checkbox" name = "build_type[]" value = "محلات" class = "filled-in"/>
							<span>محلات</span>
						</label>
					</p>
					<p>
						<label>
							<input type = "checkbox" name = "build_type[]" value = "ارض صناعية"
							       class = "filled-in"/>
							<span>ارض صناعية</span>
						</label>
					</p>
					<p>
						<label>
							<input type = "checkbox" name = "build_type[]" value = "أخرى" class = "filled-in"/>
							<span>أخري :</span>
							<input id = "other" name = "build_type[]" type = "text" class = "validate text-in">
						</label>
					</p>
				</div>
				<div class = "form-group">
					<h5>الموقع <span class = "red-text">*</span></h5>
					<select name="project_name">
						<option value = "" disabled selected>Choose your option</option>

						@foreach($projects as $project)
							<option value = "{{$project->name_ar}}">{{$project->name_ar}}</option>
						@endforeach
					</select>
				</div>
				<div class = "form-group">
					<h5>المساحة المطلوبة</h5>
					<input type = "text" name = "area" class = "validate text-in">
				</div>
				<div class = "form-group">
					<h5>النشاط للعقار</h5>
					<input name = "activity" type = "text" class = "validate text-in">
				</div>
				<div class = "form-group">
					<h5>نوع العميل المستأجر <span class = "red-text">*</span></h5>
					<p>
						<label>
							<input type = "checkbox" name = "user_type[]" value = "شركة " class = "filled-in"
							       checked = "checked"/>
							<span>شركة</span>
						</label>
					</p>
					<p>
						<label>
							<input type = "checkbox" name = "user_type[]" value = "مؤسسة" class = "filled-in"
							       checked = "checked"/>
							<span>مؤسسة</span>
						</label>
					</p>
					<p>
						<label>
							<input type = "checkbox" name = "user_type[]" value = "تاجر" class = "filled-in"
							       checked = "checked"/>
							<span>تاجر</span>
						</label>
					</p>
					<p>
						<label>
							<input type = "checkbox" name = "user_type[]" value = "مستثمر" class = "filled-in"
							       checked = "checked"/>
							<span>مستثمر</span>
						</label>
					</p>
					<p>
						<label>
							<input type = "checkbox" name = "user_type[]" value = "فردي" class = "filled-in"
							       checked = "checked"/>
							<span>فردي</span>
						</label>
					</p>
					<p>
						<label>
							<input type = "checkbox" name = "user_type[]" value = "اخرى" class = "filled-in"
							       checked = "checked"/>
							<span>أخري :</span>
							<input type = "text" name = "user_type[]" class = "validate text-in">
						</label>
					</p>
				</div>
				<div class = "form-group">
					<h5>رقم جوال العميل<span class = "red-text">*</span></h5>
					<input type = "number" name = "client_phone" class = "validate">
				</div>
				<div class = "form-group">
					<h5>كيفية الوصول للشركة</h5>
					<input type = "text" name = "company_rout" class = "validate">
				</div>
				<div class = "form-group">
					<h5>مستلم الطلب (موظفي شركة علي الفوزان) <span>*</span></h5>
					<input type = "text" name = "received_employ" class = "validate">
				</div>
				<div class = "form-group">
					<h5>وظيفة مستلم الطلب في الشركة</h5>
					<input type = "text" name = "employ_job" class = "validate">
				</div>
				<div class = "form-group">
					<h5>رقم جوال مستلم الطلب <span>*</span></h5>
					<input type = "text" name = "employ_phone" class = "validate">
				</div>
				<div class = "form-group">
					<h5>تاريخ استلم الطلب <span>*</span></h5>
					<input type = "text" name = "receive_date" class = "datepicker">
				</div>
				<div class = "form-group">
					<h5>توفر الطلب<span>*</span></h5>
					<p>
						<label class = "radio">
							<input class = "with-gap" name = "auction_available" value = "متوفر" type = "radio"/>
							<span>متوفر</span>
						</label>
					</p>
					<p>
						<label class = "radio">
							<input class = "with-gap" name = "auction_available" value = "غير متوفر"
							       type = "radio"/>
							<span>غير متوفر</span>
						</label>
					</p>
					<p>
						<label class = "radio">
							<input class = "with-gap" name = "auction_available" value = "بانتظار الاخلاء"
							       type = "radio"/>
							<span>بانتظار الاخلاء</span>
						</label>
					</p>
					<p>
						<label class = "radio">
							<input class = "with-gap" name = "auction_available" value = "جاري البحث والتاكد"
							       type = "radio"/>
							<span>جاري البحث والتاكد</span>
						</label>
					</p>
					<p>
						<label class = "radio">
							<input class = "with-gap" name = "auction_available"
							       value = "تم تحويل الطلب لادارة التأجير او موظف أخر" type = "radio"/>
							<span>تم تحويل الطلب لادارة التأجير او موظف أخر</span>
						</label>
					</p>
					<p>
						<label class = "radio">
							<input class = "with-gap" name = "auction_available" value = "اخرى" type = "radio"/>
							<span>أخرى:</span>
							<input type = "text" name = "auction_available" class = "validate text-in">
						</label>
					</p>
				</div>

				<div class = "form-group">
					<h5>وضع الطلب وإنهاءه<span>*</span></h5>
					<p>
						<label class = "radio">
							<input class = "with-gap" name = "order_status" value = "تم التأجير للعميل"
							       type = "radio"/>
							<span>تم التأجير للعميل </span>
						</label>
					</p>
					<p>
						<label class = "radio">
							<input class = "with-gap" name = "order_status" value = "تحت الاجراء للسداد"
							       type = "radio"/>
							<span>تحت الاجراء للسداد</span>
						</label>
					</p>
					<p>
						<label class = "radio">
							<input class = "with-gap" name = "order_status" value = "تحت الاجراء لتوثيق العقد"
							       type = "radio"/>
							<span>تحت الاجراء لتوثيق العقد</span>
						</label>
					</p>
					<p>
						<label class = "radio">
							<input class = "with-gap" name = "order_status" value = "جاري البحث عن العقار"
							       type = "radio"/>
							<span>جاري البحث عن العقار</span>
						</label>
					</p>
					<p>
						<label class = "radio">
							<input class = "with-gap" name = "order_status"
							       value = "تم الاعتذار للعميل لعدم التوفر" type = "radio"/>
							<span>تم الاعتذار للعميل لعدم التوفر</span>
						</label>
					</p>
					<p>
						<label class = "radio">
							<input class = "with-gap" name = "order_status" value = "تم التأجيل لفترة محددة"
							       type = "radio"/>
							<span>تم التأجيل لفترة محددة</span>
						</label>
					</p>
					<p>
						<label class = "radio">
							<input class = "with-gap" name = "order_status" value = "جاري عرض العقار على العميل"
							       type = "radio"/>
							<span>جاري عرض العقار على العميل</span>
						</label>
					</p>
					<p>
						<label class = "radio">
							<input class = "with-gap" name = "order_status"
							       value = "بانتظار التأكيد من العميل حيال الاستئجار" type = "radio"/>
							<span>بانتظار التأكيد من العميل حيال الاستئجار</span>
						</label>
					</p>
					<p>
						<label class = "radio">
							<input class = "with-gap" name = "order_status" value = "أخرى" type = "radio"/>
							<span>أخرى:</span>
							<input name = "order_status" type = "text" class = "validate text-in">
						</label>
					</p>
				</div>
				<div class = "form-group">
					<h5>ملاحظات عامة على الطلب</h5>
					<p>
						<label class = "radio">
							<textarea name = "details" class = "materialize-textarea"></textarea>
						</label>
					</p>
				</div>

				<button class = "btn waves-effect waves-light" type = "submit">
					ارسال
				</button>
			</form>
		</div>
	</div>
</div>
<!--JavaScript at end of body for optimized loading-->
<script src = "https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity = "sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin = "anonymous">
</script>
<script src = "https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script>
	$( document ).ready( function () {
		$( 'select' ).formSelect();
		$( '.datepicker' ).datepicker();
	} );
</script>
</body>

</html>