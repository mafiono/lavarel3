<template>
    <div class="register-form-title" :class="css">
        <h3 class="title"><slot></slot></h3>
    </div>
</template>

<script>
    export default {
        props: [
            'title',
            'css'
        ]
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/global/variables';

    .register-form-title {
        .title {
            font-family: $font-exo;
            font-weight: bold;
            font-size: 14px;
            color: #5A5E64;
            border-bottom: solid 1px #f90;
            padding-left: 5px;
            margin-top: 0;
            margin-bottom: 20px;
            background: #FFF;
            height: 30px;
        }
    }
</style>
