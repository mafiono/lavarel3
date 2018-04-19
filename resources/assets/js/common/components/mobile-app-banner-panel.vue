<template>
    <transition appear name="slide">
    <div class="mobile-app-banner-panel">
        <ul class="panel-content">
            <li class="close-box">
                <a href="javascript:void(0);" class="close" @click="close()">&times;</a>
            </li>
            <li class="icon-box">
                <img class="icon" src="/assets/portal/img/icon-app.png" alt="Casino Portugal"/>
            </li>
            <li class="info-box">
                <ul class="info-content">
                    <li class="info-title">Casino Portugal</li>
                    <li class="info-company">Apostas e Jogo Online</li>
                    <li class="info-availability">Grátis - {{storeName}}</li>
                </ul>
            </li>
            <li class="open-box">
                <a :href="appUrl" class="open" target="_blank" @click="open()">Obter</a>
            </li>
        </ul>
    </div>
    </transition>
</template>

<script>
    export default {
        name: "mobile-app-banner-panel",
        methods: {
            close() {
                this.$emit('close');
            },
            open() {
                this.$emit('open');
            }
        },
        props: ['appUrl', 'storeName']
    }
</script>

<style lang="scss">
    .mobile-app-banner-panel {
        display: none;
    }

    @media (max-width: 767px) {
        .mobile-app-banner-panel {
            display: block;
            color: #4d4d4d;
            text-shadow: 0 1px 0 white;
            height: 80px;
            overflow: hidden;
            z-index: 9998;

            ul {
                margin: 0;
                padding: 0;
            }

            .panel-content {
                z-index: 1;
                position: fixed;
                left: 0;
                top: 0;
                width: 100%;
                height: 80px;
                font-family: 'Helvetica Neue', sans-serif;

                -webkit-font-smoothing: antialiased;
                overflow: hidden;
                -webkit-text-size-adjust: none;
                list-style-type: none;
                font-size: 0;
                background: #f4f4f4;
                background: linear-gradient(#f4f4f4, #FFF, #cdcdcd);
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);

                li {
                    display: inline-block;
                    /*border: 1px solid black;*/
                    font-size: 15px;
                    height: 80px;
                    vertical-align: top;
                    text-align: center;
                }

                .close-box {
                    width: 30px;
                    padding-top: 25px;

                    .close {
                        display: inline-block;
                        font-size: 18px;
                        width: 30px;
                        height: 30px;
                        line-height: 30px;
                        color: black;
                        border-radius: 14px;
                    }
                }

                .icon-box {
                    width: 60px;
                    padding-top: 10px;

                    .icon {
                        display: inline-block;
                        width: 60px;
                        height: 60px;
                        background: rgba(0, 0, 0, 0.6);
                        background-size: cover;
                        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
                        border-radius: 10px;
                    }
                }

                .info-box {
                    width: calc(100% - 150px);
                    padding: 10px 0 0 10px;
                    overflow: hidden;

                    .info-content {
                        list-style-type: none;

                        li {
                            display: block;
                            text-align: left;
                            white-space: nowrap;
                        }

                        .info-title {
                            font-weight: bold;
                            font-size: 15px;
                            height: 24px;
                            line-height: 24px;
                        }

                        .info-company {
                            font-weight: bold;
                            font-size: 12px;
                            height: 18px;
                            line-height: 18px;
                            color: #6a6a6a;
                        }

                        .info-availability {
                            font-weight: bold;
                            font-size: 12px;
                            height: 18px;
                            line-height: 18px;
                            color: #6a6a6a;
                        }

                    }
                }

                .open-box {
                    width: 60px;
                    padding: 25px 0 0 4px;
                    text-align: left;

                    .open {
                        display: block;
                        line-height: 30px;
                        color: #4a7fb3;
                        text-shadow: 0 1px 0 #e3e3ea;
                    }
                }

            }

            @keyframes slideDown {
                from {
                    transform: translateY(-100%);
                }
                to {
                    transform: translateY(0);
                }
            }

            @keyframes slideUp {
                from {
                    transform: translateY(0);
                }
                to {
                    transform: translateY(-100%);
                }
            }

            &.slide-enter {
                height: 0;
            }

            &.slide-enter-active {
                animation: slideDown .3s ease-in;
                transition: height .3s ease-in;
            }

            &.slide-leave-active {
                transition: height .3s ease-out;
                animation: slideUp .3s ease-out;
                height: 0;
            }
        }
    }
</style>