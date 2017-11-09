<template>
    <div class="open-rounds-button">
        <i :class="iconClass"></i>
        <span> &nbsp; {{title}}</span>
        <i :class="iconClass"></i>
    </div>
</template>

<script>
    export default {
        name: 'left-button',
        props: [
            'iconClass',
            'title',
        ]
    }
</script>