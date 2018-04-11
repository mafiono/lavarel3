<template>
    <div class="register-form-submit">
        <button class="submit" @click.prevent="onSubmit()" :disabled="disabled"> {{ text }} </button>
    </div>
</template>

<script>
  export default {
    methods: {
      onSubmit () {
        this.$emit('submit')
      }
    },
    props: ['text', 'disabled']
  }
</script>

<style lang="scss" scoped>
    @import '../../../sass/global/variables';

    .register-form-submit {
        padding: 25px 15px 15px;

        @media (max-width: $mobile-screen-width) {
            padding: 25px 5px 15px;
        }

        .submit {
            position: relative;
            left: 30%;
            width: 53.7%;
            background-color: #71aa30;
            border: none;
            color: #FFF;
            font-size: 14px;
            font-weight: bold;
            height: 35px;
            line-height: 30px;
            cursor: pointer;

            @media (max-width: $mobile-screen-width) {
                position: static;
                width: 100%;
            }
        }
    }
</style>
