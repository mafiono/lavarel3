<template>
    <div class="register-view">
        <div id="register-container" v-if="showStep2Or3"></div>
        <register></register>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                app: Store.app,
                showStep2Or3: false
            };
        },
        methods: {
            register: function(step) {
                this.showStep2Or3 = true;

                Register.make({params: {step}});
            }
        },
        mounted() {
            this.$watch('app.currentRoute', function (route) {
                let step = route.substr(10);

                if (step === 'step2' || step === 'step3') {
                    this.register(step);

                    return;
                }

                this.showStep2Or3 = false;
            });


            if (router.currentRoute === '/registar/step2') {
                this.register('step2');
            }

            if (router.currentRoute === '/registar/step3') {
                this.register('step3');
            }


            if (Store.user.isAuthenticated) {
                router.replace('/');

                return;
            }
        },
        components: {
            'register': require('../../common/components/register.vue')
        },
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/common/variables';

    .register-view {
        float: left;
        width: 640px;
        overflow: auto;

        @media (max-width: $mobile-screen-width) {
            width: 100%;
        }
    }
</style>