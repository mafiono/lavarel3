<template>
    <div class="register-form-textbox">
        <label class="label" :for="name"> {{ label }} </label>
        <div class="control">
            <input class="textbox" :name="name" :id="name" :type="inputType" v-model="form[name]" :placeholder="placeholder">
            <i class="error-icon cp-exclamation-circle" v-if="form.errors.has(name)"></i>
            <span class="error-message" v-if="form.errors.has(name)"> {{ form.errors.get(name) }} </span>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            inputType () {
                return this.type === undefined ? 'text' : this.type
            }
        },
        props: [
            'form',
            'label',
            'name',
            'type',
            'placeholder',
        ]
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/common/variables';
    @import '../../../sass/common/mixins';
    @import '../../../sass/common/register-form-control';

    .register-form-textbox {
        @extend .register-form-control;

        .textbox {
            color: #5A5E64;
            padding: 5px 10px;
            font-family: 'Open Sans', 'Droid Sans', sans-serif;
            font-size: 13px;
            width: 75%;
            height: 30px;
            border-radius: 0 !important;
            background-color: #EBEEF3;
            border: 1px solid #B9C4D3;

            @include placeholder(#EBEEF3);

            &:focus {
                border-color: #536883;
            }

            @media (max-width: $mobile-screen-width) {
                width: 100%;

                @include placeholder(#5A5E64);
            }
        }
    }
</style>
