{!! HTML::style('assets/portal/js/plugins/sweetalert/sweetalert.css') !!}
<script>
	$(function () {
        @if(Session::has('has_deposited') && Auth::check() && SportsBonus::hasAvailable())
			$.fn.popup({
				type: 'success',
				text: 'Depósito efetuado com sucesso! Tem bónus disponível, pretende ver o bónus?',
				showCancelButton: true,
				confirmButtonText: 'SIM',
				cancelButtonText: 'NÃO',
			}, function (isConfirm) {
				if (isConfirm) {
					page('/perfil/banco/saldo');
				}
			});
		@else
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
		@endif
	});
</script>
