<div id="markets-header-container"></div>
<div id="markets-fixturesContainer" class="markets-container">
    <div id="intro-banners" class="hidden">
        <div style="font-family: 'Exo 2','Open Sans','Droid Sans',sans-serif; font-size: 18px; font-weight: bold; line-height: 46px; margin-bottom: 8px; padding-left: 10px">
            A CASA DE APOSTAS PORTUGUESA COM CASH OUT TOTAL
            @if (empty($authUser))
                <a href="/registar" style="float: right; padding: 0 10px; background-color: #f90; font-family: 'Open Sans','Droid Sans',Verdana,sans-serif; font-size: 14px; color: #FFF;">Registe-se agora</a>
            @endif
        </div>
        <div id="slider">
            <div class="slides">
                <div class="slider">
                    <div class="images">
                        <img src="/assets/portal/img/slides/slide1.png">
                    </div>
                </div>
                <div class="slider">
                    <div class="images">
                        <img src="/assets/portal/img/slides/slide2.png">
                    </div>
                </div>
                <div class="slider">
                    <div class="images">
                        <img src="/assets/portal/img/slides/slide1.png">
                    </div>
                </div>
                <div class="slider">
                    <div class="images">
                        <img src="/assets/portal/img/slides/slide2.png">
                    </div>
                </div>
            </div>
            <div class="switch">
                <ul>
                    <li>
                        <div class="on"></div>
                    </li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
            </div>
        </div>
    </div>
    <div id="markets-content">
        <p style="position: relative; margin-top: 180px;" id="marketsSpinner"></p>
    </div>
</div>
<div id="markets-fixtureMarketsContainer" class="markets-container hidden"></div>
