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
                }
            },
            messages: {
                dias: {
                    required: 'Introduza o numero de dias.',
                    min: 'O minimo de dias é 90.'
                }
            }
        });

        var cDays = $('#content-days');
        var tbDays = $('#dias');
        var sType = $('#self_exclusion_type');
        var rx = Rx.Observable
            .fromEvent(sType, 'change')
            .map(function(e){ return  e.target.value; })
            .merge(Rx.Observable.of(sType.val()))
            .map(function(val){
                return 'minimum_period' === val;
            })
            .subscribe(function onNext(showHide){
                cDays.toggle(showHide);
                if (showHide) {
                    tbDays.rules('add', {
                        required: true,
                        min: 90
                    });
                } else {
                    tbDays.rules('remove', 'required min');
                }
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