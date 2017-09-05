<template>
    <div class="banner">
        <router-link :to="href">
            <img :src="image" :alt="alt">
        </router-link>
    </div>
</template>
<script>
    export default{
        props: [
            'image',
            'href',
            'alt',
        ],
    }
</script>
