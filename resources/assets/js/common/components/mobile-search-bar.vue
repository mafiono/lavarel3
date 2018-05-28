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
                if (this.context === "casino") {
                    Store.games.$search.next(this.searchText);
                } else {
                    $("#searchForm").children("#textSearch").val(this.searchText).parent().submit();
                }

                this.searchText = "";
            }
        },
        props: ['context']
    }
</script>
