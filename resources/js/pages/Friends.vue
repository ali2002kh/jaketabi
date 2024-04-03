<template>
    <page-header></page-header>
    <div class="row">
        <div class="col-sm-6 my-3">
            صفحه ی دوستان
        </div>
        <div class="container my-4">
            <div class="row">
                <div class="col-sm-4">
                    <div class="container d-flex">
                        <div class="d-grid">
                            <p v-for="f in user.friends" :key="f.id" class="text-muted mb-1"> 
                                {{ f.username }}
                            </p>
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
            user: null,
        }
    },
    created() {
        axios.get('/api/user')
        .then(response => {
            console.log(response.data.data)
            this.user = response.data.data
        })
    },  
}
</script>