<template>
    <transition name="vue-fade-in">
        <div class="mobile-up-button" @click="scrollUp()" v-if="show">
            <i class="cp-chevron-up"></i>
        </div>
    </transition>
</template>

<script>
    export default {
        data() {
            return {
                top: 0,
            }
        },
        methods: {
            scrollUp() {
                $(window).scrollTop(0);
            }
        },
        computed: {
            show() {
                return this.top > 100;
            }
        },
        mounted() {
            window.addEventListener('scroll', () => {
                    this.top = $(window).scrollTop();
                }
            );
        }
    }
</script>