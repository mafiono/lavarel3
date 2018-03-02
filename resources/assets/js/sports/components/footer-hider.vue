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
                    $('.page-footer').addClass('hide');
                } else {
                    $('.page-footer').removeClass('hide');
                }
            }
        },
        mounted() {
            this.$watch('hideFooter', () => {
                this.setFooterVisibility();
            });
        }
    }
</script>