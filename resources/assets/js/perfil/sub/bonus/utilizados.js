
module.exports.load = function(){
    $('.bonus i.cp-2x').click(function () {
        var self = $(this);
        self.parents('.row').toggleClass('active');
    });
};
module.exports.unload = function () {

};