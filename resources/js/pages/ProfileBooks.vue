<template>
    <PageHeader></PageHeader>
    <p class="title mt-5"><span>{{ records[0].status }}</span></p>
    <div class="trending-books row flex-row-reverse mx-1 p-1 rounded-1" style="background-color: #f4f4f4;">
        <div v-for="r in records" :key="r.id"   class="col-auto p-1 mx-3">
            <router-link :to="{name: 'book', params: {id: r.book.id}}"  class="link-underline link-underline-opacity-0 link-dark">
                <img class="book-img d-block mx-auto" :src="r.book.image" alt="">
                <p class="text-center p-1">{{ r.book.name }}</p>
            </router-link>
            <p v-if="status == 2">
                {{ r.progression }} 
            </p>
            
        </div> 
    </div>
</template>

<script>

import { mapState } from 'vuex';
import PageHeader from "../layouts/PageHeader"
// import PageFooter from "../layouts/PageFooter"

export default {
    components: {
        PageHeader,
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
}
</script>