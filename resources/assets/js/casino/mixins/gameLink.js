export default {
  props: ['game'],
  methods: {
    open: function() {
      if (Store.getters['mobile/getIsMobile']) {
        router.push(`/mobile/launch/${this.game.id}`);
      } else if (this.userLoggedIn) {
        GameLauncher.open(this.game.id);
      } else
        router.push('/registar');
    },
  },
  computed: {
    userLoggedIn() {
      return Store.getters['user/isAuthenticated'];
    }
  },
}
