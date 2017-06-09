<template>
    <div class="slider" v-show="show">
        <div class="slides">
            <div class="slider">
                <div class="images">
                    <img src="/assets/portal/img/casino/slides/slide1.png">
                </div>
            </div>
            <div class="slider">
                <div class="images">
                    <img src="/assets/portal/img/casino/slides/slide2.png">
                </div>
            </div>
            <div class="slider">
                <div class="images">
                    <img src="/assets/portal/img/casino/slides/slide1.png">
                </div>
            </div>
            <div class="slider">
                <div class="images">
                    <img src="/assets/portal/img/casino/slides/slide2.png">
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