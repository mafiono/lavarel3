<template>
    <div class="register-form-control register-form-checkbox">
        <label class="label" :for="name">&nbsp; {{ label }} </label>
        <div class="control">
            <input class="checkbox" type="checkbox" :name="name" :id="name" v-model="form[name]" />
            <span><span class="text"> {{ text }} <slot></slot> </span></span>
            <span class="error-message" v-if="form.errors.has(name)"> {{ form.errors.get(name) }} </span>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'form',
            'name',
            'label',
            'text',
        ]
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/common/register-form-control';

    .register-form-checkbox {
        @extend .register-form-control;

        .checkbox {
            float: left;
            height: 18px;
            width: 18px;
            vertical-align: middle;
            margin: 3px 3px 31px 0;
        }

        .text {
            display: block;
            padding: 5px;
            font-family: 'Exo 2', 'Open Sans', 'Droid Sans', sans-serif;
            text-align: justify;
            font-size: 12px;
            color: #5A5E64;
            width: 309px;

            @media (max-width: 767px){
                width: 100%;
            }
        }

        .error-message {
            margin-top: 2px;
        }
    }
</style>
