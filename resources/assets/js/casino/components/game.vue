<template>
    <div class="game">
        <div style="position: relative; z-index:1" v-show="game.is_new">
            <span class="tag">Novo</span>
        </div>
        <img :src="'/assets/portal/img/casino/games/' + game.image" alt="" class="game-img" @click="open">
        <span class="name">{{game.name}}</span>
        <favorite :id="game.id"></favorite>
    </div>
</template>

<script>
    export default{
        props: [
            'game'
        ],
        methods: {
            open: function() {
                if (userLoggedIn) {
                    var width = 1200;
                    var height = 800;

                    window.open('/casino/game/' + this.game.id, 'newwindow',
                        'width=' + width + ', height=' + height + ', top='
                        + ((window.outerHeight - height) / 2) + ', left=' + ((window.outerWidth - width) / 2)
                    );
                } else
                    this.$router.push('/registar');
            }
        },
        components: {
            'favorite': require('./favorite.vue')
        }
    }
</script>


