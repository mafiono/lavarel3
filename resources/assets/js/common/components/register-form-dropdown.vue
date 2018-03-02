<template>
    <div class="register-form-dropdown">
        <label class="label" :for="name"> {{ label }} </label>
        <div class="control">
            <select class="dropdown" :name="name" :id="name" v-model="form[name]" @change="onChange()">
                <slot></slot>
                <option v-for="(label, value) in options" :value="value"> {{ label }}</option>
            </select>
            <i class="error-icon cp-exclamation-circle" v-if="form.errors.has(name)"></i>
            <span class="error-message" v-if="form.errors.has(name)"> {{ form.errors.get(name) }} </span>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            onChange() {
                this.$emit('change', this.form[name]);
            }
        },
        props: [
            'form',
            'options',
            'label',
            'name',
        ]
    }
</script>

<style lang="scss">
    @import '../../../sass/common/variables';
    @import '../../../sass/common/register-form-control';

    .register-form-dropdown {
        @extend .register-form-control;

        .dropdown {
            color: #5A5E64;
            padding: 5px 10px;
            font-family: 'Open Sans', 'Droid Sans', sans-serif;
            font-size: 13px;
            width: 75%;
            height: 30px;
            border-radius: 0 !important;
            background-color: #EBEEF3;
            border: 1px solid #B9C4D3;

            &:focus {
                border-color: #536883;
            }

            @media (max-width: $mobile-screen-width) {
                width: 100%;
            }
        }
    }
</style>