<template>
    <div class="bs-wp">
        <div id="stats" class="stats bs-wp hidden-xs" style="height:800px;">
            <img v-if="image" :src="image" style="width: 100%;">
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                golos: [],
            }
        },
        methods:{
            fetchgolo(){
                $.getJSON('/api/active')
                    .done(data => {
                        this.golos.push(data.data);
                    });
            },
        },
        computed: {
            golo() {
                if (this.golos !== null && this.golos.length)
                    return this.golos[0];
                return null;
            },
            details() {
                if (this.golo !== null && this.golo.details)
                    return JSON.parse(this.golo.details);
                return {
                    image: null,
                };
            },
            image() {
                let img = this.details.image;
                if (img !== null) {
                    return '/assets/portal/img/banners/' + img;
                }
                return null;
            }
        },
        mounted(){
            this.fetchgolo();
        }
    }
</script>