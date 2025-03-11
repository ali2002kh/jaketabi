<template>
    <page-header :user="user"></page-header>
    <div class="container-fluid body-class">

        <p v-if="title == 'publisher'" class="title mx-1" style="margin-top: 110px;"><span>نشر {{ name }}</span></p>
        <p v-if="title == 'genre'" class="title mx-1" style="margin-top: 110px;"><span>ژانر {{ name }}</span></p>
        <p v-if="title == 'bookCategory'" class="title mx-1" style="margin-top: 110px;"><span>دسته‌ بندی {{ name }}</span></p>



        <div class="row flex-row-reverse mt-4" :class="{'justify-content-center mx-0' : windowWidth < 576}">
            <div v-for="b in books" :key="b.id"  class="col-md-2 col-sm-3 col-6 p-1">
                <single-book-item :book="b"></single-book-item>
            </div>
        </div>

        <nav aria-label="Page navigation example" v-if="pagination">
            <div class="pagination row flex-row-reverse justify-content-center align-items-center mt-4">
                <div class="page-item col-auto">
                    <button type="button" v-if="hasPrev" @click.prevent="prev"
                    class="page-link btn btn-link" style="border:none">
                        <i class="fa-solid fa-angle-right fa-xl text-dark"></i>
                    </button>
                    <button type="button" v-else class="page-link btn btn-link" disabled>
                        <i class="fa-solid fa-angle-right fa-xl text-dark"></i>
                    </button>
                </div>
                <div class="page-item col-auto">{{ page }}</div>
                <div class="page-item col-auto">
                    <button type="button" v-if="hasNext" @click.prevent="next"
                    class="page-link btn btn-link" style="border:none">
                        <i class="fa-solid fa-angle-left fa-xl text-dark"></i>
                    </button>
                    <button type="button" v-else class="page-link btn btn-link" disabled>
                        <i class="fa-solid fa-angle-left fa-xl text-dark"></i>
                    </button>
                </div>
            </div>
        </nav>


        <div v-if="title == 'bookCategory' && page > 0 && siblings[page - 1]" class="publisher-books h-100">
            <div class="d-flex flex-row-reverse align-items-center text-center mt-5 mx-2">
                <div class="col-auto title-sibling">
                    {{ siblings[page - 1].name }}
                </div>
                <hr class="col opacity-100 border-muted border mx-3">
                <div class="col-auto">
                    <router-link :to="{name: 'bookList', params: { title:'bookCategory', id: siblings[page - 1].id }}"
                    class="link-dark text-center" style="text-decoration:none;">
                        مشاهده همه
                    </router-link>
                </div>
            </div>
            <!-- <p class="title mt-4 mx-1">
                <span>  </span>
            </p> -->
            <!-- <p class="text-end m-2 fs-5 fw-bold"></p>
            <hr class="opacity-100 border-muted border mx-auto"> -->
            <div class="row flex-row-reverse align-items-center rounded-1 mx-1 p-1 mb-4" style="background-color: #f4f4f4;">
                <div v-for="b in siblings[page - 1].preview_books" :key="b.id" class="col-md-2 col-sm-3 col-6 p-1">
                    <single-book-item :book="b"></single-book-item>
                </div>
                <!-- <div class="col p-5">
                    <router-link :to="{name: 'bookList', params: { title:'bookCategory', id: siblings[page - 1].id }}">
                        <button class="btn">
                            <i class="fa-solid fa-angle-left fa-3x"></i>
                        </button>
                    </router-link>
                </div> -->
            </div>
        </div>


    </div>
</template>

<script>

import { mapState } from 'vuex';
import PageHeader from "../../layouts/PageHeader"
import SingleBookItem from '../../layouts/SingleBookItem';
// import PageFooter from "../layouts/PageFooter"

export default {
    components: {
        PageHeader,
        SingleBookItem,
        // PageFooter,
    },
    data() {
        return {
            books: null,
            title: this.$route.params.title,
            name: null,
            siblings: null,
            windowWidth: window.innerWidth,
            columns: null,
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
                this.siblings = response.data.bookCategory.siblings
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
    mounted() {
        window.addEventListener('resize', this.responsiveBookList);
    },
    unmounted() {
        window.removeEventListener('resize', this.responsiveBookList);
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
        },
        // Col() {
        //     this.windowWidth = window.innerWidth;
        //     if (this.windowWidth < 576) {
        //         this.columns = 1
        //         return this.columns;
        //     }
        //     else if (576 <= this.windowWidth && this.windowWidth < 768) {
        //         return this.columns;
        //     }
        //     else if (768 <= this.windowWidth && this.windowWidth < 1200) {
        //         return this.columns;
        //     }
        //     else if (this.windowWidth >= 1200) {
        //         return list;
        //     };
        // },
    },
}
</script>


<style scoped>
.body-class {
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}
.book-img {
width: 110px;
height: 160px;
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

.title-sibling {
    font-family: hamishe;
    font-weight:bold;
    font-size: large;
}

.user-profile {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.book-cover {
    width: 100px;
    max-height: 180px;
}

</style>
