function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

export default {
    gender: 'm',
    firstname: '',
    name: '',
    birth_date: '',
    email: '',
    conf_email: '',
    nationality: '',
    document_number: '',
    sitprofession: '',
    tax_number: '',
    bank_name: '',
    bank_bic: '',
    bank_iban: '',
    country: '',
    address: '',
    city: '',
    district: '',
    zip_code: '',
    phone: '',
    username: '',
    password: '',
    conf_password: '',
    promo_code: getCookie('btag'),
    captcha: '',
    general_conditions: false
}
