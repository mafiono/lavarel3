var deposits = require('../../perfil/sub/bank/deposit');
module.exports.load = function () {
    ga('send', {
        hitType: 'event',
        eventCategory: 'register',
        eventAction: 'step3-loaded-form',
        eventLabel: 'Step 3 Loaded'
    });
    deposits.load();
};
module.exports.unload = function () {
    deposits.unload();
};