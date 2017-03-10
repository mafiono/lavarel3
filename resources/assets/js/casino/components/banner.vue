<template>
    <div class="banner">
        <a :href="href">
            <img :src="image" :alt="alt">
        </a>
    </div>
</template>
<script>
    export default{
        props: [
            'image',
            'href',
            'alt'
        ],
    }
</script>
