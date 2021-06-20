<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8"/>
	<meta name = "description" content = ""/>
	<meta name = "keywords" content = ""/>
	<meta name = "author" content = ""/>
	<meta name = "robots" content = "index/follow"/>


	<meta http-equiv = "X-UA-Compatible" content = "IE=edge,chrome=1"/>

	<meta name = "viewport"
	      content = "width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, minimum-scale=1, user-scalable=no"/>

	<meta name = "HandheldFriendly" content = "true"/>

	<!-- title and browser tab icon -->
	<title>Ad Info</title>
	<link rel = "shortcut icon" href = "" type = "image/x-icon"/>

	<!-- style sheets -->
	<link rel = "stylesheet" href = "{{asset('site/new/css/bootstrap.min.css')}}"/>
	<link rel = "stylesheet" href = "{{asset('site/new/css/font-awesome-5all.css')}}">
	<link rel = "stylesheet" href = "{{asset('site/new/css/owl.carousel.min.css')}}"/>
	<link rel = "stylesheet" href = "{{asset('site/new/css/style.css')}}"/>
</head>
<body>

<!--=====================================================================
    // Start Nav
======================================================================-->

<!--<nav>-->
<!--<div class="container">-->
<!--<div class="head">-->
<!--<i class="fas fa-th grid"></i>-->
<!--<i class="fas fa-chevron-left arrow"></i>-->
<!--تفاصيل الإعلان-->
<!--</div>-->
<!--</div>-->
<!--</nav>-->

<!--=====================================================================
    // Start Header
======================================================================-->


<!--=====================================================================
    // Start Content
======================================================================-->

<div class = "content-holder">

	<header>

		{{--ad Images--}}
		<div class = "slider owl-carousel">

			@foreach($ad->image as $img)
				<div class = "item">
					<img src = "{{asset('dashboard/uploads/adss/'.$img->image)}}" alt = "image"/>
					<div class = "overlay"></div>
				</div>
			@endforeach


		</div>
	</header>

	<div class = "block">
		<div class = "red margin5">{{$ad->name_ar}}</div>
		<div class = "black">{{$ad->desc}}<sup>2</sup></div>
	</div>

	<!-- Info -->

	<div class = "block">
		<ul class = "info">
			<li>
				<div class = "red margin5">السعر</div>
				<div class = "black">{{$ad->cost}} ريال</div>
			</li>
			<li>
				<div class = "red margin5">السعى</div>
				<div class = "black">{{$ad->vat}} %</div>
			</li>
			<li>
				<div class = "red margin5">الضريبة</div>
				<div class = "black">{{$ad->tax}} %</div>
			</li>
			<li>
				<div class = "red margin5">الإجمالي</div>
				<div class = "black">{{$ad->total_cost}} ريال</div>
			</li>
		</ul>
	</div>

	<!-- Views -->

	<div class = "block">
		<ul class = "views">
			<li>
				<div><i class = "far fa-eye"></i></div>
				<div class = "black">{{count($ad->view)}}</div>
				<div class = "red">عدد المشاهدات</div>
			</li>
			<li>
				<div><i class = "fas fa-lightbulb"></i></div>
				<div class = "black">{{$ad->id}}</div>
				<div class = "red">رقم الإعلان</div>
			</li>
			<li>
				<div><i class = "far fa-calendar-alt"></i></div>
				<div class = "black">{{$ad->created_at}}</div>
				<div class = "red">تاريخ نشر الإعلان</div>
			</li>
		</ul>
	</div>

	<!-- Map -->

	<div class = "block map">
		<div id = "map"></div>
	</div>

	<!-- Details -->

	<div class = "block details">
		<div class = "block-title red">التفاصيل</div>
		<div class = "para">
			{{$ad->details}}
		</div>
	</div>

	

	@if($ad->video)
		<div class = "block details">
			<div class = "block-title red">فيديو المشروع</div>
			<div class = "para">
				<div class = "frame-holder">
					<iframe width = "560" height = "315"
					        src = "https://www.youtube.com/embed/{{getYoutubeVideoId( $ad -> video )}}"
					        frameborder = "0"
					        allow = "accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
					        allowfullscreen></iframe>
				</div>
			</div>
		</div>

@endif

<!-- Share -->

{{--<div class="block share">--}}
{{--<div class="red margin5">نشر الإعلان</div>--}}
{{--<ul class="social">--}}
{{--<li><a href=""><i class="fab fa-whatsapp"></i></a></li>--}}
{{--<li><a href=""><i class="fab fa-twitter"></i></a></li>--}}
{{--<li><a href=""><i class="fab fa-facebook-f"></i></a></li>--}}
{{--</ul>--}}
{{--</div>--}}

<!-- Comment -->

	<div class = "block">
		<div class = "red margin5">التعليقات</div>
		{{--Reviews--}}

		@foreach($ad->review as $review)

			<div class = "comment">
				<img src = "{{asset('dashboard/uploads/users/'.$review->user->avatar)}}" alt = "user"/>
				<div class = "info">
					<div class = "name">
						<span class = "fa-pull-right red">{{$review->user->name}}</span>
						<div class = "stars fa-pull-left">


							@if($review->rate>=1)
								<i class = "fas fa-star active"></i>
							@else
								<i class = "fas fa-star"></i>
							@endif

							@if($review->rate>=2)
								<i class = "fas fa-star active"></i>
							@else
								<i class = "fas fa-star"></i>
							@endif

							@if($review->rate>=3)
								<i class = "fas fa-star active"></i>
							@else
								<i class = "fas fa-star"></i>
							@endif

							@if($review->rate>=4)
								<i class = "fas fa-star active"></i>
							@else
								<i class = "fas fa-star"></i>
							@endif

							@if($review->rate==5)
								<i class = "fas fa-star active"></i>
							@else
								<i class = "fas fa-star"></i>
							@endif

						</div>
						<div class = "clearfix"></div>
					</div>
					<div class = "para">
						{{$review->comment}}
					</div>
				</div>
			</div>

		@endforeach


	</div>

</div> <!-- Block Holder -->

<!--=====================================================================
    // Start
======================================================================-->

<script type = "text/javascript" src = "{{asset('site/new/js/jquery-3.2.1.min.js')}}"></script>
<script type = "text/javascript" src = "{{asset('site/new/js/bootstrap.min.js')}}"></script>
<script type = "text/javascript" src = "{{asset('site/new/js/owl.carousel.min.js')}}"></script>
<script type = "text/javascript" src = "{{asset('site/new/js/custom.js')}}"></script>
<script>
	function initMap() {
		var myLatLng = { lat: {{$ad->lat}}, lng: {{$ad->lng}} };

		var map = new google.maps.Map( document.getElementById( 'map' ), {
			zoom: 9,
			center: myLatLng
		} );

		var marker = new google.maps.Marker( {
			                                     position: myLatLng,
			                                     map: map,
			                                     title: 'Hello World!'
		                                     } );
	}
</script>
<script
	src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyD8cUy05UAvneVcdyAUodgt2qk2I2uzHdw&callback=initMap"></script>
</body>
</html>
