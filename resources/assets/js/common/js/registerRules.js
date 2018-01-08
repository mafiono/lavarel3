export default {
    firstname: [
        {required: {message: 'Preencha o seu primeiro nome.'}}
    ],
    name: [
        {required: {message: 'Preencha os seus apelidos.'}}
    ],
    birth_date: [
        {required: {message: 'Selecione a sua data de nascimento.'}},
        {date: {message: 'Data inválida.'}}
    ],
    email: [
        {toggleValidation: {field: 'conf_email'}},
        {required: {message: 'Preencha o seu email.'}},
        {email: {message: 'Formato inválido'}},
        {remote: {url: '/api/check-users', requestType: 'post', successMessage: 'true'}}
    ],
    conf_email: [
        {equalTo: {message: 'Os emails não correspondem.', field: 'email'}}
    ],

    nationality: [
        {required: {message: 'Selecione a sua nacionalidade.'}}
    ],
    document_number: [
        {required: {message: 'Preencha o seu nº de identificação.'}},
        {minLength: {message: 'Mínimo de 6 caracteres.', minLength: 6}},
        {maxLength: {message: 'Máximo de 10 caracteres.', maxLength: 10}},
        {remote: {url: '/api/check-users', requestType: 'post', successMessage: 'true'}}
    ],
    sitprofession: [
        {required: {message: 'Selecione a sua situação profissional.'}}
    ],
    tax_number: [
        {required_country: {message: 'Preencha o seu nº fiscal.', field: 'nationality'}},
        {digits: {message: 'Só são permitidos dígitos.'}},
        {minLength: {message: 'Mínimo de 6 caracteres.', minLength: 6}},
        {maxLength: {message: 'Máximo de 10 caracteres.', maxLength: 10}},
        {remote: {url: '/api/check-users', requestType: 'post', successMessage: 'true'}}
    ],
    bank_bic: [
        {required_if: {message: 'Introduza um BIC/SWIFT.', field: 'bank_iban'}},
        {swift: {message: 'Introduza um BIC/SWIFT válido.'}},
    ],
    bank_name: [
        {required_if: {message: 'Preencha o nome do seu banco.', field: 'bank_bic'}},
        {minLength: {message: 'Mínimo de 3 caracteres.', minLength: 3}},
    ],

    bank_iban: [
        {required_if: {message: 'Introduza um IBAN.', field: 'bank_name'}},
        {toggleValidation: {field: 'bank_name'}},
        {iban: {message: 'Introduza um IBAN válido.'}}
    ],
    country: [
        {required: {message: 'Preencha o seu país.'}}
    ],
    address: [
        {required: {message: 'Preencha a sua morada.'}},
        {minLength: {message: 'Mínimo de 10 caracteres.', minLength: 10}},
        {maxLength: {message: 'Máximo de 150 caracteres.', maxLength: 150}}
    ],
    city: [
        {required: {message: 'Preencha a sua cidade.'}},
        {minLength: {message: 'Mínimo de 4 caracteres.', minLength: 4}},
        {maxLength: {message: 'Máximo de 100 caracteres.', maxLength: 100}}

    ],
    district: [
        {required_district: {message:'Preencha o seu distrito.' ,field:'country'}},
    ],
    zip_code: [
        {required: {message: 'Preencha o seu código postal.'}},
        {zipCode: {message: 'Formato: xxxx-xxx.'}}
    ],
    phone: [
        {required: {message: 'Preencha o seu telefone.'}},
        {phone: {message: 'Formato: +351 xxxxxxxxx.'}}
    ],
    username: [
        {required: {message: 'Preencha o seu utilizador.'}},
        {minLength: {message: 'Mínimo de 5 caracteres.', minLength: 5}},
        {maxLength: {message: 'Máximo de 45 caracteres.', maxLength: 45}},
        {username: {message: 'Formato inválido.'}},
        {remote: {url: '/api/check-users', requestType: 'post', successMessage: 'true'}}
    ],
    password: [
        {required: {message: 'Preencha a sua palavra passe.'}},
        {minLength: {message: 'Mínimo de 8 caracteres.', minLength: 8}},
        {maxLength: {message: 'Máximo de 20 caracteres.', maxLength: 20}},
        {password: {message: '1 maiúscula, 1 minúscula e 1 numero.'}},
        {toggleValidation: {field: 'conf_password'}}
    ],
    conf_password: [
        {equalTo: {message: 'As palavras passe não correspondem.', field: 'password'}}
    ],
    captcha: [
        {required: {message: 'Introduza o código do captcha.'}},
        {minLength: {message: 'Mínimo de 5 caracteres.', minLength: 5}},
        {maxLength: {message: 'Máximo de 5 caracteres.', maxLength: 5}}
    ],
    general_conditions: [
        {is: {message: 'Por favor confirme que aceita os Termos e Condições.', value: true}}
    ]
}
