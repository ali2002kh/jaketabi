<template>
    <div>
        {{ shelf.name }}
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
            shelf: null,
        }
    },
    created() {
        axios.get(`/api/shelf/${this.$route.params.id}`)
        .then(response => {
            console.log(response.data.data)
            this.shelf = response.data.data
        })
    },  
}
</script>