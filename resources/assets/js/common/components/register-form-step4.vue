<template>
    <div class="register-form-step4">
        <register-form-title>Conta - CASINO PORTUGAL</register-form-title>

        <register-form-textbox
                name="username"
                label="* Nome de Utilizador:"
                :form="form"
                placeholder="Nome de Utilizador">
        </register-form-textbox>

        <register-form-textbox
                name="password"
                label="* Palavra Passe:"
                type="password"
                :form="form"
                placeholder="Palavra Passe">
        </register-form-textbox>

        <register-form-textbox
                name="conf_password"
                label="* Repita Palavra Passe:"
                type="password"
                :form="form"
                placeholder="Repita Palavra Passe">
        </register-form-textbox>

        <register-form-captcha
                name="captcha"
                label="* Captcha:"
                :form="form"
                placeholder="Captcha"
                :url="this.captchaUrl"
                @refresh="captchaRefresh()">
        </register-form-captcha>

        <register-form-checkbox
                name="general_conditions"
                :form="form">
            Aceito os <a target="_blank" href="https://www.casinoportugal.pt/info" style="text-decoration: none">termos e condições</a>e dou o meu consentimento para a recolha e tratamento dos meus dados pessoais. Declaro ter no mínimo 18 anos.
        </register-form-checkbox>

        <register-form-submit @submit="onSubmit()" text="REGISTAR" :disabled="submitDisabled"></register-form-submit>
    </div>
</template>

<script>
    import registerFormStepMixin from './mixins/register-form-step';

    export default {
        data() {
            return {
                captchaUrl: '/captcha?_CAPTCHA&refresh=1'
            }
        },
        methods: {
            captchaRefresh () {
                this.captchaUrl = '/captcha?_CAPTCHA&refresh=1&t=' + new Date().getTime();
            }
        },
        mixins: [registerFormStepMixin],
        components: {
            'register-form-title': require('./register-form-title.vue'),
            'register-form-textbox': require('./register-form-textbox.vue'),
            'register-form-captcha': require('./register-form-captcha.vue'),
            'register-form-checkbox': require('./register-form-checkbox.vue'),
            'register-form-submit': require('./register-form-submit.vue')
        }
    }
</script>