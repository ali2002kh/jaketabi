<template>
    <page-header></page-header>
    <div class="row">
        <div class="col-sm-6 my-3">
            صفحه ی کتاب
        </div>
        <div class="container my-4">
            <div class="row">
                <div class="col-sm-4">
                    <div class="container d-flex">
                        <div class="d-grid">
                            <p class="text-muted mb-1">{{ book.name }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        </div>
    </div>
</template>

<script>

import PageHeader from "../layouts/PageHeader"
// import PageFooter from "../layouts/PageFooter"

export default {
    components: {
        PageHeader,
        // PageFooter,
    },
    data() {
        return {
            book: null,
        }
    },
    created() {
        axios.get(`/api/book/${this.$route.params.id}`)
        .then(response => {
            console.log(response.data.data)
            this.book = response.data.data
        })
    },  
}
</script>