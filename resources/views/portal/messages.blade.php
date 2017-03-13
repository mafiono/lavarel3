<div class="alert alert-danger alert-dismissable" style="display:none;">
	<div class="msg" style="display:inline-block"> </div> <i class="cp-times-circle warning-color"></i>
</div>

<div class="alert alert-success alert-dismissable text-center" style="display:none;">
	<div class="msg" style="display:inline-block"> </div> <i class="cp-check-circle success-color"></i>
</div>

@if(Session::has('error'))
	<div class="alert alert-danger alert-dismissable">
		{{ Session::get('error') }} <i class="cp-times-circle warning-color"></i>
	</div>
@endif
@if (Session::has('success'))
	<div class="alert alert-success alert-dismissable text-center">
		{{ Session::get('success') }} <i class="cp-check-circle success-color"></i>
	</div>
@endif
@if ($errors->has())
    <div class="alert alert-danger alert-dismissable">
        @foreach ($errors->all() as $error)
            {{ $error }} <i class="cp-times-circle warning-color"></i><br>
        @endforeach
    </div>
@endif