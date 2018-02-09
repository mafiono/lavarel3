export default {
    data() {
        return {
            formSubmitDisabled: false
        }
    },
    methods: {
        onSubmit() {
            this.formSubmitDisabled = true;

            this.form.validateSome(this.fields);


            this.$nextTick(() => {
                this.formSubmitDisabled = false;

                if (this.form.errors.some(this.fields)) {
                    return;
                }

                this.$emit('submit');
            });
        },
    },
    computed: {
        submitDisabled() {
            return this.formSubmitDisabled || this.disabled;
        }
    },
    props: ['form', 'fields', 'disabled'],
}