/**
 * Created by miguel on 22/02/2016.
 */
require('../../external/autonumeric/autoNumeric');
var subscription = null;
var oldVal = null;
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

    var dpArea = $('#deposit_area');
    var tbArea = $('#deposit_tb');

    var total = $('#total');
    var tax = $('#tax');
    var currTax = 0;
    var freeAbove = 0;
    var field = $('#deposit_value');
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
    var inputs = $('#depositForm input[name=payment_method]');
    var $tax = Rx.Observable.fromPromise($.get('/ajax-perfil/banco/taxes').promise());
    subscription = Rx.Observable.fromEvent(inputs, 'change')
        .do(function (x) {
            var checked = $('#method_bank_transfer').is(':checked');
            dpArea.toggle(!checked);
            tbArea.toggle(checked);
        })
        .map(function (x) { return x.target.value; })
        .zip($tax)
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