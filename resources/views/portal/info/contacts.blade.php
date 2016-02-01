@extends('layouts.portal')
@section('header')
{!! HTML::style('assets/portal/css/sports.css') !!}
@stop
@section('styles')
{!! HTML::style('assets/portal/css/global.css') !!}
{!! HTML::style('assets/portal/css/favorites.css') !!}
@stop

@section('content')
Contactos.
@stop

@section('scripts')
    {!! HTML::script('assets/portal/js/spin.min.js') !!}
    {!! HTML::script('assets/portal/js/jquery.spin.js') !!}
    <script type="text/javascript">
        <?php if (!empty($authUser)):?>
            var phpAuthUser = <?php echo json_encode($authUser)?>;
        <?php else:?>
            var phpAuthUser = null;
        <?php endif;?>
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/locale/pt.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.0/js.cookie.min.js"></script>

    {!! HTML::script('assets/portal/js/template.js') !!}
    {!! HTML::script('assets/portal/js/favorites.js') !!}
    {!! HTML::script('assets/portal/js/casino/mainCasino.js') !!}
@stop