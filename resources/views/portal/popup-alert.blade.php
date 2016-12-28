{!! HTML::style('assets/portal/js/plugins/sweetalert/sweetalert.css') !!}
{!! HTML::script(URL::asset('assets/portal/js/plugins/sweetalert/sweetalert.min.js')) !!}
<script>
	$(function () {
		@if(Session::has('error'))
		$.fn.popup({
			type: 'error',
			text: {!! json_encode(Session::get('error')) !!}
		});
		@endif
		@if (Session::has('success'))
		$.fn.popup({
			type: 'success',
			text: {!! json_encode(Session::get('success')) !!}
		});
		@endif
		@if ($errors->has())
		$.fn.popup({
			type: 'error',
			text: '\
			@foreach ($errors->all() as $error)
				{{ $error }}<br>\
			@endforeach
			'
		});
		@endif
	});

</script>
