var deposits = require('../../perfil/sub/bank/deposit');
module.exports.load = function () {
    deposits.load();
};
module.exports.unload = function () {
    deposits.unload();
};