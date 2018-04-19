<template>
    <mobile-app-banner-panel
        v-if="showPanel"
        @close="close()"
        @open="open()"
        :store-name="storeName"
        :app-url="appUrl"
    />
</template>

<script>
    import MobileAppBannerPanel from './mobile-app-banner-panel.vue';
    import UAParser from 'ua-parser-js';
    import cookies from 'cookies-js';

    const agent = new UAParser();

    export default {
        name: "mobile-app-banner",
        data() {
            return {
                show: false
            }
        },
        computed: {
            isMobilePhone() {
                return this.isPhone && (this.isIOS || this.isAndroid);
            },
            isPhone() {
                return agent.getDevice().type === 'mobile';
            },
            isIOS() {
                return agent.getOS().name === 'iOS';
            },
            isAndroid() {
                return agent.getOS().name === 'Android';
            },
            isSafari() {
                return agent.getBrowser().name === 'Mobile Safari';
            },
            inMobileApp() {
                //This global variable is set by the mobile app.
                return !! window.inMobileApp;
            },
            hasClosedBanner() {
                return cookies.get('hasClosedMobileAppBanner') === "true";
            },
            hasOpenedStorePage() {
                return cookies.get('hasOpenedMobileAppStore') === "true";
            },
            showPanel() {
                return this.show
                    && ! this.hasClosedBanner
                    && ! this.hasOpenedStorePage
                    && ! this.isSafari
                    && ! this.inMobileApp
                    && this.isMobilePhone;
            },
            storeName() {
                return this.isAndroid
                    ? 'Google Play'
                    : 'App Store';
            },
            appUrl() {
                return this.isAndroid
                    ? 'https://play.google.com/store/apps/details?id=pt.casinoportugal.app'
                    : 'https://itunes.apple.com/pt/app/app/id1353477331?mt=8';
            }
        },
        methods: {
            daysInSeconds(days) {
                return days * 24 * 60 * 60;
            },
            close() {
                this.show = false;
                cookies.set('hasClosedMobileAppBanner', true, {expires: this.daysInSeconds(15)});
            },
            open() {
                cookies.set('hasOpenedMobileAppStore', true, {expires: this.daysInSeconds(90)});
            }
        },
        components: {
            MobileAppBannerPanel
        },
        mounted() {
            window.setTimeout(() => this.show = true, 3000);
        }
    }
</script>

