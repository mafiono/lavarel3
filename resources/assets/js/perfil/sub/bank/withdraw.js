
module.exports.load = function(){
    $('#withdrawal_value').autoNumeric("init",{
        aSep: ' ',
        aDec: '.',
        mDec: 2,
        vMin: '0'
    });
    var max = parseFloat($('#available').val());
    var min = parseFloat($('#min-amount').val());

    if ($('.withdraw-email').length) {
        function toggleEmail() {
            let type = $('#bank_account').find('option:selected').data('type');
            $('.withdraw-email').toggle(type === 'pay_safe_card');
        }
        $('#bank_account').change(toggleEmail);
        toggleEmail();
    }

    $("#saveForm").validate({
        rules: {
            withdrawal_email: {
                email: true
            },
            withdrawal_value: {
                required: true,
                max: max,
                min: min
            }
        },
        messages: {
            withdrawal_email: {
                email: "Preencha o email que usou para a conta My Paysafecard",
            },
            withdrawal_value: {
                required: "Preencha o valor a levantar",
                max: "Não possuí saldo suficiente para o levantamento pedido",
                min: `Preencha um valor superior a ${min}.`,
            }
        }
    });

};
module.exports.unload = function () {
};