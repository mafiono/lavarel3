<template>
    <div class="register-form-step3">
        <register-form-title>Contatos</register-form-title>

        <register-form-dropdown
                name="country"
                label="* País:"
                :form="form"
                :options="countries">
            <option value="" disabled>País</option>
            <option value="PT">Portugal</option>
        </register-form-dropdown>

        <register-form-textbox
                name="address"
                label="* Morada:"
                :form="form"
                placeholder="Morada">
        </register-form-textbox>

        <register-form-textbox
                name="city"
                label="* Cidade:"
                :form="form"
                placeholder="Cidade">
        </register-form-textbox>

        <register-form-dropdown
                name="district"
                label="* Distrito:"
                :form="form"
                :options="districts">
            <option value="" disabled>Distrito</option>
        </register-form-dropdown>

        <register-form-textbox
                name="zip_code"
                label="* Código Postal:"
                :form="form"
                placeholder="Código Postal">
        </register-form-textbox>

        <register-form-textbox
                name="phone"
                label="* Telefone:"
                :form="form"
                placeholder="Telefone">
        </register-form-textbox>

        <register-form-submit @submit="onSubmit" text="CONTINUAR 3/4" :disabled="submitDisabled"></register-form-submit>
    </div>
</template>

<script>
    import countries from '../js/countries';
    import districts from '../js/districts';
    import registerFormStepMixin from './mixins/register-form-step';

    export default {
        data() {
            return {
                countries: [],
                districts,
            }
        },
        mixins: [registerFormStepMixin],
        components: {
            'register-form-title': require('./register-form-title.vue'),
            'register-form-textbox': require('./register-form-textbox.vue'),
            'register-form-dropdown': require('./register-form-dropdown.vue'),
            'register-form-submit': require('./register-form-submit.vue')
        },
        mounted: function () {
            console.log('Mounted!!!');
            countries.get().then(x => this.countries = x);
        }
    }
</script>