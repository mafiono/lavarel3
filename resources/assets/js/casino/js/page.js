page = function (path, fn) {
    if (typeof path === 'string') {
        router.push(path);
    }
};

page.back = (fallbackPath) => {
    if (router.historyCount === 1) {
        page(fallbackPath);

        return;
    }

    router.back();
};

page.current = '';

router.afterEach((to, from) => {
    router.historyCount = router.historyCount ? router.historyCount + 1 : 1;

    page.current = to.path;

    if (to.path === "/mobile/menu-casino") {
        Store.commit('mobile/setView', 'menu-casino');
    } else if (to.path === "/mobile/menu") {
        Store.commit('mobile/setView', 'menu');
    } else {
        Store.commit('mobile/setView', '');
    }
});