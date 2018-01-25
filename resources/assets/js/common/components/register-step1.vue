<template>
  <form class="register-step1">
      <register-form-step1 v-if="step === 1"></register-form-step1>
      <register-form-step2 v-if="step === 2"></register-form-step2>
      <register-form-step3 v-if="step === 3"></register-form-step3>
      <register-form-step4 v-if="step === 4"></register-form-step4>
      <div class="next">
          <button @click.prevent="nextStep()" v-if="step < 5">{{ submitText }}</button>
      </div>
  </form>
</template>

<script>
export default {
    data() {
        return {
            step: 1
        }
    },
    filters: {
        padZero(value, n) {
            return Math.pow(10, n) > value ? ((Math.pow(10, n) + value) + "").substr(1) : value;
        }
    },
    methods: {
        nextStep() {
            this.step++;
        }
    },
    computed: {
        submitText() {
            return this.step < 4 ? `CONTINUAR ${this.step}/4` : 'INSCREVER-SE';
        }
    },
    components: {
        'register-form-step1': require('./register-form-step1.vue'),
        'register-form-step2': require('./register-form-step2.vue'),
        'register-form-step3': require('./register-form-step3.vue'),
        'register-form-step4': require('./register-form-step4.vue'),
        'register-form-captcha': require('./register-form-captcha.vue'),
    }
}
</script>

<style lang="scss">
    @import '../../../sass/common/variables';
    @import '../../../sass/common/mixins';

    .register-step1 {
        .title {
            font-family: 'Exo 2','Open Sans','Droid Sans',sans-serif;
            font-weight: 700;
            font-size: 14px;
            color: #5A5E64;
            border-bottom: solid 1px #ff9900;
            padding-bottom: 5px;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .input-row {
            overflow: auto;

            label {
                float: left;
                width: 30%;
                font-family: 'Exo 2','Open Sans','Droid Sans',sans-serif;
                color: #5A5E64;
                font-size: 14px;
                line-height: 40px;
                padding-right: 5px;
                text-align: right;
            }

            .field {
                float: left;
                width: 70%;
                padding: 5px;

                input[type=text], select {
                    color: #5A5E64;
                    padding: 5px 10px;
                    font-size: 13px;
                    width: 75%;
                    height: 30px;
                    border-radius: 0 !important;
                    background-color: #EBEEF3 !important;
                    border: 1px solid #B9C4D3 !important;

                    @include placeholder(#EBEEF3);

                    &:focus {
                        border-color: #536883 !important;
                    }
                }

                &.gender, &.birth-date {
                    select {
                        width: 24.3%;
                    }
                }
            }
        }

        .next {
            padding: 25px 15px 15px;

            button {
                position: relative;
                left: 30%;
                width: 53.7%;
                background-color: #71aa30;
                border: none;
                color: #ffffff;
                font-size: 14px;
                font-weight: bold;
                height: 35px;
                line-height: 30px;
            }
        }
    }

    @media (max-width: $mobile-screen-width) {
        .register-step1 {
            .input-row {
                padding: 0 10px;
                label {
                    display: none;
                }
                label[for="birth_date"] {
                    line-height: 20px;
                    text-align: left;
                    display: block;
                    width: 100%;
                    padding-left: 5px;
                    margin-bottom: -7px;
                    font-size: 12px;

                    b, span {
                        display: none;
                    }
                }

                .field {
                    width: 100%;

                    input[type=text] {
                        width: 100%;

                        @include placeholder(#5A5E64);
                    }

                    select {
                        float: left;
                    }

                    &.gender, &.birth-date {
                        select {
                            width: 32%;
                        }

                        select:not(:first-child) {
                            margin-left: 2%;
                        }
                    }
                }
            }

            .next {
                button {
                    position: static;
                    width: 100%;
                }
            }
        }
    }
</style>
