<template>
    <page-header :user="user"></page-header>
    <div class="container-fluid" style="margin-top: 110px;">
        <p class="title text-end mx-4 fs-4 fw-bold"> {{ records[0].status }} </p>
        <hr class="opacity-100 border border-muted">
    </div>
    <div class="body h-100 mb-5 row flex-row-reverse mx-2 p-1 rounded-1" style="background-color: #f4f4f4;">
        <div v-for="r in records" :key="r.id"   class="col-md-2 col-sm-3 col-6 p-1">
<<<<<<< HEAD
            <router-link :to="{name: 'book', params: {id: r.book.id}}"  class="router-links">
                <img class="book-img d-block mx-auto" :src="r.book.image" alt="">
                <p class="text-center mx-auto p-1 book-title">{{ r.book.name }}</p>
            </router-link>
            <div v-if="status == 2">
                <hr class="mx-5 mb-0">
                <p  class="text-center mx-5 p-1" 
                style="direction:rtl; font-size: smaller;">
                    {{  Number(r.progression*100).toFixed() }}% خوندی 
                </p>
            </div>
            
        </div> 
=======
            <single-book-item :book="r.book"></single-book-item>
            <hr class="mx-5 mb-0">
            <p v-if="status == 2" class="text-center mx-5 p-1"
            style="direction:rtl; font-size: smaller;">
                {{  Number(r.progression*100).toFixed() }}%  خوانده
            </p>
        </div>
>>>>>>> cdfcd59b3d34a53368ef6485264ff5c115edf978
    </div>
</template>

<script>

import { mapState } from 'vuex'
import PageHeader from "../layouts/PageHeader"
import SingleBookItem from '../layouts/SingleBookItem'
// import PageFooter from "../layouts/PageFooter"

export default {
    components: {
        PageHeader,
        SingleBookItem
    },
    data() {
        return {
            records: null,
            status: this.$route.params.status,
        }
    },

    created() {
        axios.get(`/api/profile/books/${this.$route.params.id}/${this.status}`)
        .then(response => {
            console.log(response.data.data)
            this.records = response.data.data;
        });
    },

    beforeMount() {

        let loadUser = new Promise((resolve, reject) => {
             if (this.user) {
                console.log('User is already loaded')
                resolve()
            } else {
                this.$store.dispatch("user/loadUser")
                .then(() => {
                    resolve()
                }).catch((error) => {
                    console.log(error.message)
                    if (error.message === 'Unauthorized') {
                        resolve()
                    }
                })
            }
        })
    },
    computed: {
        ...mapState({
            user: state => state.user.data,
            loggedIn: state => state.user.loggedIn
        }),
    },
    methods: {

    },
}
</script>

<style scoped>

.body {
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}
<<<<<<< HEAD
.shelf-book-img {
    max-width: 80px;
    max-height: 120px;
}
.book-img {
    width: 110px;
    height: 160px;
}
.book-title {
    direction: rtl;
    white-space:initial;
    max-width: 120px;
    max-height: 50px;
}
=======

>>>>>>> cdfcd59b3d34a53368ef6485264ff5c115edf978
.title {
    font-family: hamishe;
}

</style>
