<template>
    <div>
        <div class="games">
            <content-games :games="games"></content-games>
        </div>
        <error-panel v-if="showError">
            <p>Não existem resultados.</p>
            <p>Por favor selecione alguns.</p>
        </error-panel>
    </div>
</template>
<script>
    export default {
        data: function() {
            return this.$root.$data.search
        },
        components: {
            'content-games': require('./../components/content-games.vue'),
            'error-panel': require('../../common/components/error-panel.vue')
        },
        computed: {
            count: function() {
                return this.games.length;
            },
            showError: function() {
                return Store.mobile.isMobile && this.count === 0;
            }
        },
        watch: {
            count: function() {
                if (this.count === 0 && !Store.mobile.isMobile)
                    this.$router.replace('/');
            }
        },
        mounted: function() {
            if (this.count === 0 && !Store.mobile.isMobile)
                this.$router.replace('/');
        }
    }
</script>
