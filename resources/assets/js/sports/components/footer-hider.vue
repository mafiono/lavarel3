<template></template>

<script>
    export default {
        name: "footer-hider",
        computed: {
            hideFooter() {
                return Store.app.currentRoute !== '/';
            }
        },
        methods: {
            setFooterVisibility() {
                if (this.hideFooter) {
                    $('.page-footer > .content').removeClass('show-mobile');
                    $('.page-footer > .mobile-content').removeClass('hidden');
                } else {
                    $('.page-footer > .content').addClass('show-mobile');
                    $('.page-footer > .mobile-content').addClass('hidden');
                }
            }
        },
        mounted() {
            this.$watch('hideFooter', () => {
                this.setFooterVisibility();
            });

            this.setFooterVisibility();
        }
    }
</script>