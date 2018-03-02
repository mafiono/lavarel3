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
                    $('.page-footer').removeClass('visible');
                } else {
                    $('.page-footer').addClass('visible');
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