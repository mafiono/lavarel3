/**
 * Created by miguel on 29/01/2016.
 */
(function(){
    var moradaChanged = false;
    // validate signup form on keyup and submit
    $("#saveForm").validate({
        success: function(label, input) {
            input = $(input);
            input.siblings('.success-color').remove();
            input.after('<i class="fa fa-check-circle success-color"></i>');
            input.siblings('.warning-color').remove();
        },
        errorPlacement: function(error, input) {
            input = $(input);
            input.siblings('.warning-color').remove();
            input.siblings('span').find('.warning-color').remove();
            input.after('<span><font class="warning-color">'+error.text()+'</font></span>');
            input.after('<i class="fa fa-exclamation-circle warning-color"></i>');
            input.siblings('.success-color').remove();
        },
        rules: {
            profession: {
                required: true
            },
            country: {
                required: true
            },
            address: {
                required: true
            },
            city: {
                required: true
            },
            zip_code: {
                required: true,
                minlength: 4
            },
            phone: {
                required: true,
                digits: true
            },
            upload: {
                required: false
            }
        },
        messages: {
            profession: {
                required: "Preencha a sua profissão"
            },
            country: {
                required: "Preencha o seu país",
            },
            address: {
                required: "Preencha a sua morada",
            },
            city: {
                required: "Preencha a sua cidade",
            },
            zip_code: {
                required: "Preencha o seu código postal",
                minlength: "O código postal tem de ter pelo menos 4 caracteres"
            },
            phone: {
                required: "Preencha o seu telefone",
                digits: "Apenas digitos são aceites"
            },
            upload: {
                required: "Introduza um comprovativo de morada"
            }
        }
    });

    var inputs = $('#country, #address, #city, #zip_code');
    var uploadGroup = $('#file_morada');
    var uploadField = $('#upload');
    var obj = {};
    inputs.each(function (idx, it) {
       obj[it.id] = {
           changed: false,
           val: it.value
       };
    });
    Rx.Observable.fromEvent(inputs, 'keyup change')
        .map(function (e){
            obj[e.target.id].changed = obj[e.target.id].val !== e.target.value;
            var upload = false;
            Object.keys(obj).forEach(function (t, y){
                upload = upload || obj[t].changed;
            });
            return upload;
        })
        .distinctUntilChanged()
        .subscribe(
            function next(show){
                if (show){
                    uploadGroup.show();
                    uploadField.rules( "add", {
                        required: true
                    });
                    $('.alert').remove();
                } else {
                    uploadGroup.hide();
                    uploadField.rules( "remove", "required");
                }
                // console.log('Var: ', show);
            }
        );
})();