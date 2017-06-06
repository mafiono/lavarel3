<template>
    <div class="mobile-search-bar">
        <form @submit.prevent="submitSearch">
            <input v-model="searchText" placeholder="Pesquisar">
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                searchText: ""
            }
        },
        methods: {
            submitSearch() {
                $("#searchForm").children("#textSearch").val(this.searchText).parent().submit();

                this.searchText = "";
            }
        },
    }
</script>
