<template>
    <div class="register-form-captcha">
        <label class="label" :for="name"> {{ label }} </label>
        <div class="control">
            <div class="captcha">
                <img class="image" :src="url" alt="captcha">
                <i class="refresh-icon cp-refresh" @click.prevent="onRefresh()"></i>
                <input class="textbox" type="text" :name="name" :id="name" v-model="form[name]" :placeholder="placeholder" autocomplete="off"/>
            </div>
            <i class="error-icon cp-exclamation-circle" v-if="form.errors.has(name)"></i>
            <span class="error-message" v-if="form.errors.has(name)"> {{ form.errors.get(name) }} </span>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            onRefresh () {
                this.$emit('refresh');
            }
        },
        props: [
            'form',
            'label',
            'name',
            'rules',
            'placeholder',
            'url'
        ]
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/common/mixins';
    @import '../../../sass/common/variables';
    @import '../../../sass/common/register-form-control';

    .register-form-captcha {
        @extend .register-form-control;

        .label {
            line-height: 50px;
        }

        .captcha {
            display: inline-block;
            width: 75%;
            border: 1px solid #B9C4D3;
            box-sizing: border-box;
            background-color: #FFF;
            padding: 0;
            vertical-align: middle;

            @media (max-width: $mobile-screen-width) {
                width: 100%;
            }

            .image {
                float: left;
                width: 50%;
                height: 40px;
            }

            .refresh-icon {
                float: left;
                border: 0;
                background: none;
                height: 40px;
                width: 10%;
                color: #5A5E64;
                text-align: center;
                line-height: 40px;
                cursor: pointer;

                &:active {
                    border: 1px solid #536883;
                }
            }

            .textbox {
                float: left;
                width: 40%;
                height: 40px;
                padding: 5px 10px;
                font-family: 'Open Sans', 'Droid Sans', sans-serif;
                font-size: 14px;
                border: 0;
                background: none;
                border: none;
                border-radius: 0 !important;
                color: #5A5E64;

                @include placeholder(#cbcbcb);

                &:focus {
                    border: 1px solid #536883;
                }
            }
        }
    }
</style>
