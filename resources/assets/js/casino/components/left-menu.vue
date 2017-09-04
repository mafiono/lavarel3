<template>
    <div class="left-menu">
        <!--<div class="tabs">-->
            <!--<a class="tab" :class="{'selected': selected('CASINO')}" @click="select('CASINO')">CASINO</a>-->
            <!--<a class="tab" :class="{'selected': selected('PROMOS')}" @click="select('PROMOS')">PROMOS</a>-->
        <!--</div>-->
        <promotions-button context="casino"></promotions-button>
        <transition>
            <categories-menu v-show="selected('CASINO')"></categories-menu>
        </transition>
    </div>
</template>


<script>
    export default {

        data: function() {
            return {
                tab: 'CASINO'
            };
        },
        methods: {
            select: function(tab) {
                this.tab = tab;
            },
            selected: function(tab) {
                return this.tab === tab;
            }
        },

        components: {
            'categories-menu': require('./categories-menu.vue'),
            'promotions-button': require('../../sports/components/promotions-button.vue')
        }
    }
</script>
