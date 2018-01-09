class MobileHelper {
    constructor(options) {
        this.mobileWidth = 767;

        this.containers = {
            "" : ".markets-container",
            "menu-desportos" : ".sportsMenu-container",
            "betslip" : ".betslip",
            "login" : ".mobile-login",
            "menu" : ".mobile-menu"
        };

        $(window).resize(() => {this.handleTalkTo()});
    }
    isMobile() {
        return $(window).width() <= 767;
    }
    getContainer(view) {
        return this.containers[(view in this.containers) ? view : ""];
    }
    hideContainers() {
        $(".sportsMenu-container, .markets-container, .betslip")
            .hide();

        Store.mobile.view = "";
    }
    showView(view) {
        Store.mobile.view = view;

        $(this.getContainer(view)).fadeIn(500).show();
    }
    handleTalkTo() {
        try {
            if ($(window).width() > this.mobileWidth)
                Tawk_API.showWidget();
            else if (Tawk_API.isChatMinimized())
                Tawk_API.hideWidget();
        } catch (e) {}
    }
}

window.MobileHelper = new MobileHelper();