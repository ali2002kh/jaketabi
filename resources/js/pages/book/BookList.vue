<template>
    <PageHeader></PageHeader>
    <div class="container-fluid body-class">

        <p v-if="title == 'publisher'" class="title mx-1" style="margin-top: 110px;"><span>نشر</span></p>
        <p v-if="title == 'genre'" class="title mx-1" style="margin-top: 110px;"><span>ژانر</span></p>
        <p v-if="title == 'bookCategory'" class="title mx-1" style="margin-top: 110px;"><span>دسته‌بندی</span></p>

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

        <nav aria-label="Page navigation example" v-if="pagination">
            <ul class="pagination ms-3">
                <li class="page-item"><button v-if="hasPrev" @click.prevent="prev" class="page-link">Previous</button></li>
                <li class="page-item"><button class="page-link">{{ page }}</button></li>
                <li class="page-item"><button v-if="hasNext" @click.prevent="next" class="page-link">Next</button></li>
            </ul>
        </nav>

    </div>
</template>

<script>

import { mapState } from 'vuex';
import PageHeader from "../../layouts/PageHeader"
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

            // pagination
            page: 0,
            numberOfBooks: 0,
            pageSize: 0,
            hasNext: true,
            hasPrev: false,
        } 
    },
    created() {

        if (this.title == 'publisher') {
            axios.get(`/api/publisher/${this.$route.params.id}/${this.page}`)
            .then(response => {
                console.log(response.data)
                this.numberOfBooks = response.data.publisher.book_count
                this.pageSize = response.data.pageSize
                this.name = response.data.publisher.name
            });

        } else if (this.title == 'genre') {
            axios.get(`/api/genre/${this.$route.params.id}/${this.page}`)
            .then(response => {
                console.log(response.data)
                this.numberOfBooks = response.data.genre.book_count
                this.pageSize = response.data.pageSize
                this.name = response.data.genre.name
            });

        } else if (this.title == 'bookCategory') {
            axios.get(`/api/book/category/${this.$route.params.id}/${this.page}`)
            .then(response => {
                console.log(response.data.data)
                this.numberOfBooks = response.data.bookCategory.book_count
                this.pageSize = response.data.pageSize
                this.name = response.data.bookCategory.name
            });
        } else {
            alert('404')
        }

        this.page++
        this.getBooks()
        
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

        pagination() {
            return this.hasNext || this.hasPrev
        },
    },
    methods: {
        
        async getBooks() {
            if (this.title == 'publisher') {
                axios.get(`/api/publisher/${this.$route.params.id}/${this.page}`)
                .then(response => {
                    console.log(response.data.data)
                    this.books = response.data.data
                    this.checkNext()
                })
            } else if (this.title == 'genre') {
                axios.get(`/api/genre/${this.$route.params.id}/${this.page}`)
                .then(response => {
                    console.log(response.data.data)
                    this.books = response.data.data
                    this.checkNext()
                });
            } else if (this.title == 'bookCategory') {
                axios.get(`/api/book/category/${this.$route.params.id}/${this.page}`)
                .then(response => {
                    console.log(response.data.data)
                    this.books = response.data.data
                    this.checkNext()
                });
            }
        },
        async next() {
            this.page++
            this.getBooks()
            this.hasPrev = true
        },
        async prev() {
            this.page--
            this.getBooks()
            this.hasNext = true
            if (this.page <= 1) {
                this.hasPrev = false
            }
        },
        async checkNext() {
            console.log(this.books)
            if (this.books.length < this.pageSize || 
                ((this.page - 1) * this.pageSize) + this.books.length == this.numberOfBooks) {
                    console.log(this.books.length)
                    this.hasNext = false
            }
        } 
    },
}
</script>


<style scoped>
.body-class {
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
} 
.book-img {
        max-width: 120px;
        max-height: 200px;
}

.shelf-book-img {
    max-width: 80px;
    max-height: 120px;
}
.title {
    width: 100%; 
    direction: rtl;
    border-bottom: 1.5px solid rgb(232, 232, 232); 
    line-height: 0.1em;
    font-family: hamishe;
} 
    
.title span { 
        background:#fff; 
        padding-left: 20px;
        padding-right: 20px;
        margin-right: 30px;
        font-weight:bold;
        font-size: large;
}

.user-profile {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

</style>