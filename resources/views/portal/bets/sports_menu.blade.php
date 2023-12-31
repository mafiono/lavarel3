<div class="sportsMenu-container noselect">

    <mobile-left-menu-header></mobile-left-menu-header>

    <mobile-search-bar></mobile-search-bar>

    <div class="sportsMenu-box header">
        <button id="sportsMenu-button-live" class="sportsMenu-tab">DIRETO</button>
        <button id="sportsMenu-button-prematch" class="sportsMenu-tab selected">PRE-JOGO</button>
    </div>

    <div id="sportsMenu-prematch-container">


        <div id="sportsMenu-highlights-header" class="sportsMenu-box-highlights">
            <span class="sportsMenu-text highlight"><i class="cp-flag"></i> &nbsp; Destaques</span>
            <i class="cp-caret-down sportsMenu-icon-highlights-right hidden"></i>
        </div>
        <div id="sportsMenu-highlights">
            <p style="position: relative; left: -20px; height: 60px;" id="highlightsSpinner"></p>
        </div>

        <favorites-button></favorites-button>

        <div id="sportsMenu-popular">
            <p style="position: relative; left: -20px; height: 120px;" id="sportsSpinner"></p>
        </div>
    </div>


    <div id="sportsMenu-live-container">
        <favorites-button></favorites-button>
        <div id="sportsMenu-live"></div>
    </div>


</div>

