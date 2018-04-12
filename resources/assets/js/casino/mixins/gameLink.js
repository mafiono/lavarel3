export default {
    props: ['game'],
    methods: {
        open: function () {
            router.push(`/game-lobby/${this.game.id}`);
        },
    },
}
