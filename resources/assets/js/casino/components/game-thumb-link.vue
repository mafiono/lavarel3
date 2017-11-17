<template>
    <img :src="'/assets/portal/img/casino/games/' + game.image" :alt="game.name" class="game-thumb-link" @click="open" :width="width" :height="height">
</template>

<script>
  import gameLink from '../mixins/gameLink';

  export default {
    props: ['width', 'height'],
    mixins: [gameLink]
  }
</script>

<style lang="scss">
    @import '../../../sass/casino/mixins';

    .game-thumb-link {
        cursor: pointer;
        @include bounceIn(2s);

        &:hover {
            animation-name: bounceIn;
        }
    }
</style>
