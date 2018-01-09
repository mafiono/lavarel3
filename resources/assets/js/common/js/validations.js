import isIBAN from './ibanValidator'

export default function (form) {
    return {
        required (field, params) {
            return new Promise((resolve) => {
                if (form[field] === '') {
                    form.errors[field] = params.message
                        ? params.message
                        : `${field} is required.`
                } else {
                    resolve()
                }
            })
        },
        email (field, params) {
            let testEmail = (email) => {
                return /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(email)
            }

            return new Promise((resolve) => {
                if (!testEmail(form[field])) {
                    form.errors[field] = params.message
                        ? params.message
                        : 'Invalid email.'
                } else {
                    resolve()
                }
            })
        },
        date (field, params) {
            return new Promise((resolve) => {
                let date = moment(form[field])

                if (!date.isValid()) {
                    form.errors[field] = params.message
                        ? params.message
                        : 'Invalid date.'
                } else {
                    resolve()
                }
            })
        },
        is (field, params) {
            return new Promise((resolve) => {
                if (form[field] !== params.value) {
                    form.errors[field] = params.message
                        ? params.message
                        : `Value should be ${params.value}.`
                } else {
                    resolve()
                }
            })
        },
        minLength (field, params) {
            return new Promise((resolve) => {
                if (form[field].length < params.minLength) {
                    form.errors[field] = params.message
                        ? params.message
                        : `Minimum length is ${params.minLength}.`
                } else {
                    resolve()
                }
            })
        },
        maxLength (field, params) {
            return new Promise((resolve) => {
                if (form[field].length > params.maxLength) {
                    form.errors[field] = params.message
                        ? params.message
                        : `Maximum length is ${params.maxLength}.`
                } else {
                    resolve()
                }
            })
        },
        digits (field, params) {
            let testDigits = (value) => {
                return /^\d+$/.test(value)
            }

            return new Promise((resolve) => {
                if (!testDigits(form[field])) {
                    form.errors[field] = params.message
                        ? params.message
                        : `Only digits are allowed.`
                } else {
                    resolve()
                }
            })
        },
        zipCode (field, params) {
            let testZipCode = (zipCode) => {
                return /^[0-9]{4}-[0-9]{3}$/.test(zipCode)
            }

            return new Promise((resolve) => {
                if (!testZipCode(form[field])) {
                    form.errors[field] = params.message
                        ? params.message
                        : `Format: xxxx-xxx.`
                } else {
                    resolve()
                }
            })
        },
        phone (field, params) {
            let testPhone = (phone) => {
                return /^\+\d{2,3}\s?\d{6,11}$/.test(phone)
            }

            return new Promise((resolve) => {
                if (!testPhone(form[field])) {
                    form.errors[field] = params.message
                        ? params.message
                        : `Formato: +xx(x) xxxxxxxxx.`
                } else {
                    resolve()
                }
            })
        },
        username (field, params) {
            let testUsername = (username) => {
                return /^(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?![_.])$/.test(username)
            }

            return new Promise((resolve) => {
                if (!testUsername(form[field])) {
                    form.errors[field] = params.message
                        ? params.message
                        : 'Invalid format.'
                } else {
                    resolve()
                }
            })
        },
        password (field, params) {
            let testPassword = (password) => {
                return /^(?=.*[A-Z])(?=.*[0-9])(?=.*[a-z]).{8,20}$/.test(password)
            }

            return new Promise((resolve) => {
                if (!testPassword(form[field])) {
                    form.errors[field] = params.message
                        ? params.message
                        : '1 uppercase, 1 lowercase and 1 number.'
                } else {
                    resolve()
                }
            })
        },
        equalTo (field, params) {
            return new Promise((resolve) => {
                if (form[field] !== form[params.field]) {
                    form.errors[field] = params.message
                        ? params.message
                        : `${field} doesn't match ${params.field}`
                } else {
                    resolve()
                }
            })
        },
        toggleValidation (field, params) {
            return new Promise((resolve) => {
                form.validate(params.field)

                resolve()
            })
        },
        remote(field, params) {
            return new Promise((resolve) => {
                $[params.requestType](params.url, { [field] : form[field]})
                    .done((data) => {
                        if (data !== params.successMessage) {
                            form.errors[field] = params.message
                                ? params.message
                                : data
                        } else {
                            resolve()
                        }
                    })
            })
        },
        iban(field, params) {
            return new Promise((resolve) => {
                if (form[field] !== '' && !isIBAN(form[field])) {
                    form.errors[field] = params.message
                        ? params.message
                        : 'Invalid iban.'
                } else {
                    resolve();
                }
            });
        },
        swift (field, params) {
            let testSwift = (swift) => {
                return /^([a-zA-Z]){4}([a-zA-Z]){2}([0-9a-zA-Z]){2}([0-9a-zA-Z]{3})?$/.test(swift)
            }

            return new Promise((resolve) => {
                if (!testSwift(form[field])) {
                    form.errors[field] = params.message
                        ? params.message
                        : 'Invalid BIC/SWIFT.'
                } else {
                    resolve()
                }
            })
        },
        required_if(field, params) {
            return new Promise((resolve) => {
                if (form[field] === '' && form[params.field] !== '') {
                    form.errors[field] = params.message
                        ? params.message
                        : `${field} is required.`
                } else if (form[field] === '') {
                    form.errors[field] = '';
                } else {
                    resolve();
                }
            });
        },
        required_country(field, params){
            return new Promise((resolve) => {
                if (form[field] === '' && form[params.field] === 'PT'){
                    form.errors[field] = params.message
                    ? params.message
                    : `${field} is required.`
                } else if (form[field] === ''){
                    form.errors[field] = '';
                } else {
                    resolve();
                }
            })
        },
        optional(field, params) {
            return new Promise((resolve) => {
                if (form[field] === '') {
                    form.errors[field] = '';
                } else {
                    resolve()
                }
            })
        }
    }
}
