@extends('layouts.portal')

@section('styles')
    {!! HTML::style('assets/portal/css/casino/casino.css') !!}
    {!! HTML::style('assets/portal/css/owl.carousel/owl.carousel.css') !!}
    {!! HTML::style('assets/portal/css/owl.carousel/owl.theme.css') !!}
@stop

@section('content')
<div class="main-contend casino-container">
    <div id="casino-menu-container" class="casino-container-menu"></div>
    <div id="casino-content-container" class="casino-container-content clearfix"></div>
    {{--<div id="casino-all-container" class="casino-container-content clearfix">--}}
        {{--<div class="casino-container-header">--}}
            {{--<div class="acenter">--}}
                {{--<button class="casino-button">Casino</button>--}}
                {{--<button class="casino-button">Casino ao vivo</button>--}}
                {{--<button class="casino-button">Promoções</button>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div id="casino-featuredGames-container" class="casino-container-content hidden"></div>--}}

</div>
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

    {!! HTML::script('assets/portal/js/history/history.min.js') !!}
    {!! HTML::script('assets/portal/js/handlebars/handlebars.min.js') !!}
    {!! HTML::script('assets/portal/js/moment/locale/pt.js') !!}
    {!! HTML::script('assets/portal/js/js-cookie/js.cookie.min.js') !!}
    {!! HTML::script('assets/portal/js/template.js') !!}
    {!! HTML::script('assets/portal/js/favorites/favorites.js') !!}
    {!! HTML::script('assets/portal/js/owl.carousel/owl.carousel.min.js') !!}
    {!! HTML::script('assets/portal/js/casino/casinoController.js') !!}
@stop