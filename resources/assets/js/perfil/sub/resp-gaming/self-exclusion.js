import {Observable} from 'rxjs/Observable';
import 'rxjs/add/observable/of';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/merge';
/**
 * Created by miguel on 11/02/2016.
 */
var subscriptions = [];
module.exports.load = function(){
    'use strict';
    if ($("#saveForm").length > 0){
        $("#saveForm").validate({
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
                    max: 30
                },
                se_dias: {
                    required: false,
                    min: 90
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
                    max: 'O máximo de dias é 30.'
                },
                se_dias: {
                    required: 'Introduza o numero de dias.',
                    min: 'O minimo de dias é 90.'
                },
                motive: {
                    required: 'Introduza um motivo',
                    minlength: 'Pelo menos 5 caracteres.',
                    maxlength: 'Um texto com um máximo de 1000 caracteres.'
                }
            }
        });

        var tMotive = $('#motive_option input');
        if (tMotive !== undefined)
        {
            var taMotive = $('#motive');
            var rx2 = Observable
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
        var sType = $('#self_exclusion_type input');
        if (sType.length > 0)
        {
            var rpDays = $('#rp_dias');
            var seDays = $('#se_dias');
            var rx = Observable
            .fromEvent(sType, 'change')
            .map(function(e){ return  e.target.value; })
            .merge(Observable.of(sType.val()))
                .do(function () {rpDays.removeAttr('required min max disabled').rules('remove', 'required min max');})
                .do(function () {seDays.removeAttr('required min max disabled').rules('remove', 'required min max');})
            .map(function(val){
                switch (val){
                    case 'minimum_period':
                        var setts = {
                            required: true,
                            min: 90
                        };
                        seDays.val(90)
                            .attr(setts)
                            .rules('add', setts);
                        return true;
                    case 'reflection_period':
                        var setts = {
                            required: true,
                            min: 1,
                            max: 30
                        };
                        rpDays.val(1)
                            .attr(setts)
                            .rules('add', setts);
                        return true;
                }
                return false;
            })
            .subscribe(function onNext(showHide){
                console.log(showHide);
            });
            subscriptions.push(rx);
        }
    }
    if ($("#revokeForm").length > 0){
        // TODO convert to new popup system
        var form = $("#revokeForm");
        form.validate();
        form.find('input[type=submit]')
            .on('click', function(e){
                e.preventDefault();
                e.stopPropagation();

                var $url = form.attr('action');
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