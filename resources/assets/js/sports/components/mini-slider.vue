<template>
    <div class="mini-slider">
        <div class="slides">
            <div class="slider">
                <div class="images">
                    <a href="#" @click.prevent="open('/casino')">
                        <img src="/assets/portal/img/mini-slides/slide1.jpg">
                    </a>
                </div>
            </div>
            <div class="slider">
                <div class="images">
                    <a href="#" @click.prevent="open('/casino')">
                        <img src="/assets/portal/img/mini-slides/slide2.jpg">
                    </a>
                </div>
            </div>
            <div class="slider">
                <div class="images">
                    <a href="#" @click.prevent="open('/casino')">
                        <img src="/assets/portal/img/mini-slides/slide3.jpg">
                    </a>
                </div>
            </div>
            <div class="slider">
                <div class="images">
                    <a href="#" @click.prevent="open('/casino')">
                        <img src="/assets/portal/img/mini-slides/slide2.jpg">
                    </a>
                </div>
            </div>
            <div class="switch">
                <ul>
                    <li>
                        <div class="on"></div>
                    </li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            open: function (url) {
                page(url);
            }
        }
    }
</script>