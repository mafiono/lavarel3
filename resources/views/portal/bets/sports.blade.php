@extends('layouts.portal')

@section('styles')
{!! HTML::style('assets/portal/css/sports/sports.css') !!}
{!! HTML::style('assets/portal/css/global.css') !!}
{!! HTML::style('assets/portal/css/sports/markets.css') !!}
{!! HTML::style('assets/portal/css/sports/bets.css') !!}
{!! HTML::style('assets/portal/css/favorites/favorites.css') !!}
@stop

@section('content')

<!---- CONTEND ---->
<div class="main-contend black-back">
    <div class="main-apostas fcenter">
        <!----- COLUNA 1 ------>
        <div class="col-xs-012 fleft ">
            <div class="apostas-box grey-back leftbar aleft">
                <div class="col-xs-12">
                    <div class="col-xs-45 jogo-box-botao blue-hover inline height1" id="live">Live</div>
                    <div class="col-xs-45 jogo-box-botao blue-hover inline height1" id="preMatch">Pré-Match</div>
                    <div class="col-xs-01 acenter inline height1"><i class="fa fa-chevron-left"></i></div>
                </div>
                <div class="col-xs-12 dyas-select">
                    <select id="events-period-select" class="col-xs-10 jogo-box-botao height2" style="text-align: left; text-indent: 15px;">
                        <option value="10000">Todas</option>
                        <option value="1">1 hora</option>
                        <option value="2">2 horas</option>
                        <option value="3">3 horas</option>
                        <option value="6">6 horas</option>
                        <option value="12">12 horas</option>
                        <option value="24">24 horas</option>
                        <option value="48">48 horas</option>
                        <option value="72">72 horas</option>
                    </select>
                </div>
                <div class="col-xs-12 apostas-modalidades">
                    <h2>Populares</h2>
                    <div id="left-menu-container">
                        <p style="position: relative; left: -20px; height: 120px;" id="leftSpinner"></p>
                    </div>
                </div>
            </div>
        </div>
        <!----- COLUNA 2 ------>
        @include('portal.bets.markets')
        <!----- COLUNA 3 ------>
        @include('portal.bets.bets')
        <div class="clear"></div> <!-- fixes background size-->
    </div> <!-- END main-apostas -->
</div> <!-- END CONTEND -->
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
        var switchEventType = {{strcmp(Route::getCurrentRoute()->getPath(),"apostas/aovivo")?0:1}};
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/locale/pt.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.0/js.cookie.min.js"></script>

    {!! HTML::script('assets/portal/js/template.js') !!}
    {!! HTML::script('assets/portal/js/eventHandler.js') !!}
    {!! HTML::script('assets/portal/js/sports/sportsBarController.js') !!}
    {!! HTML::script('assets/portal/js/sports/marketsController.js') !!}
    {!! HTML::script('assets/portal/js/favorites/favorites.js') !!}
    {!! HTML::script('assets/portal/js/favorites/favoritesController.js') !!}
    {!! HTML::script('assets/portal/js/sports/betsGraveward.js') !!}
    {!! HTML::script('assets/portal/js/sports/betsService.js') !!}
    {!! HTML::script('assets/portal/js/sports/betsplip.js') !!}
    {!! HTML::script('assets/portal/js/sports/betsController.js') !!}
    {!! HTML::script('assets/portal/js/sports/betValidator.js') !!}
    {!! HTML::script('assets/portal/js/sports/main.js') !!}
    @stop