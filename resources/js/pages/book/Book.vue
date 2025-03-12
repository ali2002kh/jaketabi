<template>
    <page-header :user="user"></page-header>
    <div class="body-class container-fluid mt-5 pt-5 row flex-row-reverse" v-if="book">
        <div class="col-lg-7 col-md-6 col-sm-7 col-12 mx-auto p-2" >
            <div class="book-details row flex-row-reverse rounded-2 p-2" style="background-color: #f4f4f4;">
                <div class="book col-lg-4 col-md-5 col-sm-6 col-12 p-3">
                    <img class="book-cover d-block mx-auto" :src="book.image" style="width:150px; max-height:200px;" alt="">
                </div>
                <div class="col-lg-8 col-md-7 col-sm-6 col-12 my-auto p-3 text-end lh-sm"
                :class="{'text-center' : windowWidth < 576}">
                    <div class="fw-bold p-1" style="font-size: 120%;">عنوان: {{ book.name }}</div>
                    <div class="p-1">نویسنده: {{ book.author }}</div>
                    <div class="p-1">ناشر: {{ book.publisher.name }}</div>
                    <div class="p-1" v-if="book.translator">مترجم: {{ book.translator }}</div>
                    <div class="p-1">دسته بندی:
                        <router-link class="router-links" :to="{ name: 'bookList', params: { title:'bookCategory', id: book.category_id }}">
                            {{ book.category }}
                        </router-link>
                    </div>
                    <div class="p-1" v-if="book.genres[0]">
                        ژانرها:
                        <div v-for="g in book.genres" :key="g.id">
                            <router-link class="router-links" :to="{ name: 'bookList', params: { title:'genre', id: g.id }}">
                                {{ g.name }}
                            </router-link>
                        </div>
                    </div>
                    <div class="p-1" v-if="book.page_count">تعداد صفحه: {{ book.page_count }}</div>
                </div>
            </div>
            <div v-if="book.description" class="book-desctiption row text-end mt-3 p-2 rounded-2 p-2"
            style="direction: rtl;background-color: #f4f4f4;">
                <div class="fw-bold p-1 fs-5 me-3">توضیحات</div>
                <hr class="border-muted mt-1">
                <p class="m-2 me-0  rounded-3 fs-6">
                    {{ book.description }}
                </p>
            </div>
            <div class="publisher-books row flex-row-reverse rounded-2 p-2 mt-3" style="background-color: #f4f4f4;">
                <book-swiper :books="book.publisher.preview_books" :sizes="sizes"></book-swiper>
            </div>

            <div class="related-books row flex-row-reverse rounded-2 p-2 mt-3  mb-4" style="background-color: #f4f4f4;">
                <div class="text-end me-2 fs-5 fw-bold"> کتاب های مشابه </div>
                <hr class="border-muted mt-1">
                <book-swiper :books="related" :sizes="sizes"></book-swiper>
            </div>
        </div>
        <div class="user-col col-lg-4 col-md-5 col-sm-5 col-12 h-100 mx-auto p-2" >
            <div v-if="user" class="book-user rounded-2 w-100 row" style="background-color: #f4f4f4;">
                <div class="row flex-row-reverse align-items-center mt-2 p-3 m-1 py-0">
                    <div class="add-button col-md-2 col-sm-3 col-2 g-0">
                        <button v-if="isNotSelected" @click.prevent="addToWantToRead" class="btn" type="button">
                            <i class="fa-solid fa-circle-plus fa-2xl"></i>
                        </button>
                        <button v-if="isWantToRead || isCurrentlyReading" @click.prevent="completed" class="btn" type="button">
                            <i class="fa-regular fa-circle-check fa-2x"></i>
                        </button>
                    </div>
                    <div class="col-md-10 col-sm-8 col-10" :class="{'mx-auto' : isAlreadyRead}">
                        <select v-model="selected_status" @change="update_status()" class="form-select" aria-label="Default select example">
                            <option v-for="status in statuses" :key="status" :value="status" class="text-center p-2">
                                {{ statuses_text[status] }}
                            </option>
                        </select>
                    </div>
                </div>
                <add-book-to-shelves v-if="user"
                    :shelves="shelves"
                    :shelves_with_this_book="shelves_with_this_book">
                </add-book-to-shelves>

                <div v-if="isCurrentlyReading"
                class="row flex-row-reverse page-number p-3 m-1 py-2 align-items-center justify-content-center">
                        <span class="col-3 p-2 text-end">  شماره صفحه </span>
                        <input v-model="current_page_input" class="bg-white rounded-1 border border-muted p-1 col-7"/>
                        <a class="btn col-2" @click.prevent="update_current_page">
                            <i class="fa-solid fa-pen fa-lg"></i>
                        </a>
                </div>
                <div v-if="has_start_date" class="row flex-row-reverse p-4 m-1 py-0">
                    <p class="col-3 p-1 mx-1 text-end">شروع </p>
                    <p class="col-7 bg-white border border-muted rounded-1 p-1"> {{start_date}} </p>
                </div>
                <div v-if="has_last_read_date" class="row flex-row-reverse p-3 m-1 py-0">
                    <span class="col-3 p-1 mx-1 text-end">آخرین مطالعه</span>
                    <p class="col-7 bg-white border border-muted rounded-1 p-1 "> {{last_read_date}} </p>
                </div>
                <div v-if="has_finish_date" class="row flex-row-reverse p-3 m-1 py-0">
                    <span class="col-3 p-1 mx-1 text-end"> پایان </span>
                    <p class="col-7 bg-white border border-muted rounded-1 p-1"> {{finish_date}}</p>
                </div>
                <div v-if="has_progression" style="vertical-align:bottom">
                    <div class="progress"  role="progressbar" aria-label="Success example"
                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-dark" :class="{ already_read: isAlreadyRead}"
                        :style="{ width: record.progression * 100 + '%' }"></div>
                    </div>
                </div>

            </div>
            <div class="user-friends row rounded-2 h-100 mt-3 w-100" style="background-color: #f4f4f4;">
                <div v-if="user" class="row flex-row-reverse align-items-center m-1 p-3">
                    <p class="col-4 text-end fw-bold m-1">فعالیت دوستان</p>
                    <div class="col-8 row flex-row-reverse mt-2">
                        <div v-for="f in friend_book.preview_friends" :key="f.id" class="col-4">
                            <router-link :to="{name: 'profile', params: {id: f.id}}">
                                <img class="rounded-5" :src="f.image" style="width: 40px; height:40px;" alt="">
                            </router-link>
                        </div>
                    </div>
                </div>
                <div v-else class="row user-friends justify-content-center me-2 rounded-1 h-100 mt-3">
                    لاگین نکرده
                </div>
                <div class="row text-center p-2 m-1 py-2">
                    <router-link :to="{name: 'bookComments', params: {id: book.id}}">
                        <button class="btn btn-dark w-100 text-white">نظرات کاربران</button>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { mapState } from 'vuex';
import PageHeader from "../../layouts/PageHeader"
import moment from "moment";
import BookSwiper from '../../layouts/BookSwiper';
import AddBookToShelves from '../../layouts/AddBookToShelves';
// import PageFooter from "../layouts/PageFooter"

export default {
    components: {
        PageHeader,
        BookSwiper,
        AddBookToShelves,
        // PageFooter,
    },

    data() {
        return {
            sizes: [1, 2, 3, 4, 5],
            moment: moment,
            book: null,

            // relation with user
            record: null,
            selected_status: 0,
            statuses: [0, 1, 2, 3],
            statuses_text: {
                0: 'انتخاب نشده',
                1: 'می خواهم بخوانم',
                2: 'دارم می خوانم',
                3: 'خوانده ام'
            },
            current_page_input: null,
            has_progression: false,
            friend_book: null,
            start_date: null,
            last_read_date: null,
            finish_date: null,
            has_start_date: false,
            has_last_read_date: false,
            has_finish_date: false,

            shelves: [],
            shelves_with_this_book: [],

            isFull: false,
            windowWidth: window.innerWidth,
            related: null,
        }
    },
    created() {
        axios.get(`/api/book/${this.$route.params.id}`)
        .then(response => {
            console.log(response.data.data)
            this.book = response.data.data
            this.related = this.book.related_books
        });
    },
    mounted() {

        console.log("mounted")
        window.addEventListener('resize', this.responsiveBookList);
        let userLoaded = new Promise((resolve, reject) => {
             if (this.user) {
                console.log('User is already loaded')
                resolve()
            } else {
                this.$store.dispatch("user/loadUser").then(() => {
                    console.log('resolved')
                    resolve()
                }).catch(() => {
                    reject()
                })
                console.log('called')

            }
        })

        userLoaded.then(() => {
            console.log(this.user)

            axios.get(`/api/book-record/${this.user.id}/${this.$route.params.id}`)
            .then(response => {
                console.log(response.data.data)
                this.record = response.data.data
                this.selected_status = this.record.status_code
                this.current_page_input = this.record.current_page
                this.start_date = this.record.started_at
                this.last_read_date = this.record.last_read_at
                this.finish_date = this.record.finished_at

                if (this.isCurrentlyReading) {
                    this.has_progression = true
                    this.has_start_date = true
                    if (this.current_page_input) {
                        this.has_last_read_date = true
                    }
                }
                if (this.isAlreadyRead) {
                    this.has_progression = true
                    this.has_start_date = true
                    this.has_finish_date = true
                }
                console.log("has progression: "+this.has_progression)
                if (this.record.shelves_with_this_book) {
                    this.shelves_with_this_book = this.record.shelves_with_this_book
                }

            })

            axios.get(`/api/book-friend/${this.$route.params.id}`)
            .then(response => {
                console.log(response.data.data)
                this.friend_book = response.data.data
            })

            axios.get(`/api/shelves/${this.user.id}`)
            .then(response => {
                console.log(response.data.data)
                this.shelves = response.data.data;
            });
        })

    },
    unmounted() {
        window.removeEventListener('resize', this.responsiveBookList);
    },
    methods: {
        async update_status() {
            console.log(this.selected_status)
            await axios.get(`/api/update-book-status/${this.book.id}/${this.selected_status}`)
            .then(() => {
                this.record.status_code = this.selected_status
                if (this.isAlreadyRead) {
                    this.record.progression = 1
                    this.has_progression = true
                    this.finish_date = moment(new Date()).format("YYYY-MM-DD")

                    this.has_start_date = true
                    this.has_last_read_date = false
                    this.has_finish_date = true
                }
                else if (this.isCurrentlyReading) {
                    console.log("current page input: " + this.current_page_input)
                    this.record.progression = this.current_page_input / this.book.page_count
                    this.has_progression = true
                    this.start_date = moment(new Date()).format("YYYY-MM-DD")
                    this.last_read_date = moment(new Date()).format("YYYY-MM-DD")

                    this.has_start_date = true
                    this.has_finish_date = false
                    if (this.current_page_input) {
                        this.has_last_read_date = true
                    } else {
                        this.has_last_read_date = false
                    }
                }
                else {
                    this.has_progression = false
                    this.has_start_date = false
                    this.has_finish_date = false
                    this.has_last_read_date = false
                }
                console.log("has start dat: " + this.has_start_date)
                console.log("has progression: "+this.has_progression)
                // console.log(this.isAlreadyRead)
            })
        },

        async completed() {
            await await axios.get(`/api/update-book-status/${this.book.id}/3`)
            .then(() => {
                this.record.status_code = 3
                this.selected_status = 3
                this.record.progression = 1
                this.has_progression = true
                if (!this.start_date) {
                    this.start_date = moment(new Date()).format("YYYY-MM-DD")
                }
                this.finish_date = moment(new Date()).format("YYYY-MM-DD")
                this.has_start_date = true
                this.has_finish_date = true
                this.has_last_read_date = false
            })
        },

        async addToWantToRead() {
            await await axios.get(`/api/update-book-status/${this.book.id}/1`)
            .then(() => {
                this.record.status_code = 1
                this.selected_status = 1
            })
        },

        async update_current_page() {
            await axios.get(`/api/update-book-current-page/${this.$route.params.id}/${this.current_page_input}`)
            .then(() => {
                this.record.progression = this.current_page_input / this.book.page_count
                this.last_read_date = moment(new Date()).format("YYYY-MM-DD")
                this.has_last_read_date = true
                console.log(this.record.progression)
            })
        },
    },

    computed: {
        ...mapState({
            user: state => state.user.data,
            loggedIn: state => state.user.loggedIn,
        }),

        isNotSelected() {
            if (this.record && this.record.status_code == 0) {
                return true
            }
            return false
        },

        isWantToRead() {
            if (this.record && this.record.status_code == 1) {
                return true
            }
            return false
        },

        isCurrentlyReading() {
            if (this.record && this.record.status_code == 2) {
                return true
            }
            return false
        },

        isAlreadyRead() {
            if (this.record && this.record.status_code == 3) {
                return true
            }
            return false
        },
        isFull() {
            if ((this.book.publisher.preview_books).length == 5) {
                return true;
            }
            return false
        },

    },
}
</script>

<style scoped>


.body-class {
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}
.container-fluid {
    padding-right:0;
    padding-left:0;
    margin-right:0;
    margin-left:0;
 }
.book-cover {
    width: 100px;
    max-height: 180px;
}
.already_read {
    background-color:green !important;
}
.user-col {
    position: -webkit-sticky;
    position: sticky;
    top: 90px;
}

.router-links {
    color: black;
    text-decoration: none;
}

.form-select:focus {
    border: 2px solid black;
    box-shadow:none;
}

</style>
