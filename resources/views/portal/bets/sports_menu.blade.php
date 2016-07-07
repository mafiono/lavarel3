<div class="sportsMenu-container">
    <div class="sportsMenu-box header">
        <button class="sportsMenu-tab">LIVE</button>
        <button class="sportsMenu-tab selected">PRE-MATCH</button>
    </div>

    <div id="sportsMenu-interval" class="sportsMenu-box-interval">
        <span class="sportsMenu-text interval pull-right">&nbsp;<i class="i1 fa fa-plus sportsMenu-icon-interval expand"></i></span>
        <span class="sportsMenu-text-interval"><i class="fa fa-calendar-o" aria-hidden="true"></i> &nbsp;&nbsp;
            <span id="sportsMenu-interval-text">Todos</span>
        </span>
        <span class="sportsMenu-text-interval"></span>
    </div>

    <div id="sportsMenu-interval-content" class="sportsMenu-box interval content hidden">
        <div class="sportsMenu-box sub-menu interval" data-interval="today">
            <span class="sportsMenu-text sub-menu interval">Hoje</span>
        </div>
        <div class="sportsMenu-box sub-menu interval" data-interval="9999">
            <span class="sportsMenu-text sub-menu interval">Todos</span>
        </div>
        <div class="sportsMenu-box sub-menu interval" data-interval="1">
            <span class="sportsMenu-text sub-menu interval">1 hora</span>
        </div>
        <div class="sportsMenu-box sub-menu interval" data-interval="2">
            <span class="sportsMenu-text sub-menu interval" data-interval="48">2 horas</span>
        </div>
        <div class="sportsMenu-box sub-menu interval" data-interval="3">
            <span class="sportsMenu-text sub-menu interval">3 horas</span>
        </div>
        <div class="sportsMenu-box sub-menu interval" data-interval="6">
            <span class="sportsMenu-text sub-menu interval">6 horas</span>
        </div>
        <div class="sportsMenu-box sub-menu interval" data-interval="12">
            <span class="sportsMenu-text sub-menu interval">12 horas</span>
        </div>
        <div class="sportsMenu-box sub-menu interval" data-interval="24">
            <span class="sportsMenu-text sub-menu interval">24 horas</span>
        </div>
        <div class="sportsMenu-box sub-menu interval" data-interval="48">
            <span class="sportsMenu-text sub-menu interval">48 horas</span>
        </div>
        <div class="sportsMenu-box sub-menu interval" data-interval="72">
            <span class="sportsMenu-text sub-menu interval">72 horas</span>
        </div>
    </div>

    <div class="sportsMenu-box-highlights">
        <span class="sportsMenu-text highlight"><i class="fa fa-flag" aria-hidden="true"></i> &nbsp;&nbsp;Destaques</span>
        <i class="fa fa-caret-down sportsMenu-icon-highlights-right"></i>
    </div>
    <div id="highlights-container">
        <p style="position: relative; left: -20px; height: 40px;" id="highlightsSpinner"></p>
    </div>

    <div class="sportsMenu-box-popular">
        <span class="sportsMenu-text popular"><i class="fa fa-trophy" aria-hidden="true"></i> &nbsp;&nbsp;Populares</span>
        <i class="fa fa-caret-down sportsMenu-icon-popular-right"></i>
    </div>

    <div id="sportsMenu-popular">
        <p style="position: relative; left: -20px; height: 120px;" id="sportsSpinner"></p>
    </div>

</div>

