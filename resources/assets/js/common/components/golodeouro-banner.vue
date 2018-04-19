<template>
    <div class="bs-wp" v-if="image">
        <div id="stats" class="stats bs-wp hidden-xs" style="height:800px;">
            <img :src="image" style="width: 100%;">
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                activeImage: Store.golodeouro,
            }
        },
        computed: {
            image() {
                let img = this.activeImage.image;
                if (img !== null) {
                    return '/assets/portal/img/banners/' + img;
                }
                return null;
            }
        }
    }
</script>