
module.exports.load = function(){
    $('#withdrawal_value').autoNumeric("init",{
        aSep: ' ',
        aDec: ',',
        mDec: 0,
        vMin: '0'
    });
    var max = parseFloat($('#available').val());

    $("#saveForm").validate({
        rules: {
            withdrawal_value: {
                required: true,
                digits: true,
                max: max,
                min: 10
            }
        },
        messages: {
            withdrawal_value: {
                required: "Preencha o valor a levantar",
                digits: "Apenas digitos são aceites",
                max: "Não possuí saldo suficiente para o levantamento pedido",
                min: "Preencha um valor superior a 10.",
            }
        }
    });

};
module.exports.unload = function () {
};