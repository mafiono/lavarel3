import {Observable} from 'rxjs/Observable';
import 'rxjs/add/observable/of';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/merge';
import * as forms from '../../helpers/forms';

let subscriptions = [];
module.exports.load = function(){
    'use strict';
    let saveForm = $("#saveForm");
    if (saveForm.length > 0){
        $('#rp_dias').autoNumeric('init', {
            mDec: 0,
            vMin: 1,
            vMax: 90
        });
        $('#se_meses').autoNumeric('init', {
            mDec: 0,
            vMin: 3,
            vMax: 999
        });
        saveForm.validate({
            submitHandler: function (form, event) {
                $.fn.popup({
                    title: 'Submeter autoexclusão?',
                    text: 'Após submeter este pedido terá de aguardar o tempo previsto no regulamento para poder reverter esta ação',
                    showCancelButton: true,
                }, function (accept) {
                    if (accept) {
                        setTimeout(() => forms.submitHandler(form, event), 50);
                        return true;
                    }
                });
            },
            showErrors: function(errorMap, errorList) {
                $.each(errorList, function(a, b){
                    if (! b.element) { b.element = $('<div>'); }
                });
                this.defaultShowErrors();
            },
            rules: {
                rp_dias: {
                    required: false,
                    min: 1,
                    max: 90
                },
                se_meses: {
                    required: false,
                    min: 3,
                    max: 999
                },
                motive: {
                    required: true,
                    minlength: 5,
                    maxlength: 1000
                }
            },
            messages: {
                rp_dias: {
                    required: 'Introduza o numero de dias.',
                    min: 'O minimo de dias é 1.',
                    max: 'O máximo de dias é 90.'
                },
                se_meses: {
                    required: 'Introduza o numero de dias.',
                    min: 'O minimo de dias é 3 meses.',
                    max: 'O máximo de dias é 999 meses.'
                },
                motive: {
                    required: 'Introduza um motivo',
                    minlength: 'Pelo menos 5 caracteres.',
                    maxlength: 'Um texto com um máximo de 1000 caracteres.'
                }
            }
        });

        let tMotive = $('#motive_option input');
        if (tMotive !== undefined)
        {
            let taMotive = $('#motive');
            let rx2 = Observable
                .fromEvent(tMotive, 'change')
                .map(function(e){
                    return {
                        key: e.target.value,
                        text: e.target.parentElement.textContent.trim()
                    };
                })
                .merge(Observable.of({ key: '', text: '' }))
                .do(function (obj){ taMotive.toggle(obj.key === 'other'); })
                .map(function (obj){ return (obj.key === 'other' || obj.key === '') ? '' : obj.text; })
                .subscribe(function onNext(val){
                    taMotive.find('textarea').val(val);
                });
            subscriptions.push(rx2);
        }
        let sType = $('#self_exclusion_type input');
        if (sType.length > 0)
        {
            let rpDays = $('#rp_dias');
            let seMonths = $('#se_meses');

            let rx = Observable
            .fromEvent(sType, 'change')
            .map(function(e){ return  e.target.value; })
            .merge(Observable.of(sType.val()))
                .do(function () {rpDays.removeAttr('required min max disabled').rules('remove', 'required min max');})
                .do(function () {seMonths.removeAttr('required min max disabled').rules('remove', 'required min max');})
            .map(function(val){
                let setts = null;
                switch (val){
                    case 'minimum_period':
                        setts = {
                            required: true,
                            min: 3,
                            max: 999,
                        };
                        seMonths.val(3)
                            .attr(setts)
                            .focus()
                            .rules('add', setts);
                        return true;
                    case 'reflection_period':
                        setts = {
                            required: true,
                            min: 1,
                            max: 90,
                        };
                        rpDays.val(1)
                            .attr(setts)
                            .focus()
                            .rules('add', setts);
                        return true;
                }
                return false;
            })
            .subscribe(function onNext(showHide){
                // console.log(showHide);
            });
            subscriptions.push(rx);
        }
    }
    let form = $("#revokeForm");
    if (form.length > 0){
        form.validate();
        form.find('input[type=submit]')
            .on('click', function(e){
                e.preventDefault();
                e.stopPropagation();

                $.fn.popup({
                    text: "Tem a certeza que pretende revogar o seu pedido de autoexclusão?",
                    type: 'error',
                    confirmButtonText: '',
                    showCancelButton: true,
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        form.submit();
                    }
                });
        });
    }
};
module.exports.unload = function () {
    var sub = null;
    while((sub=subscriptions.pop()) != null) {
        sub.unsubscribe();
    }
};