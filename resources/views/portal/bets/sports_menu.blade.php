<div class="sportsMenu-container">

    <div class="sportsMenu-box header">
        <button id="sportsMenu-button-live" class="sportsMenu-tab">DIRETO</button>
        <button id="sportsMenu-button-prematch" class="sportsMenu-tab selected">DESPORTOS</button>
    </div>

    <div id="sportsMenu-prematch-container">

        <div class="sportsMenu-interval">

            <div id="sportsMenu-interval" class="header">
                <i class="i1 fa fa-plus expand"></i>
                <i class="fa fa-calendar-o" aria-hidden="true"></i> &nbsp; <span id="sportsMenu-interval-text">Todos</span>
            </div>

            <div id="sportsMenu-interval-content" class="content hidden">
                <div class="item" data-interval="today">Hoje</div>
                <div class="item" data-interval="9999">Todos</div>
                <div class="item" data-interval="1">1 hora</div>
                <div class="item" data-interval="2">2 horas</div>
                <div class="item" data-interval="3">3 horas</div>
                <div class="item" data-interval="6">6 horas</div>
                <div class="item" data-interval="12">12 horas</div>
                <div class="item" data-interval="24">24 horas</div>
                <div class="item" data-interval="48">48 horas</div>
                <div class="item" data-interval="72">72 horas</div>
            </div>

        </div>

        <div id="sportsMenu-highlights-header" class="sportsMenu-box-highlights">
            <span class="sportsMenu-text highlight"><i class="fa fa-flag" aria-hidden="true"></i> &nbsp; Destaques</span>
            <i class="fa fa-caret-down sportsMenu-icon-highlights-right hidden"></i>
        </div>
        <div id="sportsMenu-highlights">
            <p style="position: relative; left: -20px; height: 60px;" id="highlightsSpinner"></p>
        </div>

        <div id="sportsMenu-popular-header" class="sportsMenu-box-popular">
            <span class="sportsMenu-text popular"><i class="fa fa-trophy" aria-hidden="true"></i> &nbsp; Populares</span>
            <i class="fa fa-caret-down sportsMenu-icon-popular-right hidden"></i>
        </div>

        <div id="sportsMenu-popular">
            <p style="position: relative; left: -20px; height: 120px;" id="sportsSpinner"></p>
        </div>
    </div>

    <div id="sportsMenu-live-container" class="menuLive"></div>


</div>

