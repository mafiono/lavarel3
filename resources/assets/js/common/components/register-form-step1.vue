<template>
    <div class="register-form-step1">
        <register-form-title>Dados Pessoais</register-form-title>

        <register-form-dropdown
                name="gender"
                label="Titulo:"
                :form="form">
            <option value="m">Sr.</option>
            <option value="f">Sr.ª</option>
        </register-form-dropdown>

        <register-form-textbox
                name="firstname"
                label="* Primeiro Nome:"
                :form="form"
                placeholder="Primeiro Nome">

        </register-form-textbox>

        <register-form-textbox
                name="name"
                label="* Apelidos:"
                :form="form"
                placeholder="Apelidos">
        </register-form-textbox>

        <register-form-date
                name="birth_date"
                label="* Data de Nascimento:"
                :form="form"
                dayLabel="Dia"
                monthLabel="Mês"
                yearLabel="Ano"
                :startingYear="startingYear"
                :yearsCount="100">
        </register-form-date>

        <register-form-textbox
                name="email"
                label="* Email:"
                :form="form"
                placeholder="Email">
        </register-form-textbox>

        <register-form-textbox
                name="conf_email"
                label="* Repita Email:"
                :form="form"
                placeholder="Repita Email">
        </register-form-textbox>

        <register-form-submit @submit="onSubmit" text="CONTINUAR 1/4" :disabled="submitDisabled"></register-form-submit>
    </div>

</template>

<script>
    import registerFormStepMixin from './mixins/register-form-step';

    export default {
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
            }
        },
        computed: {
            startingYear() {
                return moment().subtract(18, 'years').year();
            }
        },
        mixins: [registerFormStepMixin],
        components: {
            'register-form-title': require('./register-form-title.vue'),
            'register-form-textbox': require('./register-form-textbox.vue'),
            'register-form-dropdown': require('./register-form-dropdown.vue'),
            'register-form-date': require('./register-form-date.vue'),
            'register-form-submit': require('./register-form-submit.vue')
        }
    }
</script>