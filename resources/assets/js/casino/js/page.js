page = function (path, fn) {
    if (typeof path === 'string') {
        router.push(path);
    }
};

page.current = '';