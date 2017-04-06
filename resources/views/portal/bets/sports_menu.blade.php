<div class="sportsMenu-container noselect">

    <div class="sportsMenu-box header">
        <button id="sportsMenu-button-live" class="sportsMenu-tab">DIRETO</button>
        <button id="sportsMenu-button-prematch" class="sportsMenu-tab selected">DESPORTOS</button>
    </div>

    <div id="sportsMenu-prematch-container">


        <div id="sportsMenu-highlights-header" class="sportsMenu-box-highlights">
            <span class="sportsMenu-text highlight"><i class="cp-flag"></i> &nbsp; Destaques</span>
            <i class="cp-caret-down sportsMenu-icon-highlights-right hidden"></i>
        </div>
        <div id="sportsMenu-highlights">
            <p style="position: relative; left: -20px; height: 60px;" id="highlightsSpinner"></p>
        </div>

        <promotions-button></promotions-button>
        <div class="sportsMenu-interval">
            <div id="sportsMenu-interval" class="header">
                <i class="i1 cp-plus expand"></i>
                <i class="cp-clock2"></i> &nbsp; <span id="sportsMenu-interval-text">Todos</span>
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
        <div id="sportsMenu-popular">
            <p style="position: relative; left: -20px; height: 120px;" id="sportsSpinner"></p>
        </div>
    </div>

    <div id="sportsMenu-live-container"></div>


</div>

