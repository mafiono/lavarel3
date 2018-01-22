<template>
    <div class="register-form">
        <register-form-step1 :form="form" :fields="stepFields[0]" v-if="step === 1" @submit="onSubmit"></register-form-step1>
        <register-form-step2 :form="form" :fields="stepFields[1]" v-if="step === 2" @submit="onSubmit"></register-form-step2>
        <register-form-step3 :form="form" :fields="stepFields[2]" v-if="step === 3" @submit="onSubmit"></register-form-step3>
        <register-form-step4 :form="form" :fields="stepFields[3]" v-if="step === 4" @submit="onSubmit" :disabled="submitDisabled"></register-form-step4>
    </div>
</template>

<script>
    import Form from '../js/Form';
    import registerRules from '../js/registerRules';
    import registerFormData from '../js/registerFormData';

    export default {
        data () {
            return {
                form: new Form(registerFormData, registerRules),
                stepFields: [
                    ['firstname', 'name', 'birth_date', 'email', 'conf_email'],
                    ['nationality', 'document_number', 'sitprofession', 'tax_number', 'bank_name', 'bank_iban'],
                    ['country', 'address', 'city', 'district', 'zip_code', 'phone'],
                    ['username', 'password', 'conf_password', 'captcha', 'general_conditions']
                ],
                step: 1,
                submitDisabled: false
            }
        },
        methods: {
            onSubmit () {
                if (this.step < 4) {
                    this.step++;

                    return;
                }

                this.submitDisabled = true;

                this.submitForm();
            },
            submitForm() {
                this.showWaitPopup();

                this.form.submit('post', '/ajax-register/step1')
                    .done((data) => {
                        if (data.type === 'redirect') {
                            page(data.redirect);
                        } else {
                            Object.keys(data).forEach(key => {
                                if (this.form.errors[key] !== undefined) {
                                    this.form.errors[key] = data[key][0];
                                }
                            });

                            this.gotoErroneousStep();
                        }
                    })
                    .fail((error) => {
                        if (error.type === 'redirect') {
                            page(error.redirect);
                        } else if (error.type === 'abort') {
                            page('/');
                        }
                    })
                    .always((response) => {
                        if (response.status) {
                            this.gaSubmit(response.status);
                        }

                        swal.close();

                        this.submitDisabled = false;
                    })
            },
            showWaitPopup() {
                $.fn.popup({
                    title: 'Aguarde por favor!',
                    type: 'warning',
                    showCancelButton: false,
                    showConfirmButton: false
                });
            },
            gaSubmit(status) {
                ga('send', {
                    hitType: 'event',
                    eventCategory: 'register',
                    eventAction: 'step1-submit-' + status,
                    eventLabel: 'Step 1 Submit ' + status
                });
            },
            gotoErroneousStep() {
                for (let i=0; i<4; i++) {
                    if (this.stepHasError(i)) {
                        this.step = i +1;
                        return;
                    }
                }
            },
            stepHasError(step) {
                let stepFields = this.stepFields[step];

                for (let i=0; i<stepFields.length; i++) {
                    if (this.form.errors[stepFields[i]] !== '') {
                        return true;
                    }
                }

                return false;
            }
        },
        components: {
            'register-form-step1': require('./register-form-step1.vue'),
            'register-form-step2': require('./register-form-step2.vue'),
            'register-form-step3': require('./register-form-step3.vue'),
            'register-form-step4': require('./register-form-step4.vue'),
        },
        mounted () {
            Object.keys(this.form.defaultData).forEach(name => {
                this.$watch(`form.${name}`, () => {
                    this.form.validate(name);
                })
            })
        }
    }
</script>

<style lang="scss">
    @import '../../../sass/global/variables';

    .register-form {
        padding: 16px;
    }
</style>
