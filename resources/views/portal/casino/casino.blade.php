@extends('layouts.portal')

@section('styles')
    {!! HTML::style('assets/portal/css/casino/casino.css') !!}
    {!! HTML::style('assets/portal/css/owl.carousel/owl.carousel.css') !!}
    {!! HTML::style('assets/portal/css/owl.carousel/owl.theme.css') !!}
    {!! HTML::style('assets/portal/css/global.css') !!}
@stop

@section('content')
<div class="casino-container">
    <div class="casino-container-header">
        <div class="acenter">
            <button class="casino-button">Casino</button>
            <button class="casino-button">Casino ao vivo</button>
            <button class="casino-button">Promoções</button>
        </div>
    </div>
    <div class="casino-container-content">
        <div class="casino-box-header">EM DESTAQUE</div>
        <div class="casino-box">
            <div class="casino-box-navigation">
                <button id="featured-prev" class="casino-button-prev"><</button>
            </div>
            <div class="casino-box-carousel">
                <div id="featured-carousel" class="owl-carousel">
                    <div class="casino-box-game">
                        <iframe class="casino-iframe-game" src="http://nogs-gl.nyxinteractive.eu/game/?nogsgameid=70001&nogsoperatorid=1&nogscurrency=eur&nogslang=en_us&nogsmode=demo"></iframe>
                        <span class="casino-text-gameLabel">Vocano</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                    <div class="casino-box-game">
                        <iframe class="casino-iframe-game" src="http://nogs-gl.nyxinteractive.eu/game/?nogsgameid=70001&nogsoperatorid=1&nogscurrency=eur&nogslang=en_us&nogsmode=demo"></iframe>
                        <span class="casino-text-gameLabel">Vocano</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                    <div class="casino-box-game">
                        <iframe class="casino-iframe-game" src="http://nogs-gl.nyxinteractive.eu/game/?nogsgameid=70001&nogsoperatorid=1&nogscurrency=eur&nogslang=en_us&nogsmode=demo"></iframe>
                        <span class="casino-text-gameLabel">Vocano</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                    <div class="casino-box-game">
                        <iframe class="casino-iframe-game" src="http://nogs-gl.nyxinteractive.eu/game/?nogsgameid=70001&nogsoperatorid=1&nogscurrency=eur&nogslang=en_us&nogsmode=demo"></iframe>
                        <span class="casino-text-gameLabel">Vocano</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                    <div class="casino-box-game">
                        <iframe class="casino-iframe-game" src="http://nogs-gl.nyxinteractive.eu/game/?nogsgameid=70001&nogsoperatorid=1&nogscurrency=eur&nogslang=en_us&nogsmode=demo"></iframe>
                        <span class="casino-text-gameLabel">Vocano</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                </div>
            </div>
            <div class="casino-box-navigation">
                <button id="featured-next" class="casino-button-next">></button>
            </div>
        </div>
        <div class="casino-box-header">JOGOS DE CARTAS</div>
        <div class="casino-box">
            <div class="casino-box-navigation">
                <button id="cards-prev" class="casino-button-prev"><</button>
            </div>
            <div class="casino-box-carousel">
                <div id="cards-carousel" class="owl-carousel">
                    <div class="casino-box-game">
                        <iframe class="casino-iframe-game" src="http://nogs-gl.nyxinteractive.eu/game/?nogsgameid=70001&nogsoperatorid=1&nogscurrency=eur&nogslang=en_us&nogsmode=demo"></iframe>
                        <span class="casino-text-gameLabel">Vocano</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                    <div class="casino-box-game">
                        <iframe class="casino-iframe-game" src="http://nogs-gl.nyxinteractive.eu/game/?nogsgameid=70001&nogsoperatorid=1&nogscurrency=eur&nogslang=en_us&nogsmode=demo"></iframe>
                        <span class="casino-text-gameLabel">Vocano</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                    <div class="casino-box-game">
                        <iframe class="casino-iframe-game" src="http://nogs-gl.nyxinteractive.eu/game/?nogsgameid=70001&nogsoperatorid=1&nogscurrency=eur&nogslang=en_us&nogsmode=demo"></iframe>
                        <span class="casino-text-gameLabel">Vocano</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                    <div class="casino-box-game">
                        <iframe class="casino-iframe-game" src="http://nogs-gl.nyxinteractive.eu/game/?nogsgameid=70001&nogsoperatorid=1&nogscurrency=eur&nogslang=en_us&nogsmode=demo"></iframe>
                        <span class="casino-text-gameLabel">Vocano</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                    <div class="casino-box-game">
                        <iframe class="casino-iframe-game" src="http://nogs-gl.nyxinteractive.eu/game/?nogsgameid=70001&nogsoperatorid=1&nogscurrency=eur&nogslang=en_us&nogsmode=demo"></iframe>
                        <span class="casino-text-gameLabel">Vocano</span>
                        <button class="fa fa-star casino-button-favorite"></button>
                    </div>
                </div>
            </div>
            <div class="casino-box-navigation">
                <button id="cards-next" class="casino-button-next">></button>
            </div>
        </div>
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