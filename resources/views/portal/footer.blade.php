<div class="col-xs-12 footer neut-back neut-color" style="clear: both; min-width: 1204px">
    <div class="main">
        <div class="social">
            <a class="social-face brand-trans" target="_blank" href="#"></a>
            <a class="social-insta brand-trans" target="_blank" href="#"></a>
            <a class="social-twitter brand-trans" target="_blank" href="#"></a>
            <a class="social-gplus brand-trans" target="_blank" href="#"></a>
        </div>
        
        <div class="footer-referencias">
            <a target="_blank" href="http://www.srij.turismodeportugal.pt/pt/"><img alt="SRIJ" src="/assets/portal/img/srij.png" /></a>
            <!---<img alt="Curação" src="/assets/portal/img/curacao.png" />--->
            <img alt="Aviso Maioridade" src="/assets/portal/img/18plus.png" />
        </div>
        <div class="footer-aviso">
            A IbetUp encontra-se licenciada e regulada pelo Governo de Portugal e opera ao abrigo da Licença de Jogo Nº ###/##. Categorias e tipos de jogos e apostas que podemos explorar.
            <br />
            <br />
            Ao aceder, continuar a utilizar ou a navegar nesta página web, aceita que utilizemos certos cookies de navegador com o objectivo de melhorar a sua experiência. A ibetup apenas usa cookies que melhoram a sua experiência e não interferem com a sua privacidade. Queira por favor aceder à nossa Política de Utilização para mais informação relativamente à forma como utilizamos cookies e como pode desactivar ou gerir os mesmos, caso deseje fazê-lo.
        </div>
        <div class="footer-menu">
            <ul>
                <a href="/info/sobre_nos" onclick="onPopup(this); return false;"><li class="brand-trans">Sobre Nós</li></a>
                <a href="/info/afiliados" onclick="onPopup(this); return false;"><li class="brand-trans footer-menu-option">Afiliados</li></a>
                <a href="/info/termos_e_condicoes" onclick="onPopup(this); return false;"><li class="brand-trans footer-menu-option">Termos e Condições</li></a>
                <a href="/info/contactos" onclick="onPopup(this); return false;"><li class="brand-trans footer-menu-option">Contactos</li></a>
                <a href="/info/ajuda" onclick="onPopup(this); return false;"><li>Ajuda</li></a>
                <a href="/info/promocoes" onclick="onPopup(this); return false;"><li class="brand-trans footer-menu-option">Promoções</li></a>
                <a href="/info/faq" onclick="onPopup(this); return false;"><li class="brand-trans footer-menu-option">FAQ</li></a>
                <a href="/info/territorios_restritos" onclick="onPopup(this); return false;"><li class="brand-trans footer-menu-option">Territórios Restritos</li></a>
                <div class="clear"></div>
            </ul>
            <div class="footer-menu-logo neut-back">
                <img alt="ibetup" src="/assets/portal/img/logoibetup.png" />
            </div>
        </div>
        <div class="footer-pagamentos">
            <img alt="MasterCard" src="/assets/portal/img/mastercard.png" />
            <img alt="Visa" src="/assets/portal/img/visa.png" />
            <img alt="PayPal" src="/assets/portal/img/paypal.png" />
            <!---<img alt="Boleto" src="/assets/portal/img/boleto.png" />--->
        </div>
    </div>
</div>
<script>
    function onPopup(url) {
        // Fixes dual-screen position                         Most browsers      Firefox
        var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
        var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

        var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        var w = 800, h = 600, title = "IBetUp_FAQ";

        var left = ((width / 2) - (w / 2)) + dualScreenLeft;
        var top = ((height / 2) - (h / 2)) + dualScreenTop;
        var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        // Puts focus on the newWindow
        if (window.focus) {
            newWindow.focus();
        }
    }
</script>
