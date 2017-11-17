<template>
    <a class="game-link" @click.prevent="open()"> {{ game.name }} </a>
</template>

<script>
  import gameLink from '../mixins/gameLink'

  export default {
    mixins: [gameLink]
  }
</script>
