export default {
  props: ['game'],
  methods: {
    open: function() {
      if (Store.mobile.isMobile) {
        router.push(`/mobile/launch/${this.game.categoryId}`);
      } else if (this.userLoggedIn) {
        GameLauncher.open(this.game.categoryId);
      } else
        router.push('/registar');
    },
  },
  computed: {
    userLoggedIn() {
      return Store.user.isAuthenticated;
    }
  },
}
