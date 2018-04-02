<template>
    <div class="mini-game" v-if="loaded">
        <iframe :src="url" frameborder="0" scrolling="no" width="100%" height="400px"></iframe>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                loaded: false,
            }
        },
        computed: {
            url() {
                let gameType = Store.user.isAuthenticated ? 'game' : 'game-demo';
                return `/casino/${gameType}/6674`;
            }
        },
        mounted() {
            setTimeout(() => this.loaded = true, 3000);
        }
    }
</script>
