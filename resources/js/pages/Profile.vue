<template>=
    <div class="row">
        <div class="col-sm-6 my-3">
            صفحه ی پروفایل
        </div>
        <router-link :to="{name: 'home'}">صفحه ی هوم</router-link>
        <div class="container my-4">
        <div class="row">
            <div class="col-sm-4">
                <div class="container d-flex">
                    <div class="d-grid">
                        <p class="text-muted mb-1">{{ user.number }}</p>
                        <p class="text-muted mb-1">{{ user.email }}</p>
                        <p class="text-muted mb-1">{{ user.name }}</p>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    </div>
    </div>
</template>

<script>

// import Popular from "../layouts/Popular"
// import Newest from "../layouts/Newest"
// import PageHeader from "../layouts/PageHeader"
// import PageFooter from "../layouts/PageFooter"

export default {
    components: {

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
            this.user = response.data.data;
        });
    },
}
</script>