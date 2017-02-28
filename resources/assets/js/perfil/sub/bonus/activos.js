
module.exports.load = function(){
    $('.bonus i.fa-2x').click(function () {
        var self = $(this);
        self.parents('.row').toggleClass('active');
    });
    $('.bonus .cancel').click(function(e) {
        e.preventDefault();
        e.stopPropagation();

        var id = $(this).data('id');
        var title = $(this).data('title');
        $.fn.popup({
            title: 'Bónus',
            text: 'Tem a certeza que pretende cancelar o ' + title + '?',
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Sim",
            cancelButtonText: "Não",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function (confirmed) {
            if (confirmed) {
                $.get('/ajax-perfil/bonus/cancel/' + id)
                    .success(function () {
                        $.fn.popup({
                            title: 'Bónus',
                            text: 'Bónus ' + title + ' cancelado com sucesso!',
                            type: "success"
                        }, function () {
                            window.location.reload();
                        });
                    })
                    .error(function (obj) {
                        var response = obj.responseJSON;
                        $.fn.popup({
                            title: response.title || '&nbsp;',
                            text: response.msg,
                            type: 'error'
                        });
                    })
                    .done();
            } else {
                swal.close();
            }
        });
        return false;
    });
};
module.exports.unload = function () {

};