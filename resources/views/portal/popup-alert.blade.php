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
<script>
	$(function () {
		var modal = $('#myModal');
		var open = function () {
			modal.find('#close').unbind('click').click(function () {
				modal.removeClass('in').addClass('out');
				setTimeout(function () {
					modal.hide().parent().hide();
				}, 300);
			});
			modal.show().addClass('in').parent().show();
		};
		$.fn.popupError = function (msg) {
			modal.find('.icon img').hide();
			modal.find('.icon .error').show();
			modal.find('.msg').html(msg);
			open();
		};
		$.fn.popupSuccess = function (msg) {
			modal.find('.icon img').hide();
			modal.find('.icon .success').show();
			modal.find('.msg').html(msg);
			open();
		};

		@if(Session::has('error'))
		$.fn.popupError('{{ Session::get('error') }}');
		@endif
		@if (Session::has('success'))
		$.fn.popupSuccess('{{ Session::get('success') }}');
		@endif
		@if ($errors->has())
		$.fn.popupError('\
			@foreach ($errors->all() as $error)
			{{ $error }}<br>\
			@endforeach
		');
		@endif
	});

</script>
