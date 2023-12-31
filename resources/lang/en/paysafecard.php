<?php

return [
    'api' =>[
        'unique_account' => 'Could not make the deposit, the My PaySafeCard account used is not the one that is associated with this account!',
        'success' => 'Successful transaction!',
        'error' => 'Transaction error',
        'errorDB' => 'Payment Failed, no transaction found on DB',
        'abort' => 'User aborted transaction',
        'validation' => 'An error occurred while validating your Mypaysafecard account, confirm the email you entered and try again.',
    ],

    'controller' =>[
        'wrong_method' => 'Wrong payment method!',
        'try_later' => 'An error occurred, please try again later.',
        'id_success' => 'Created ID successfully!',
        'error_portal' => 'Error communicating with payment portal, please try again later.',
        'success_dep' => 'Deposit successfully!',
    ],

    'unknown' => 'Unknown error!',
    'unknown-number' => 'Unknown error (:number)',

    '500' => 'Technical error on Paysafecard\'s end',
    '400' => 'Product not available.',
    '401' => 'Authentication failed due to missing or invalid API key.',
    '404' => 'Resource not found',

    '2001'=> 'Transaction already exists.',
    '2017'=> 'This payment is not capturable at the moment.',
    '3001'=> 'Merchant is not active.',
    '3007'=> 'Debit attempt after expiry of dispo time window.',
    '3100' => 'Product not available.',
    '3103' => 'Duplicate order request.',
    '3106' => 'Invalid facevalue format.',
    '3150' => 'Missing paramenter.',
    '3151' => 'Invalid currency.',
    '3161' => 'Merchant not allowed to perform this Action.',
    '3162' => 'No customer account found by provided credentials.',
    '3163' => 'Invalid paramater.',
    '3164' => 'Transaction already exists.',
    '3165' => 'Invalid amount.',
    '3167' => 'Customer limit exceeded.',
    '3168' => 'Feature not activated in this country for this kyc Level.',
    '3169' => 'Payout id collides with existing disposition id.',
    '3170' => 'Top-up limit exceeded.',
    '3171' => 'Payout amount is below minimum payout amount of the merchant.',
    '3179' => 'Merchant refund exceeds original transaction.',
    '3180' => 'Original Transaction of Merchant Refund is in invalid state.',
    '3181' => 'Merchant Client Id not matching with original Payment.',
    '3182' => 'merchant client Id missing.',
    '3184' => 'No original Transaction found.',
    '3185' => 'my paysafecard account not found on original transaction and no additional credentials provided.',
    '3193' => 'Customer not active.',
    '3194' => 'Customer yearly payout limit exceeded.',
    '3195' => 'An error occurred while validating your Mypaysafecard account, confirm the email you entered and try again.',
    '3198' => 'There is already the maximum number of pay-out merchant clients assigned to this account.',
    '3199' => 'Payout blocked due to security reasons.',
    '3201' => 'There is a problem with your request please contact our support.',

    'customer_details_mismatchd' => 'Customer details from request don\'t match with database.', //3195
    'customer_inactive' => 'Customer not active.', //3193
    'CUSTOMER_LIMIT_EXCEEDED' => 'Customer limit exceeded.', //3167
    'CUSTOMER_NOT_FOUND' => 'No customer account found by provided credentials.', //3162
    'customer_yearly_payout_limit_reached' => 'Customer yearly payout limit exceeded.', //3194
    'DUPLICATE_ORDER_REQUEST' => 'Duplicate order request.', //3103
    'duplicate_payout_request' => 'Transaction already exists.', //3164
    'FACEVALUE_FORMAT_ERROR' => 'Invalid facevalue format.', //3106
    'INVALID_AMOUNT' => 'Invalid amount.', //3165
    'INVALID_CURRENCY' => 'Invalid currency.', //3151
    'INVALID_PARAMETER' => 'Invalid paramater.', //3163
    'KYC_INVALID_FOR_PAYOUT_CUSTOMER' => 'Feature not activated in this country for this kyc Level.', //3168
    'max_amount_of_payout_merchants_reached' => 'There is already the maximum number of pay-out merchant clients assigned to this account.', //3198
    'MERCHANT_NOT_ALLOWED_FOR_PAYOUT' => 'Merchant not allowed to perform this Action.', //3161
    'MERCHANT_REFUND_CLIENT_ID_NOT_MATCHING' => 'Merchant Client Id not matching with original Payment.', //3181
    'merchant_refund_customer_credentials_missing' => 'my paysafecard account not found on original transaction and no additional credentials provided.', //3185
    'MERCHANT_REFUND_EXCEEDS_ORIGINAL_TRANSACTION' => 'Merchant refund exceeds original transaction.', //3179
    'MERCHANT_REFUND_MISSING_TRANSACTION' => 'No original Transaction found.', //3184
    'MERCHANT_REFUND_ORIGINAL_TRANSACTION_INVALID_STATE' => 'Original Transaction of Merchant Refund is in invalid state.', //3180
    'MISSING_PARAMETER' => 'Missing paramenter.', //3150
    'NO_UNLOAD_MERCHANT_CONFIGURED' => 'merchant client Id missing.', //3182
    'payout_amount_below_minimum' => 'Payout amount is below minimum payout amount of the merchant.', //3171
    'payout_blocked' => 'Payout blocked due to security reasons.', //3199
    'payout_id_collision' => 'Payout id collides with existing disposition id.', //3169
    'PRODUCT_NOT_AVAILABLE' => 'Product not available.', //3100
    'topup_limit_exceeded' => 'Top-up limit exceeded.', //3170
];
