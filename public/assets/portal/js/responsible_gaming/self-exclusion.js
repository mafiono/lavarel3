/**
 * Created by miguel on 11/02/2016.
 */
$(function () {
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
                dias: {
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
                dias: {
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

        var cDays = $('#content-days');
        var tbDays = $('#dias');
        var tMotive = $('#type_motive select').get(0);
        var taMotive = $('#motive');
        var sType = $('#self_exclusion_type');
        var rxMsg = $('#reflexion-msg');
        var rx2 = Rx.Observable
            .fromEvent(tMotive, 'change')
            .map(function(e){ return  e.target; })
            .merge(Rx.Observable.of(tMotive))
            .map(function (elt){
                taMotive.toggle(elt.value === 'other');
                if (elt.value === 'other')
                    return '';

                if (elt.selectedIndex == -1)
                    return '';

                return elt.options[elt.selectedIndex].text;
            })
            .subscribe(function onNext(val){
                taMotive.find('textarea').val(val);
            });
        var rx = Rx.Observable
            .fromEvent(sType, 'change')
            .map(function(e){ return  e.target.value; })
            .merge(Rx.Observable.of(sType.val()))
            .map(function(val){
                tbDays
                    .removeAttr('required min max')
                    .rules('remove', 'required min max');
                rxMsg.removeClass('hidden').hide();
                switch (val){
                    case 'minimum_period':
                        var setts = {
                            required: true,
                            min: 90
                        };
                        tbDays.val(90)
                            .attr(setts)
                            .rules('add', setts);
                        return true;
                    case 'reflection_period':
                        var setts = {
                            required: true,
                            min: 1,
                            max: 90
                        };
                        tbDays.val(1)
                            .attr(setts)
                            .rules('add', setts);
                        rxMsg.show();
                        return true;
                }
                return false;
            })
            .subscribe(function onNext(showHide){
                cDays.removeClass('hidden').toggle(showHide);
            });
    }
    if ($("#revokeForm").length > 0){
        $("#revokeForm").submit(function(e){
            var a = confirm("Tem a certeza que pretende revogar o seu pedido de auto-exclusão?");
            if (!a){
                e.preventDefault();
            }
        });
    }
});