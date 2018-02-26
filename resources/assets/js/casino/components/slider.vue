<template>
    <div class="slider" v-show="show">
        <div class="slides">
            <div class="slider">
                <div class="images">
                    <a href="#" @click.prevent="open(1)">
                        <img :src="'/assets/portal/img/casino/slides/slide1.jpg?v=' + hash">
                    </a>
                </div>
            </div>
            <div class="slider">
                <div class="images">
                    <a href="#" @click.prevent="open(1)">
                        <img :src="'/assets/portal/img/casino/slides/slide2.jpg?v=' + hash">
                    </a>
                </div>
            </div>
            <div class="slider">
                <div class="images">
                    <a href="#" @click.prevent="open(2)">
                        <img :src="'/assets/portal/img/casino/slides/slide3.jpg?v=' + hash">
                    </a>
                </div>
            </div>
            <div class="slider">
                <div class="images">
                    <a href="#" @click.prevent="open(3)">
                        <img :src="'/assets/portal/img/casino/slides/slide4.jpg?v=' + hash">
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
                show: false,
                hash: window.RAND_HASH,
            };
        },
        methods: {
            open: function (posId) {
                if (this.userLoggedIn) {
                    GameLauncher.open(window.SLIDE_GAMES[posId]);
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