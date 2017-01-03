<div class="popup-alert bs-wp" style="display:none;">
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document" style="width: 400px;">
			<div class="modal-content">
				<div class="modal-body">
					<div class="icon">
						<img src="/assets/portal/img/ops.png" alt="Erro" border="0" class="error">
						<img src="/assets/portal/img/ok.png" alt="Erro" border="0" class="success">
					</div>
					<div class="msg"></div>
				</div>
				<div class="modal-footer">
					<button type="button" id="close">OK</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-backdrop fade in"></div>
</div>

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
