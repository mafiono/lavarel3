<template>
    <div class="open-rounds-button" @click="openOpenRounds()" :class="selectedClass" v-if="show">
        <i class="cp-spinner5"></i> <span>Em Aberto</span> <i :class="iconClass"></i>
    </div>
</template>

<script>
    export default {
        methods: {
            openOpenRounds() {
                this.$router.push('/rondas-abertas');
            }
        },
        computed: {
            show() {
                return Store.user.isAuthenticated;
            },
            isSelected() {
                return Store.app.currentRoute === '/rondas-abertas';
            },
            iconClass() {
                return this.isSelected ? 'cp-caret-right' : 'cp-plus';
            },
            selectedClass() {
                return this.isSelected ? 'selected' : '';
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/common/variables';

    .open-rounds-button {
        font-family: "Exo 2", "Open Sans", "Droid Sans", sans-serif;
        background: #1e293e;
        padding: 11px 15px;
        cursor: pointer;
        color: #99ccff;

        > span {
            margin-left: 10px;
        }

        i {
            width: 28px;
        }

        .cp-plus, .cp-caret-right {
            float: right;
            width: auto;
        }

        .cp-plus {
            font-size: 16px;
            line-height: 24px;
        }

        &.selected {
            background: #101928;

            .cp-plus {
                display: none;
            }

            .cp-caret-right {
                display: block;
                float: right;
                font-size: 16px;
                line-height: 24px;
                color: #FFF;
                margin-right: 2px;
            }
        }

        @media (min-width: $mobile-screen-width) {
            &:hover {
                background-color: #FFF;
                color: #1e293e;
            }
        }
    }
</style>
