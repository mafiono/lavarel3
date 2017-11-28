<template>
    <div class="register-form-step2">
        <register-form-title>Informação Legal</register-form-title>

        <register-form-dropdown
                name="nationality"
                label="* Nacionalidade:"
                :form="form"
                :options="nationalities">
            <option value="" disabled>Nacionalidade</option>
            <option value="PT">Português</option>
        </register-form-dropdown>

        <register-form-textbox
                name="document_number"
                label="* Identificação Civil:"
                :form="form"
                placeholder="Identificação Civil">
        </register-form-textbox>

        <register-form-dropdown
                name="sitprofession"
                label="* Ocupação:"
                :form="form">
            <option value="" disabled>Ocupação</option>
            <option value="11">Trabalhador por conta própria</option>
            <option value="22">Trabalhador por conta de outrem</option>
            <option value="33">Profissional liberal</option>
            <option value="44">Estudante</option>
            <option value="55">Reformado</option>
            <option value="66">Estagiário</option>
            <option value="77">Sem atividade profissional</option>
            <option value="88">Desempregado</option>
        </register-form-dropdown>

        <register-form-textbox
                name="tax_number"
                label="* Número Fiscal:"
                :form="form"
                placeholder="Número Fiscal">
        </register-form-textbox>

        <register-form-textbox
                name="bank_name"
                label="Nome do Banco:"
                :form="form"
                placeholder="Nome do Banco">
        </register-form-textbox>

        <register-form-textbox
                name="bank_bic"
                label="BIC/SWIFT:"
                :form="form"
                placeholder="BIC/SWIFT">
        </register-form-textbox>

        <register-form-textbox
                name="bank_iban"
                label="IBAN:"
                :form="form"
                placeholder="IBAN">
        </register-form-textbox>

        <register-form-submit @submit="onSubmit" text="CONTINUAR 2/4" :disabled="submitDisabled"></register-form-submit>
    </div>
</template>

<script>
    import nationalities from './../js/nationalities';
    import registerFormStepMixin from './mixins/register-form-step';

    export default {
        data() {
            return {
                nationalities
            }
        },
        mixins: [registerFormStepMixin],
        components: {
            'register-form-title': require('./register-form-title.vue'),
            'register-form-textbox': require('./register-form-textbox.vue'),
            'register-form-dropdown': require('./register-form-dropdown.vue'),
            'register-form-submit': require('./register-form-submit.vue')
        }
    }
</script>