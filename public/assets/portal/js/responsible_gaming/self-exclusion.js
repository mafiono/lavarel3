/**
 * Created by miguel on 11/02/2016.
 */
$(function () {
    'use strict';
    if ($("#saveForm").length > 0){
        $("#saveForm").validate({
            success: function (label, input) {
                input = $(input);
                var errorDiv = input.siblings('.error');
                input.siblings('.success-color').remove();
                input.siblings('.warning-color').remove();
                errorDiv.before('<i class="fa fa-check-circle success-color"></i>');
            },
            errorPlacement: function (error, input) {
                input = $(input);
                var errorDiv = input.siblings('.error');

                input.siblings('.warning-color').remove();
                input.siblings('.success-color').remove();
                errorDiv.before('<i class="fa fa-times-circle warning-color"></i>');
                errorDiv.before('<span class="warning-color">' + error.text() + '</span>');
            },
            showErrors: function(errorMap, errorList) {
                $("#summary").empty();
                $.each(errorMap, function(a, b){
                    if ($('#' + a).length == 0)
                    $("#summary").append(b);
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
            var rx2 = Rx.Observable
                .fromEvent(tMotive, 'change')
                .map(function(e){
                    return {
                        key: e.target.value,
                        text: e.target.parentElement.textContent.trim()
                    };
                })
                .merge(Rx.Observable.of({ key: '', text: '' }))
                .do(function (obj){ taMotive.toggle(obj.key === 'other'); })
                .map(function (obj){ return (obj.key === 'other' || obj.key === '') ? '' : obj.text; })
                .subscribe(function onNext(val){
                    taMotive.find('textarea').val(val);
                });
        }
        var sType = $('#self_exclusion_type input');
        if (sType.length > 0)
        {
            var rpDays = $('#rp_dias');
            var seDays = $('#se_dias');
            var rx = Rx.Observable
            .fromEvent(sType, 'change')
            .map(function(e){ return  e.target.value; })
            .merge(Rx.Observable.of(sType.val()))
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
                            max: 90
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
        }
    }
    if ($("#revokeForm").length > 0){
        // TODO convert to new popup system
        $("#revokeForm").submit(function(e){
            var a = confirm("Tem a certeza que pretende revogar o seu pedido de auto-exclusão?");
            if (!a){
                e.preventDefault();
            }
        });
    }
});