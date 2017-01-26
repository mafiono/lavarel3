
module.exports.load = function(){
    $('.bonus i.fa-2x').click(function () {
        var self = $(this);
        self.parents('.row').toggleClass('active');
    });
};
module.exports.unload = function () {

};