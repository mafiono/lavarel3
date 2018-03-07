<template></template>

<script>
    import Cookies from 'cookies-js';

    window.Cookies = Cookies;

    export default {
        name: "app-store-popup",
        computed: {
            isIosPhone() {
                return isMobile.apple.phone;
            },
            isAndroidPhone() {
                return isMobile.android.phone;
            },
            inMobileApp() {
                //This global variable is set by the mobile app.
                return !! window.inMobileApp;
            },
            hasAskedBeforeToDownloadApp() {
                return Cookies.get('hasAskedBeforeToDownloadApp') === "true";
            },
            shouldAskToDownloadApp() {
                return ! this.hasAskedBeforeToDownloadApp
                    && (this.isAndroidPhone || this.isIosPhone)
                    && ! this.inMobileApp
            },
        },
        methods: {
            showPopup() {
                if (false && confirm('Temos uma app disponível. Quer descarregar?')) {
                    this.handleConfirm();
                }
            },
            setAskedToDownloadApp() {
                Cookies.set('hasAskedBeforeToDownloadApp', true, {expires: 9999});
            },
            handleConfirm() {
                window.location = this.isAndroidPhone
                    ? 'https://www.sapo.pt'
                    : 'https://bola.pt';
            }
        },
        mounted() {
            if (this.shouldAskToDownloadApp) {
                this.showPopup();

                this.setAskedToDownloadApp();
            }
        }
    }
</script>