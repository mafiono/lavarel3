@extends('layouts.dashboard')

@section('content')

	<div class="main-contend home-back">
    	<div class="main">
			<div class="banner-col-1 banner fleft">
            	<div class="banner1">
            		B1
                </div>
            </div>
			<div class="banner-col-3 banner fleft">
            	<div class="banner1">
            		B2
                </div>
            </div>
			<div class="banner-col-1 banner fleft">
            	<div class="banner1">
            		B3
                </div>
            </div>
			<div class="banner-col-1 nomargin fleft">
            	<div class="banner1">
            		B4
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/dashboard/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/dashboard/js/forms.js')); !!}

@stop