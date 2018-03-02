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
                    $('.page-footer').hide();
                } else {
                    $('.page-footer').show(100);
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