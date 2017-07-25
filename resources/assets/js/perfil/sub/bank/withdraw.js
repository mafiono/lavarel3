
module.exports.load = function(){
    $('#withdrawal_value').autoNumeric("init",{
        aSep: ' ',
        aDec: '.',
        mDec: 2,
        vMin: '0'
    });
    var max = parseFloat($('#available').val());

    $("#saveForm").validate({
        rules: {
            withdrawal_value: {
                required: true,
                max: max,
                min: 10
            }
        },
        messages: {
            withdrawal_value: {
                required: "Preencha o valor a levantar",
                max: "Não possuí saldo suficiente para o levantamento pedido",
                min: "Preencha um valor superior a 10.",
            }
        }
    });

};
module.exports.unload = function () {
};