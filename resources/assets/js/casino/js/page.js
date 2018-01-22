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

Store.app.currentRoute = router.currentRoute;

router.afterEach((to, from) => {
    ga('send', { 'hitType': 'pageview', 'page': to.path, 'title': to.name });

    router.historyCount = router.historyCount ? router.historyCount + 1 : 1;

    page.current = to.path;

    Store.app.currentRoute = to.path;

    if (to.path === "/mobile/menu-casino") {
        Store.mobile.view = 'menu-casino';
    } else if (to.path === "/mobile/menu") {
        Store.mobile.view = 'menu';
    } else {
        Store.mobile.view = '';
    }
});