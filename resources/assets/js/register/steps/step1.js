import {Observable} from 'rxjs/Observable';
import 'rxjs/add/observable/interval';
import 'rxjs/add/operator/filter';

$.validator.addMethod("multidate", function (value, element, params) {
    let daySelector = params[0]
        , monthSelector = params[1]
        , yearSelector = params[2]
        , day = parseInt($(daySelector).val(), 10)
        , month = parseInt($(monthSelector).val(), 10) - 1
        , year = parseInt($(yearSelector).val(), 10)
        , date = moment([year, month, day])
        , now = moment();

    return date.isValid() && now.diff(date, 'years') >= 18;
});
$.validator.addMethod("trim", function (value, element, params) {
    if (value.indexOf('  ') >= 0) {
        value = value.replace(/\s\s+/g, ' ');
        $(element).val(value);
    }
    return value === '' || value.trim() === value;
});

let sub = null;
module.exports.load = function () {
    // validate signup form on keyup and submit
    let dateFields = ['#age_day','#age_month','#age_year',];
    $("#saveForm").validate({
        customProcessStatus: function (status, response) {
            ga('send', {
                hitType: 'event',
                eventCategory: 'register',
                eventAction: 'step1-submit-' + status,
                eventLabel: 'Step 1 Submit ' + status
            });
            if (!response.token) {
                $('#captcha').val('');
            }
            refreshCaptcha();
            return false;
        },
        customPopupClose: function (args) {
            ga('send', {
                hitType: 'event',
                eventCategory: 'register',
                eventAction: 'step1-submit-popup-ok',
                eventLabel: 'Step 1 Submit Popup OK'
            });
            return false;
        },
        groups: {
            date: dateFields.join("")
        },
        rules: {
            age_day: {
                multidate: dateFields,
            },
            age_month: {
                multidate: dateFields,
            },
            age_year: {
                multidate: dateFields,
            },
            gender: {
                required: true,
            },
            firstname: {
                required: true,
                trim: true,
            },
            name: {
                required: true,
                trim: true,
            },
            nationality:  {
                required: true
            },
            document_number: {
                required: true,
                minlength: 6,
                maxlength: 13,
                trim: true,
                remote: {
                    url: '/api/check-users',
                    type: 'post'
                }
            },
            tax_number: {
                required: true,
                minlength: 9,
                maxlength: 9,
                digits: true,
                remote: {
                    url: '/api/check-users',
                    type: 'post'
                }
            },
            country: {
                required: true
            },
            sitprofession: {
                required: true
            },
            address: {
                required: true,
                trim: true,
                minlength: 10,
                maxlength: 150,
            },
            city: {
                required: true,
                trim: true,
                minlength: 4,
                maxlength: 100,
            },
            zip_code: {
                required: true,
                pattern: /^[0-9]{4}-[0-9]{3}$/
            },
            email: {
                required: true,
                email: true,
                maxlength: 100,
                remote: {
                    url: '/api/check-users',
                    type: 'post'
                }
            },
            conf_email: {
                required: true,
                email: true,
                equalTo: "#email"
            }, 
            phone: {
                required: true,
                minlength:6,
                maxlength:15,
                pattern: /^\+[0-9]{2,3}\s*[0-9]{6,11}$/
            },
            username: {
                required: true,
                minlength: 4,
                maxlength: 45,
                pattern: /^(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?![_.])$/,
                remote: {
                    url: '/api/check-users',
                    type: 'post'
                }
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 20,
                pattern: /^(?=.*[A-Z])(?=.*[0-9])(?=.*[a-z]).{8,20}$/
            },
            conf_password: {
                required: true,
                equalTo: "#password"
            },
            security_pin: {
                required: true,
                pattern: /^[0-9]{4}$/
            },
            general_conditions: "required",
            bank_name: {
                trim: true,
                required: {
                    depends: el => $('#bank_iban').is(":filled")
                }
            },
            bank_bic: {
                trim: true,
                required: {
                    depends: el => $('#bank_iban').is(":filled")
                }
            },
            bank_iban: {
                trim: true,
                iban: true,
            },
            promo_code: {
                pattern: /^(\d{3,19})(([_\-.])(\d{1,20})([a-zA-Z0-9\.\-_;,–$@]*)?)?$/,
                maxlength: 40,
            },
            friend_code: {
                pattern: /^[A-Z]{5}$/
            },
            captcha: {
                required: true,
                minlength: 5,
                maxlength: 5,
            }
        },
        messages: {
            age_day: {
                multidate: 'Por favor, verifique os dados',
            },
            age_month: {
                multidate: 'Por favor, verifique os dados',
            },
            age_year: {
                multidate: 'Por favor, verifique os dados',
            },
            gender: " ",
            firstname: {
                required: "Por favor, verifique os dados",
                trim: "Por favor, verifique os dados",
            },
            name: {
                required: "Por favor, verifique os dados",
                trim: "Por favor, verifique os dados",
            },
            nationality: "Por favor, verifique os dados",
            country: "Por favor, verifique os dados",
            document_number: {
                required: "Por favor, verifique os dados",
                trim: "Por favor, verifique os dados",
                minlength: "Mínimo 6 caracteres",
                maxlength: "Máximo 13 caracteres",
            },
            tax_number: {
                required: "Por favor, verifique os dados",
                minlength: "Número incorrecto",
                maxlength: "Número incorrecto",
                digits: "Apenas digitos"
            },
            sitprofession: {
                required: "Por favor, verifique os dados"
            },
            address: {
                required: "Por favor, verifique os dados",
                trim: "Por favor, verifique os dados",
                minlength: "Mínimo 10 caracteres",
                maxlength: 'Máximo de 150 caracteres',
            },
            city: {
                required: "Por favor, verifique os dados",
                trim: "Por favor, verifique os dados",
                minlength: "Mínimo 4 caracteres",
                maxlength: 'Máximo de 100 caracteres',
            },
            zip_code: {
                required: "Por favor, verifique os dados",
                pattern: "Formato XXXX-XXX"
            },
            email: {
                required: "Por favor, verifique os dados",
                email: "Insira um email válido",
                maxlength: "Máximo de 100 caracteres",
            },
            conf_email: {
                required: "Por favor, verifique os dados",
                email: "Insira um email válido",
                equalTo: "Confirme o seu email"
            },
            phone: {
                required: "Por favor, verifique os dados",
                maxlength: "Minimo de 6 números",
                minlength: "Maximo de 14 numeros",
                pattern:"Formato +351 xxxxxxxxx"
            },
            username: {
                required: "Por favor, verifique os dados",
                minlength: "Minimo 4 caracteres",
                pattern: "Formato inválido",
            },
            password: {
                required: "Por favor, verifique os dados",
                minlength: "Minimo 8 caracteres",
                maxlength: "Máximo 20 caracteres",
                pattern: "1 maiúscula, 1 minúscula e 1 numero"
            },
            conf_password: {
                required: "Por favor, verifique os dados",
                equalTo: "Tem de ser igual à sua password"
            },
            security_pin: {
                required: "Por favor, verifique os dados",
                pattern: "4 numeros"
            },
            general_conditions: " ",
            bank_name: {
                trim: 'Por favor, verifique os dados',
                required: 'Por favor, verifique os dados',
            },
            bank_bic: {
                trim: 'Verifique os dados',
                required: 'Verifique os dados',
            },
            bank_iban: {
                trim: 'Por favor, verifique os dados',
                iban: 'Introduza um IBAN válido'
            },
            promo_code: {
                pattern: 'Código Inválido!',
                maxlength: 'Código Inválido!',
            },
            friend_code: {
                pattern: 'Código Inválido!',
            },
            captcha: {
                required: 'Introduza o código do captcha',
                minlength: 'Introduza o código do captcha',
                maxlength: 'Introduza o código do captcha',
            }
        }
    });

    let fields = $('.birth-date select, #firstname, #name, #document_number');
    sub = Observable.interval(1000)
        .map(() => {
            return {
                fullname: $('#firstname').val() + ' ' + $('#name').val(),
                document_number: $('#document_number').val() || '',
                birth_date: moment([parseInt($('#age_year').val()||0, 10),
                    parseInt($('#age_month').val() - 1||0, 10),
                    parseInt($('#age_day').val()||0, 10)])
            }
        })
        .distinctUntilChanged((a,b) => { return JSON.stringify(a) === JSON.stringify(b);})
        .filter(x => {
            // console.log('filtering', x);
            return x.fullname.length > 3 &&
                x.document_number.length >= 6 && x.document_number.length <= 13 &&
                x.birth_date.isValid() && moment().diff(x.birth_date, 'years') >= 18;
        })
        .map(x => {
            x.birth_date = x.birth_date.format('YYYY-MM-DD');
            return x;
        })
        // .filter(() => fields.valid()) // this display errors, so we will send this task to the server
        .distinctUntilChanged((a,b) => { return JSON.stringify(a) === JSON.stringify(b);})
        .subscribe(data => {
            // console.log('Checking', data);
            $.ajax({
                url: '/api/check-identity',
                method: 'post',
                data: data
            });
        });

    function refreshCaptcha() {
        var url = '/captcha?_CAPTCHA&refresh=1&t=' + new Date().getTime();
        $('#captcha-img').attr('src', url);
    }

    $('#captcha-refresh').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        refreshCaptcha();
    });

    $('#register-close').click(function(){
        page("/");
    });

    $('#limpar').click(function(){
        $('#saveForm')[0].reset();
    });
};
module.exports.unload = function () {
    if (sub !== null) {
        sub.unsubscribe();
        sub = null;
    }
};