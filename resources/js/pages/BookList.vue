<template>
    <PageHeader></PageHeader>
    <div class="container-fluid body-class">

        <p v-if="isPublisher" class="title mx-1" style="margin-top: 110px;"><span>نشر</span></p>
        <p v-else class="title mx-1" style="margin-top: 110px;"><span>ژانر</span></p>

        <p class="title mx-1" style="margin-top: 110px;"><span>
            {{ name }}
        </span></p>



        <div class="popular-books row flex-row-reverse mx-1 p-1 rounded-1" style="background-color: #f4f4f4;">
            <div v-for="b in books" :key="b.id"  class="col-auto p-1 mx-3">
                <router-link :to="{name: 'book', params: {id: b.id}}" class="link-underline-opacity-0 link-dark">
                    <img class="book-img d-block border mx-auto" :src="b.image" alt="">
                    <p class="text-center p-1">{{ b.name }}</p>
                </router-link> 
            </div> 
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
        // PageFooter,
    },
    data() {
        return {
            books: null,
            title: this.$route.params.title,
            name: null,
            isPublisher: false,
        } 
    },
    created() {

        if (this.title == 'publisher') {
            this.isPublisher = true
            axios.get(`/api/publisher/${this.$route.params.id}`)
            .then(response => {
                console.log(response.data.data)
                this.books = response.data.data.books
                this.name = response.data.data.publisher.name
            });
        } else if (this.title == 'genre') {
            axios.get(`/api/genre/${this.$route.params.id}`)
            .then(response => {
                console.log(response.data.data)
                this.books = response.data.data.books
                this.name = response.data.data.genre.name
            });
        }

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

        loadUser.then(() => {
            if (this.user) {
                axios.get('/api/friends-activities')
                .then(response => {
                    console.log(response.data.data)
                    this.activities = response.data.data;
                });

                axios.get('/api/friends-shelves')
                .then(response => {
                    console.log(response.data.data)
                    this.shelves = response.data.data;
                });
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
