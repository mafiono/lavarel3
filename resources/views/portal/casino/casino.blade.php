@extends('layouts.portal')

@section('styles')
    {!! HTML::style('assets/portal/css/casino/casino.css') !!}
    {!! HTML::style('assets/portal/css/owl.carousel/owl.carousel.css') !!}
    {!! HTML::style('assets/portal/css/owl.carousel/owl.theme.css') !!}
    {!! HTML::style('assets/portal/css/global.css') !!}
@stop

@section('content')
<div class="casino-container">
    <div id="casinoMenuContainer" class="casino-container-menu"></div>
    <div id="allContainer" class="casino-container-content clearfix">
        <div class="casino-container-header">
            <div class="acenter">
                <button class="casino-button">Casino</button>
                <button class="casino-button">Casino ao vivo</button>
                <button class="casino-button">Promoções</button>
            </div>
        </div>
        <div class="casino-box-header">EM DESTAQUE</div>
        <div class="casino-box">
            <button id="featured-prev" class="casino-button-prev"><i class="fa fa-angle-left"></i></button>
            <div class="casino-box-carousel">
                <div id="featured-carousel" class="owl-carousel">
                    <div class="casino-box-game">
                        <a href="#"><img class="casino-image-game" src="/assets/portal/img/demo/img1.jpg"></a>
                        <span class="casino-text-gameLabel">Wonder Women</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                    <div class="casino-box-game">
                        <a href="#"><img class="casino-image-game" src="/assets/portal/img/demo/img2.jpg"></a>
                        <span class="casino-text-gameLabel">Green Lantern</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                    <div class="casino-box-game">
                        <a href="#"><img class="casino-image-game" src="/assets/portal/img/demo/img3.jpg"></a>
                        <span class="casino-text-gameLabel">The Flash</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                    <div class="casino-box-game">
                        <a href="#"><img class="casino-image-game" src="/assets/portal/img/demo/img4.jpg"></a>
                        <span class="casino-text-gameLabel">Superman</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                    <div class="casino-box-game">
                        <a href="#"><img class="casino-image-game" src="/assets/portal/img/demo/img5.jpg"></a>
                        <span class="casino-text-gameLabel">Champions Goal</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                </div>
            </div>
            <button id="featured-next" class="casino-button-next"><i class="fa fa-angle-right"></i></button>
        </div>
        <div class="casino-box-header">JOGOS DE CARTAS</div>
        <div class="casino-box">
            <button id="cards-prev" class="casino-button-prev"><i class="fa fa-angle-left"></i></button>
            <div class="casino-box-carousel">
                <div id="cards-carousel" class="owl-carousel">
                    <div class="casino-box-game">
                        <a href="#"><img class="casino-image-game" src="/assets/portal/img/demo/img6.jpg"></a>
                        <span class="casino-text-gameLabel">Eletric SAM</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                    <div class="casino-box-game">
                        <a href="#"><img class="casino-image-game" src="/assets/portal/img/demo/img7.jpg"></a>
                        <span class="casino-text-gameLabel">The Lab</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                    <div class="casino-box-game">
                        <a href="#"><img class="casino-image-game" src="/assets/portal/img/demo/img8.jpg"></a>
                        <span class="casino-text-gameLabel">DJ Wild</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                    <div class="casino-box-game">
                        <a href="#"><img class="casino-image-game" src="/assets/portal/img/demo/img9.jpg"></a>
                        <span class="casino-text-gameLabel">Wolf PACK</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                    <div class="casino-box-game">
                        <a href="#"><img class="casino-image-game" src="/assets/portal/img/demo/img10.jpg"></a>
                        <span class="casino-text-gameLabel">Wild RIDE</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                </div>
            </div>
            <button id="cards-next" class="casino-button-next"><i class="fa fa-angle-right"></i></button>
        </div>
    </div>
    <div id="featuredGamesContainer" class="casino-container-content hidden">
        <div class="casino-container-header">
            <div class="acenter">
                <button class="casino-button">Casino</button>
                <button class="casino-button">Casino ao vivo</button>
                <button class="casino-button">Promoções</button>
            </div>
        </div>
        <div class="casino-box-header">EM DESTAQUE</div>
            <div class="casino-box-game">
                <a href="#"><img class="casino-image-game" src="/assets/portal/img/demo/img1.jpg"></a>
                <span class="casino-text-gameLabel">Wonder Women</span>
                <button class="fa fa-star casino-button-favorite"></button>
            </div>
            <div class="casino-box-game">
                <a href="#"><img class="casino-image-game" src="/assets/portal/img/demo/img2.jpg"></a>
                <span class="casino-text-gameLabel">Green Lantern</span>
                <button class="fa fa-star casino-button-favorite"></button>
            </div>
            <div class="casino-box-game">
                <a href="#"><img class="casino-image-game" src="/assets/portal/img/demo/img3.jpg"></a>
                <span class="casino-text-gameLabel">The Flash</span>
                <button class="fa fa-star casino-button-favorite"></button>
            </div>
            <div class="casino-box-game">
                <a href="#"><img class="casino-image-game" src="/assets/portal/img/demo/img4.jpg"></a>
                <span class="casino-text-gameLabel">Superman</span>
                <button class="fa fa-star casino-button-favorite"></button>
            </div>
            <div class="casino-box-game">
                <a href="#"><img class="casino-image-game" src="/assets/portal/img/demo/img5.jpg"></a>
                <span class="casino-text-gameLabel">Champions Goal</span>
                <button class="fa fa-star casino-button-favorite"></button>
            </div>
        </div>
        {{--<div style="clear: both"></div>--}}
    </div>
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

    {!! HTML::script('assets/portal/js/handlebars/handlebars.min.js') !!}
    {!! HTML::script('assets/portal/js/moment/moment.min.js') !!}
    {!! HTML::script('assets/portal/js/moment/locale/pt.js') !!}
    {!! HTML::script('assets/portal/js/js-cookie/js.cookie.min.js') !!}
    {!! HTML::script('assets/portal/js/template.js') !!}
    {!! HTML::script('assets/portal/js/favorites/favorites.js') !!}
    {!! HTML::script('assets/portal/js/owl.carousel/owl.carousel.min.js') !!}
    {!! HTML::script('assets/portal/js/casino/mainCasino.js') !!}
@stop