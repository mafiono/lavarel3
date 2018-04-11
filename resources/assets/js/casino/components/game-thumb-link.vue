<template>
    <div>
        <img :src="'/assets/portal/img/casino/games/' + game.image" :alt="game.name" class="game-thumb-link" @click="open"
            width="100%"
        >
    </div>
</template>

<script>
  import gameLink from '../mixins/gameLink';

  export default {
    props: ['width', 'height'],
    mixins: [gameLink]
  }
</script>

<style lang="scss" scoped>
    @import '../../../sass/casino/mixins';

    .game-thumb-link {
        cursor: pointer;
        @include bounceIn(2s);

        &:hover {
            animation-name: bounceIn;
        }
    }
    div
    {
        width: 100%;
        min-height: 150px;
        max-height: 200px;
        overflow: hidden;
        position: relative;
    }
    img {
        position:absolute;
        left: -100%;
        right: -100%;
        top: -100%;
        bottom: -100%;
        margin: auto;
        min-height: 100%;
        min-width: 100%;
    }
</style>
