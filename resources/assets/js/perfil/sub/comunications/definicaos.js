
module.exports.load = function(){
    $('#saveForm').validate({
        beforeSubmit: false
    });
    $('.grupo .settings-switch').change(function () {
        $('#saveForm').submit();
    });
};
module.exports.unload = function () {

};