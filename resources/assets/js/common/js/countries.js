export default {
    countries: null,
    get() {
        return new Promise((resolve) => {
            if (this.countries !== null)
                return resolve(this.countries);
            $.post('/ajax-register/countries').done(data => {
                this.countries = data;
                resolve(this.countries);
            });
        });
    }
};