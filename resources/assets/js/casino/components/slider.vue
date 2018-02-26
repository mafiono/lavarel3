<template>
    <div class="slider" v-show="show">
        <div class="slides">
            <div class="slider">
                <div class="images">
                    <a href="#" @click.prevent="open(1510)">
                        <img src="/assets/portal/img/casino/slides/slide1.jpg">
                    </a>
                </div>
            </div>
            <div class="slider">
                <div class="images">
                    <a href="#" @click.prevent="open(1585)">
                        <img src="/assets/portal/img/casino/slides/slide2.jpg">
                    </a>
                </div>
            </div>
            <div class="slider">
                <div class="images">
                    <a href="#" @click.prevent="open(209030)">
                        <img src="/assets/portal/img/casino/slides/gonzos-quest.jpg">
                    </a>
                </div>
            </div>
            <div class="slider">
                <div class="images">
                    <a href="#" @click.prevent="open(4612)">
                        <img src="/assets/portal/img/casino/slides/slide4.jpg">
                    </a>
                </div>
            </div>
        </div>
        <div class="switch">
            <ul>
                <li>
                    <div class="on"></div>
                </li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
    </div>
</template>
<script>
    export default {
        data: function() {
            return {
                routes: ['/', '/favorites', '/pesquisa'],
                show: false
            };
        },
        methods: {
            open: function (gameId) {
                if (this.userLoggedIn) {
                    GameLauncher.open(gameId);
                } else
                    router.push('/registar');
            }
        },
        computed: {
            userLoggedIn() {
                return Store.user.isAuthenticated;
            }
        },
        watch: {
            $route: function(to) {
                this.show = this.routes.includes('/' + to.path.split('/')[1]);
            }
        },
        mounted: function() {
            this.show = this.routes.includes(this.$route.path);
        }
    }
</script>