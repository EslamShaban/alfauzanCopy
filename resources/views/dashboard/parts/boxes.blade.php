<style type = "text/css">
	.myelements i {
		font-size: 46px;
		margin-top: 20%;
	}
</style>

<div class = "panel panel-flat">
	<div class = "panel-heading">
		<div class = "row">
			<!-- Members online -->
			@foreach(Home() as $box)

				<div class = "col-lg-4">
					<a href = "{{ route($box['link']) }}">
						<div class = "panel" style = "background-color: {{ $box['color'] }}">
							<div class = "panel-body" style = "height: 110px; color: white;">
								<div class = "heading-elements myelements">
									{!! $box['icon'] !!}
								</div>
								<h3 class = "no-margin">{{$box['count']}}</h3>
								<h3>{{$box['name']}}</h3>
							</div>
						</div>
					</a>
				</div>

		@endforeach

		<!-- /members online -->
		</div>
	</div>
</div>