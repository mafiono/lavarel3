<?php
return [
    'link' => [
        'name' => 'Ajuda ao Cliente'
    ],
    'types' => [
        'reflection_period' => 'auto-reflexão',
        'minimum_period' => 'autoexclusão',
        '3months_period' => 'autoexclusão',
        '1year_period' => 'autoexclusão',
        'undetermined_period' => 'autoexclusão',
    ],
    'errors' => [
        'select_self_exclusion_type' => 'Selecione o tipo de autoexclusão!',
        'missing_motive' => 'Indique o motivo!',
        'missing_se_meses' => 'Indique o nr de meses!',
        'min_se_meses' => 'Minimo de meses é 3!',
        'max_se_meses' => 'Máximo de meses é 999!',
        'missing_rp_dias' => 'Indique o nr de dias!',
        'min_rp_dias' => 'Minimo de dias é de 1!',
        'max_rp_dias' => 'Máximo de dias é 90!',
        'unknow_type' => 'Tipo de autoexclusão desconhecido',
        'fail_saving' => 'Falha ão gravar os dados',
    ]
];