@if(isset($msgs))
	{{--display all chat messages--}}

	@foreach($msgs as $msg)

		@if($msg->type == 'out')
			<div class = "pls-1">
				<div class = "sents">
					<p>{{$msg->message}}</p>
					<span>{{$msg->created_at}}</span>
				</div>
			</div>
		@else
			<div class = "pls-2">
				<div class = "receive">
					<p>{{$msg->message}}</p>
					<span>{{$msg->created_at}}</span>
				</div>
			</div>
		@endif
	@endforeach

	{{--add last sended message--}}
@else
	<div class = "pls-1">
		<div class = "sents">
			<p>{{$msg->message}}</p>
			<span>{{$msg->created_at}}</span>
		</div>
	</div>

@endif