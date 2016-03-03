<div class="alert alert-danger alert-dismissable" style="display:none;">
	<div class="msg" style="display:inline-block"> </div> <i class="fa fa-times-circle warning-color"></i>
</div>

<div class="alert alert-success alert-dismissable text-center" style="display:none;">
	<div class="msg" style="display:inline-block"> </div> <i class="fa fa-check-circle success-color"></i>
</div>

@if(Session::has('error'))
	<div class="alert alert-danger alert-dismissable marginBottom20">
		{{ Session::get('error') }} <i class="fa fa-times-circle warning-color"></i>
	</div>
@endif
@if (Session::has('success'))
	<div class="alert alert-success alert-dismissable text-center marginBottom20">
		{{ Session::get('success') }} <i class="fa fa-check-circle success-color"></i>
	</div>
@endif
@if ($errors->has())
    <div class="alert alert-danger alert-dismissable marginBottom20">
        @foreach ($errors->all() as $error)
            {{ $error }} <i class="fa fa-times-circle warning-color"></i><br>
        @endforeach
    </div>
@endif