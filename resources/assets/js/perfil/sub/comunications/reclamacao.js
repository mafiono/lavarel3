
module.exports.load = function(){
    $('#saveForm').validate();
    $('.complains .complain').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).toggleClass('active').find('.details').slideToggle();
    });
};
module.exports.unload = function () {

};