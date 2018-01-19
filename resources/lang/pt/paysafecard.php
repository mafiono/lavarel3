<?php

return [
    'api' =>[
        'unique_account' => 'Não foi possível efetuar o depósito, a conta MyPaysafecard usada não é a que está associada a esta conta!',
        'success' => 'Transação efetuada com sucesso!',
        'error' => 'Erro na transação',
        'errorDB' => 'Falha no pagamento, nenhuma transação encontrada na base de dados',
        'abort' => 'Transação abortada pelo utilizador',
        'validation' => 'Ocorreu um erro ao validar a sua Conta MyPaysafecard, confirme o email introduzido e tente novamente.',
    ],

    'controller' =>[
        'wrong_method' => 'Método de pagamento incorreto!',
        'try_later' => 'Ocorreu um erro, por favor tente mais tarde.',
        'id_success' => 'Criado ID com sucesso!',
        'error_portal' => 'Erro ao comunicar com o portal de pagamentos,por favor tente mais tarde.',
        'success_dep' => 'Depósito efetuado com sucesso!',
    ],

    'unknown' => 'Ocorreu um erro inesperado!',
    'unknown-number' => 'Ocorreu um erro inesperado! (:number)',

    '500' => 'Erro técnico proveniente do Paysafecard.',
    '400' => 'Produto indisponivel.',
    '401' => 'A autenticação falhou devido à chave da API ausente ou inválida.',
    '404' => 'Recurso não encontrado',

    '2001'=> 'Transação já existente.',
    '2017'=> 'Este pagamento não é capturável no momento.',
    '3001'=> 'Comerciante não está ativo.',
    '3007'=> 'Tentativa de débito após a expiração da janela de tempo disponivel.',
    '3100' => 'Produto indisponivel.',
    '3103' => 'Solicitação de pedido duplicada.',
    '3106' => 'Formato de valor inválido.', -
    '3150' => 'Parâmetro ausente.',
    '3151' => 'Moeda inválida.',
    '3161' => 'Comerciante não pode executar esta ação.',
    '3162' => 'Nenhuma conta do cliente encontrada pelas credenciais fornecidas.',
    '3163' => 'Parâmetro inválido.',
    '3164' => 'Transação já existe.',
    '3165' => 'Montante inválido.',
    '3167' => 'Limite do cliente excedido.',
    '3168' => 'Recurso não ativado neste país para este nível kyc(Know your customer).',
    '3169' => 'ID de pagamento colide com a ID de disposição existente.',
    '3170' => 'Limite superior excedido.',
    '3171' => 'Montante do pagamento está abaixo do montante mínimo de pagamento do comerciante.',
    '3179' => 'Reembolso do comerciante excede a transação original.',
    '3180' => 'Transação original do reembolso do comerciante está em estado inválido.',
    '3181' => 'ID de cliente não corresponde ao pagamento original.',
    '3182' => 'ID de cliente comercial em falta.',
    '3184' => 'Nenhuma transação original encontrada.',
    '3185' => 'Conta MyPaysafecard não encontrada na transação original e nenhuma credencial adicional fornecida.',
    '3193' => 'Cliente inativo.',
    '3194' => 'Limite de pagamento anual do cliente excedeu.',
    '3195' => 'Detalhes do cliente no pedido não coincidem com a base de dados.',
    '3198' => 'Já existe o número máximo de clientes comerciais pagos atribuídos a esta conta.',
    '3199' => 'Pagamento bloqueado por motivos de segurança.',

    'customer_details_mismatchd' => 'Detalhes do cliente no pedido não coincidem com a base de dados.', //3195
    'customer_inactive' => 'Cliente inativo.', //3193
    'CUSTOMER_LIMIT_EXCEEDED' => 'Limite do cliente excedido.', //3167
    'CUSTOMER_NOT_FOUND' => 'Nenhuma conta de cliente encontrada pelas credenciais fornecidas.', //3162
    'customer_yearly_payout_limit_reached' => 'Limite de pagamento anual do cliente excedeu.', //3194
    'DUPLICATE_ORDER_REQUEST' => 'Solicitação de pedido duplicada.', //3103
    'duplicate_payout_request' => 'Transação já existe.', //3164
    'FACEVALUE_FORMAT_ERROR' => 'Formato de valor inválido', //3106
    'INVALID_AMOUNT' => 'Montante inválido.', //3165
    'INVALID_CURRENCY' => 'Moeda inválida.', //3151
    'INVALID_PARAMETER' => 'Parâmetro inválido.', //3163
    'KYC_INVALID_FOR_PAYOUT_CUSTOMER' => 'Recurso não ativado neste país para este nível kyc(Know your customer).', //3168
    'max_amount_of_payout_merchants_reached' => 'Já existe o número máximo de clientes comerciais pagos atribuídos a esta conta.', //3198
    'MERCHANT_NOT_ALLOWED_FOR_PAYOUT' => 'Comerciante não pode executar esta ação.', //3161
    'MERCHANT_REFUND_CLIENT_ID_NOT_MATCHING' => 'ID de cliente não corresponde ao pagamento original.', //3181
    'merchant_refund_customer_credentials_missing' => 'Conta MyPaysafecard não encontrada na transação original e nenhuma credencial adicional fornecida.', //3185
    'MERCHANT_REFUND_EXCEEDS_ORIGINAL_TRANSACTION' => 'Reembolso do comerciante excede a transação original.', //3179
    'MERCHANT_REFUND_MISSING_TRANSACTION' => 'Nenhuma transação original encontrada.', //3184
    'MERCHANT_REFUND_ORIGINAL_TRANSACTION_INVALID_STATE' => 'Transação original do reembolso do comerciante está em estado inválido.', //3180
    'MISSING_PARAMETER' => 'Parâmetro ausente.', //3150
    'NO_UNLOAD_MERCHANT_CONFIGURED' => 'ID de cliente comercial em falta.', //3182
    'payout_amount_below_minimum' => 'Montante do pagamento está abaixo do montante mínimo de pagamento do comerciante.', //3171
    'payout_blocked' => 'Pagamento bloqueado por motivos de segurança.', //3199
    'payout_id_collision' => 'ID de pagamento colide com a ID de disposição existente.', //3169
    'PRODUCT_NOT_AVAILABLE' => 'Produto indisponivel.', //3100
    'topup_limit_exceeded' => 'Limite superior excedido.', //3170
];
