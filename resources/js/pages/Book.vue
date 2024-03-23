<template>
    <div>
        {{ book.name }}
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