<div class="markets-container" @if (isset($hidden)) style="display: none" @endif>
    <div id="breadcrumb-container" class="hidden"></div>
    <div id="homepage-container" class="hidden" style="margin-top: 8px">
        <div style="font-family: 'Exo 2','Open Sans','Droid Sans',sans-serif; font-size: 18px; font-weight: bold; line-height: 46px; margin-bottom: 8px; padding-left: 10px">
            A CASA DE APOSTAS PORTUGUESA COM CASH OUT TOTAL. VENHA JOGAR!
            @if (empty($authUser))
                {{--<a href="/registar" style="float: right; padding: 0 10px; background-color: #f90; font-family: 'Open Sans','Droid Sans',Verdana,sans-serif; font-size: 14px; color: #FFF;">Registe-se agora</a>--}}
            @endif
        </div>
        <div class="carousel">
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

        <div id="liveFixtures-container"></div>
        <div id="highFixtures-container"></div>
        <div id="tennisFixtures-container"></div>
    </div>
    <div id="fixtures-container" class="hidden"></div>
    <iframe id="match-container" class="hidden" style="height: 310px; width: 100%; border: 0; margin-top: -2px" scrolling="no"></iframe>
    <div id="markets-container" class="hidden"></div>
    <div id="liveMarkets-container" class="hidden"></div>
    <div id="favorites-container" class="hidden">
        <div id="favorites-live-container"></div>
        <div id="favorites-prematch-container"></div>
    </div>
    <div id="search-container" class="hidden">
        <div id="search-live-container"></div>
        <div id="search-prematch-container"></div>
    </div>
    <div id="info-container" class="hidden"></div>
    <div id="statistics-container" class="hidden"></div>
    <div id="blocker-container"></div>
    <iframe id="register-container" style="height: 1050px; width: 100%; border: 0;" scrolling="no"></iframe>
    <div id="middleAlert-container"></div>
</div>