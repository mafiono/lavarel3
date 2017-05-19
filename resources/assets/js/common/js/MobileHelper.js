class MobileHelper {
    constructor(options) {
        this.mobileWidth = 767;

        $(window).resize(() => {this.handleTalkTo()});
    }
    isMobile() {
        return $(window).width() <= 767;
    }
    hideContainers() {
        $(".sportsMenu-container, .markets-container, .betslip, .mobile-login, .mobile-menu")
            .hide();
    }
    showPage(url) {
        page(url);

        showContent();
    }
    showContent() {
        this.hideContainers();

        Store.commit('mobile/setView', '');

        $(".markets-container").show();
    }
    showSportsMenu() {
        this.hideContainers();

        Store.commit('mobile/setView', 'sportsMenu');

        $(".sportsMenu-container").show();
    }
    showBetslip() {
        this.hideContainers();

        Store.commit('mobile/setView', 'betslip');

        $(".betslip").show();
    }
    showMobileLogin() {
        this.hideContainers();

        Store.commit('mobile/setView', 'login');

        $(".mobile-login").show();
    }
    showMobileMenu() {
        this.hideContainers();

        Store.commit('mobile/setView', 'menu');

        $(".mobile-menu").show();
    }
    handleTalkTo() {
        if ($(window).width() > this.mobileWidth)
            Tawk_API.showWidget();
        else if (Tawk_API.isChatMinimized())
            Tawk_API.hideWidget();

    }
}

window.MobileHelper = new MobileHelper();