
module.exports.load = function(){
    $('#saveForm').validate();
    $('.grupo .settings-switch').change(function () {
        $('#saveForm').submit();
    });
};
module.exports.unload = function () {

};