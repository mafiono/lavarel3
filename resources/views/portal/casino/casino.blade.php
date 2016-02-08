@extends('layouts.portal')

@section('styles')
    {!! HTML::style('assets/portal/css/casino/casino.css') !!}
    {!! HTML::style('assets/portal/css/owl.carousel/owl.carousel.css') !!}
    {!! HTML::style('assets/portal/css/owl.carousel/owl.theme.css') !!}
    {!! HTML::style('assets/portal/css/global.css') !!}
@stop

@section('content')
<div class="casino-container">
    <div class="casino-container-menu">
        <div id="allMenuItem" class="casino-box-menuItem casino-box-menuItemSelected">
            <span class="casino-text-menuItemIcon casino-text-menuItemIconSelected">
                <i class="fa fa-check-circle"></i>
            </span>
            <span class="casino-text-menuItemLabel">Todos</span>
        </div>
        <div id="featuredMenuItem" class="casino-box-menuItem">
            <span class="casino-text-menuItemIcon">
                <i class="fa fa-asterisk"></i>
            </span>
            <span class="casino-text-menuItemLabel">Em destaque</span>
        </div>
        <div class="casino-box-menuItem">
            <span class="casino-text-menuItemIcon">
                <i class="fa fa-credit-card"></i>
            </span>
            <span class="casino-text-menuItemLabel">Jogos Cartas</span>
        </div>
    </div>
    <div id="allContainer" class="casino-container-content hidden">
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
    <div id="featuredGamesContainer" class="casino-container-content">
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
        <div style="clear: both"></div>
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


    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/locale/pt.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.0/js.cookie.min.js"></script>

    {!! HTML::script('assets/portal/js/template.js') !!}
    {!! HTML::script('assets/portal/js/casino/favorites.js') !!}
    {!! HTML::script('assets/portal/js/owl.carousel/owl.carousel.js') !!}
    {!! HTML::script('assets/portal/js/casino/mainCasino.js') !!}
@stop