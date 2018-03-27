import {Observable} from 'rxjs/Observable';
import 'rxjs/add/observable/fromEvent';
import 'rxjs/add/observable/fromPromise';
import 'rxjs/add/operator/do';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/combineLatest';
import {SwitchJs} from "../../../external/payments/switch";

let subscription = null;
module.exports.load = function(){

    let mbArea = $('#deposit_mb'); let hasRefs = false;
    let dpArea = $('#deposit_area');
    let pscArea = $('#deposit_psc');
    let tbArea = $('#deposit_tb');
    let ccArea = $('#deposit_cc');
    let dsArea = $('#deposit_selection');
    let resArea = $('.reserve-amount');

    if (resArea.length) {
        let resField = resArea.find('#reserve_value');
        let resMax = resField.data('max');
        resField.autoNumeric("init",{
            aSep: ' ',
            aDec: ',',
            mDec: 2,
            vMin: '0',
            vMax: resField.data('max'),
        });
        let form = resArea.find('form');
        form.validate({
            rules: {
                reserve_value: {
                    required: true,
                    min: 0.01,
                    max: resMax,
                },
            },
            messages: {
                reserve_value: {
                    required: "Preencha o valor a depositar",
                    min: "O valor minimo é 0.01€.",
                    max: `O valor maximo é ${resMax}€.`,
                },
            }
        });
    }

    $("#depositForm").validate({
        rules: {
            deposit_value: {
                required: true,
                digits: true,
                min: 10
            },
            cc_name: {
                required: true
            },
            cc_nr: {
                required: true,
                creditcard: true,
            },
            cc_ano: {
                required: true,
                digits: true,
            },
            cc_mes: {
                required: true,
                digits: true,
            },
            cc_cvc: {
                required: true,
                pattern: /^[0-9]{3}$/
            },
        },
        messages: {
            deposit_value: {
                required: "Preencha o valor a depositar",
                digits: "Apenas digitos são aceites",
                min: "O valor minimo é 10€.",
            },
            cc_name: {
                required: "Preencha o nome da conta",
            },
            cc_nr: {
                required: "Preencha o numero de cartão",
                creditcard: "Preencha um cartão válido",
            },
            cc_ano: {
                required: "Obrigatório",
                digits: "Obrigatório"
            },
            cc_mes: {
                required: "Obrigatório",
                digits: "Obrigatório"
            },
            cc_cvc: {
                required: "Preencha o codigo CVC",
                pattern: "Preencha o codigo CVC"
            }
        },
        customProcessStatus: function (status, response) {
            if (!!response.switch_id && !!response.public_key && !!response.mode) {
                // lets process this
                // console.log("Method CC: ", response);

                let switchJs = new SwitchJs(
                    response.mode, // or SwitchJs.environments.LIVE
                    response.public_key // <-- Insert your __Public Key__ here
                );

                switchJs.createInstrument({
                    popUp: false, // optional: when a payment method requires redirecting
                    // the user to a different page, do it on the same browser window
                    charge: response.switch_id, // that you got from step 1
                    instrument: { // these are the payment details that change from method
                        // to method, that you should collect from your form
                        type: 'card_onetime',
                        name: $('#cc_name').val(),
                        number: $('#cc_nr').val(),
                        expirationYear: $('#cc_ano').val(),
                        expirationMonth: $('#cc_mes').val(),
                        cvv: $('#cc_cvc').val()
                    }
                }).then(function successFn(result) {
                    // console.log("Success", result);
                    $.fn.popup({
                        'text': 'Depósito efetuado com sucesso!'
                    }, function () {
                        page('/perfil/banco/depositar');
                    });
                }, function errorFn(err){
                    // console.error("Error", err);
                    $.fn.popup({
                        'type': 'error',
                        'text': 'Ocorreu um erro ao validar os dados, confirme os seus dados novamente!'
                    });
                });

                return true;
            }
            if (!!response.mb && !!response.amount) {
                // console.log('Será MB?', response);
                mbArea.find('#mb_value').val(parseFloat(response.amount).toFixed(2) + ' €');
                mbArea.find('#mb_ent').val(response.mb.entity);
                mbArea.find('#mb_ref').val(response.mb.ref);
                hasRefs = true;

                dpArea.toggle(false);
                mbArea.toggle(true);

                swal.close();

                return true;
            }
            if (!!response.psc && !!response.auth_url) {
                console.log("Method PSC: ", response);

                pscArea.find('iframe').attr('src', response.auth_url);

                dpArea.toggle(false);
                mbArea.toggle(false);
                dsArea.toggle(false);
                pscArea.toggle(true);

                swal.close();

                return true;
            }
            if (!!response.mb && !!response.mb.ref && !!response.mb.entity) {
                // console.log('Será MB?', response);
                mbArea.find('#mb_value').val('Qualquer Valor');
                mbArea.find('#mb_ent').val(response.mb.entity);
                mbArea.find('#mb_ref').val(response.mb.ref);
                hasRefs = true;

                dpArea.toggle(false);
                mbArea.toggle(true);

                swal.close();

                return true;
            }
            return false;
        }
    });

    let total = $('#total');
    let tax = $('#tax');
    let currTax = 0;
    let freeAbove = 0;
    let field = $('#deposit_value');
    field.autoNumeric("init",{
        aSep: ' ',
        aDec: ',',
        mDec: 0,
        vMin: '0'
    });
    function updateValue() {
        var val = field.autoNumeric('get');
        if (val !== "" && field.valid()) {
            var amount = parseFloat(val);
            var taxAmount = Math.round(amount * currTax * 100) / 100;
            if (amount >= freeAbove) taxAmount = 0;
            tax.val(taxAmount.toFixed(2));
            total.val((amount + taxAmount).toFixed(2));
        } else {
            tax.val('0.00');
            total.val('0.00');
        }
    }
    field.on('change keyup blur', updateValue);
    let method_bankTransfer = $('#method_bank_transfer');
    let method_cc = $('#method_cc');
    let method_mc = $('#method_mc');
    let method_mb = $('#method_mb');
    let inputs = $('#depositForm input[name=payment_method]');
    let $tax = Observable.fromPromise($.get('/ajax-perfil/banco/taxes').promise());
    subscription = Observable.fromEvent(inputs, 'change')
        .map(function (x) { return x.target.value; })
        .do(function (x) {
            let checked = method_bankTransfer.is(':checked');
            dpArea.toggle(!checked);
            tbArea.toggle(checked);

            if (method_mb.is(':checked')) {
                mbArea.toggle(true);
                dpArea.toggle(false);
                if (!hasRefs) {
                    var curr = $("#deposit_value").val();
                    $("#deposit_value").val(10);
                    $("#depositForm").submit();
                    $("#deposit_value").val(curr);
                }
            } else {
                mbArea.toggle(false);
            }
            let cc_check = (x === 'cc' || x === 'mc')
                && (method_cc.is(':checked') || method_mc.is(':checked'));
            ccArea.toggle(cc_check);
        })
        .map(function (x) { return x.replace('meowallet_', ''); })
        .combineLatest($tax)
        .map(function (x) { return x[1].taxes[x[0]]; })
        .subscribe(function (onNext) {
            currTax = parseFloat(onNext.tax);
            if (currTax > 0) currTax /= 100;

            freeAbove = parseInt(onNext.free_above);
            if (freeAbove <= 0) freeAbove = parseInt(onNext.max);

            updateValue();
        }, function (onError) {
            console.error(onError);
        });
};
module.exports.unload = function () {
    if (subscription) {
        subscription.unsubscribe();
    }
};