import {Observable} from 'rxjs/Observable';
import 'rxjs/add/observable/fromEvent';
import 'rxjs/add/observable/fromPromise';
import 'rxjs/add/operator/do';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/combineLatest';

/**
 * Created by miguel on 22/02/2016.
 */
require('../../../external/autonumeric/autoNumeric');
let subscription = null;
let oldVal = null;
module.exports.load = function(){
    // var old = $.validator.prototype.elementValue;
    oldVal = $.validator.prototype.elementValue;
    $.validator.prototype.elementValue = function ($element) {
        // console.log($element);
        return $($element).autoNumeric('get');
    };

    $("#depositForm").validate({
        rules: {
            deposit_value: {
                required: true,
                digits: true,
                min: 10
            }
        },
        messages: {
            deposit_value: {
                required: "Preencha o valor a depositar",
                digits: "Apenas digitos são aceites",
                min: "O valor minimo é 10€.",
            }
        }
    });

    let dpArea = $('#deposit_area');
    let tbArea = $('#deposit_tb');
    let ccArea = $('#deposit_cc');

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
    let inputs = $('#depositForm input[name=payment_method]');
    let $tax = Observable.fromPromise($.get('/ajax-perfil/banco/taxes').promise());
    subscription = Observable.fromEvent(inputs, 'change')
        .do(function (x) {
            let checked = method_bankTransfer.is(':checked');
            dpArea.toggle(!checked);
            tbArea.toggle(checked);

            let cc_check = method_cc.is(':checked') || method_mc.is(':checked');
            ccArea.toggle(cc_check);
        })
        .map(function (x) { return x.target.value; })
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
    if (oldVal !== null) {
        $.validator.prototype.elementValue = oldVal;
        oldVal = null;
    }
};