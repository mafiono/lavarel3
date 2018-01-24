export default {
    nationalities: null,
    get() {
        return new Promise((resolve) => {
            if (this.nationalities !== null)
                return resolve(this.nationalities);
            $.post('/ajax-register/nationalities').done(data => {
                this.nationalities = data;
                resolve(this.nationalities);
            });
        });
    }
};