@extends('layouts.portal', ['mini' => true])

@section('content')
    <!---- CONTENT ---->
    <div class="col-xs-12 home-back">
        <div class="main-contend main-opacity standalone">
            <div class="main white-back perfil bs-wp">
                @include('portal.partials.pop_header', ['text' => 'ID JOGADOR: '.$authUser->internalId()])

                <div class="form-registo grid">
                    <div class="col-xs-5 no-padding">
                        <div class="">
                            <div class="col-xs-6 dash-right">
                                @include('portal.profile.head', ['active' => $active1])
                            </div>
                            <div class="col-xs-6 dash-right">
                                @include('portal.profile.head_profile', ['active' => $active2])
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-7 no-padding">
                        <div class="sub-content">
                            @yield('sub-content')
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12" style="height: 30px">

                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
<!---- FIM CONTENT ---->
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/rx.umd.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/perfil/personal_info.js')); !!}

@stop