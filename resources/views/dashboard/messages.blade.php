<div class="alert alert-danger alert-dismissable" style="display:none;">
	<i class="fa fa-ban"></i>
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	<div class="msg"> </div>
</div>

<div class="alert alert-success alert-dismissable text-center" style="display:none;">
	<i class="fa fa-check"></i>
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	<div class="msg"> </div>
</div>

@if(Session::has('error'))
	<div class="alert alert-danger alert-dismissable">
		<i class="fa fa-ban"></i>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		{{ Session::get('error') }}
	</div>
@endif
@if (Session::has('success'))
	<div class="alert alert-success alert-dismissable text-center">
		<i class="fa fa-check"></i>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		{{ Session::get('success') }}
	</div>
@endif