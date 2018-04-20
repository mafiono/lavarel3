<template>
    <div class="content-games">
        <game v-for="game in games" :game="game" :key="game.id"></game>
    </div>
</template>
<script>
    export default{
        props: [
            'games',
        ],
        components: {
            'game': require('./game.vue')
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/common/variables';

    .content-games {
        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;

        flex-flow: row wrap;
        justify-content: flex-start;
    }
    .game {
        width: 25%;
        padding: 5px;

        @media (max-width: $mobile-screen-width) {
            width: 50%;
        }
    }
</style>