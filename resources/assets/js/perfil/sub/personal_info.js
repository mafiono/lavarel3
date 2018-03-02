import {Observable} from 'rxjs/Observable';
import 'rxjs/add/observable/fromEvent';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/distinctUntilChanged';

import * as auth from '../helpers/input-file';
let subscription = null;
module.exports.load = function () {
    auth.load();
    var moradaChanged = false;
    // validate signup form on keyup and submit
    $("#saveForm").validate({
        beforeSubmit: function beforeSubmit(form) {
            $.fn.popup({
                title: 'Aguarde por favor!',
                type: 'warning',
                text: '<div class="bs-wp"><div class="progress">\
                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">\
                        <span class="sr-only">0% Completo</span>\
                    </div>\
                </div></div>',
                html: true,
                showCancelButton: false,
                showConfirmButton: false
            });
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
                minlength:6,
                maxlength:15
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
                required: "Preencha o seu país"
            },
            address: {
                required: "Preencha a sua morada"
            },
            city: {
                required: "Preencha a sua cidade"
            },
            zip_code: {
                required: "Preencha o seu código postal",
                minlength: "O código postal tem de ter pelo menos 4 caracteres"
            },
            phone: {
                required: "Preencha o seu telefone",
                maxlength: "Por favor, verifique o número",
                minlength:"Por favor, verifique o número"
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
    if (!uploadGroup.length)
        return;

    inputs.each(function (idx, it) {
       obj[it.id] = {
           changed: false,
           val: it.value
       };
    });
    subscription = Observable.fromEvent(inputs, 'keyup change')
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
};
module.exports.unload = function () {
    auth.unload();
    if (subscription) {
        subscription.unsubscribe();
    }
};