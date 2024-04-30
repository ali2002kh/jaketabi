<template>
<div class="body-class" v-if="book">
    <PageHeader></PageHeader>
    <div class="container-fluid mt-5 pt-5 w-100">
        <div class="row flex-row-reverse mx-2">
            <div class="book-details col-8 rounded-2 p-2" style="background-color: #f4f4f4;">
                <div class="row flex-row-reverse ">
                    <div class="book col-6 m-1 p-2 d-flex flex-row-reverse ">
                        <img class="book-cover" :src="book.image" style="width:150px; max-height:200px;" alt="">
                        <div class="me-3 p-2 text-end lh-sm">
                            <p class="fw-bold" style="font-size: 120%;">عنوان: {{ book.name }}</p>
                            <p>نویسنده: {{ book.author }}</p>
                            <p>ناشر: {{ book.publisher }}</p>
                            <p v-if="book.translator">مترجم: {{ book.translator }}</p>
                        </div>
                    </div>
                    <div class="book-etc col text-end m-2 p-2 lh-1 " style="direction: rtl; max-height:200px">
                        <div class="d-flex flex-row">
                            <p>دسته بندی: {{ book.category }}</p>
                            <p class="pe-4" v-if="book.page_count">تعداد صفحه:{{ book.page_count }}</p>
                        </div>
                        
                        <p>ژانرها:</p>
                        <p v-for="g in book.genres" :key="g.id">
                            {{ g.name }}
                        </p>
                        <p>توضیحات:</p>
                        <p class="m-2 me-0  rounded-3  lh-sm" style="font-size: small;">
                            {{ book.description }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="book-user col rounded-2 p-1 me-2 h-100" style="background-color: #f4f4f4;">
                <div class="d-flex flex-row-reverse align-items-center justify-content-start mt-2">
                    <div class="add-button">
                        <button class="btn" type="button">
                            <i class="fa-regular fa-circle-check fa-2x"></i>
                            <!-- <i class="fa-solid fa-circle-plus fa-2xl"></i> -->
                        </button>
                    </div>
                    <select v-model="selected_status" @change="update_status()" class="form-select" aria-label="Default select example">
                        <option v-for="status in statuses" :key="status" :value="status">{{ statuses_text[status] }}</option>
                    </select>
                    <div class="text-end p-2 me-1">
                        <a class="btn btn-dark" href="#">
                            اضافه کردن به قفسه ها
                        </a>
                    </div>
                </div>
                <div>
                    <div class="page-number d-flex flex-row-reverse mt-2 me-2">
                        <span class="p-2 ">  شماره صفحه </span>
                        <p class="bg-white border rounded-1 p-2 "> 342 </p>
                        <a class="btn">
                            <i class="fa-solid fa-pen fa-lg mt-3"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <div class="start-date d-flex flex-row-reverse me-2">
                        <p class="p-2">شروع از </p>
                        <p class="bg-white border rounded-1 p-2 ">یک فروردین 1403</p>
                    </div>
                </div>
                <div>
                    <div class="finish-date d-flex flex-row-reverse me-2">
                        <span class="p-2 "> آخرین مطالعه در  </span>
                        <p class="bg-white border rounded-1 p-2 ">یک فروردین 1403</p>
                    </div>
                </div>
                <div  style="vertical-align:bottom">
                    <div class="progress"  role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-dark " style="width: 25%;"></div>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="row flex-row-reverse mx-2">
            <div class="publisher-books col-8 h-100">
                <p class="text-end m-2 fs-5 fw-bold">از همین نشر </p>
                <hr class="opacity-100 border-muted border mx-auto">
                <div class="row row-cols-6 flex-row-reverse align-items-center">
                    <div class="col ">
                        <a href="#" class="link-underline-opacity-0 link-dark">
                            <img class="book-cover d-block mx-auto" :src="book.image" alt="">
                        <p class="text-center p-1">سال بلوا</p>
                        </a> 
                    </div>
                    <div class="col p-5">
                        <button class="btn">
                            <i class="fa-solid fa-angle-left fa-3x"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div  class="user-friends col me-2 rounded-1 h-100 mt-3" style="background-color: #f4f4f4;">
                <div class="d-flex flex-row-reverse align-items-center m-1 p-2">
                    <p class="text-end fw-bold m-1"> دارند می خوانند / خوانده اند</p>
                    <div class="d-flex flex-row-reverse align-items-center">
                        <img class="rounded-5 me-3" :src="user.image" style="width: 40px; height:40px;" alt="">
                        <img class="rounded-5 me-3" :src="user.image" style="width: 40px; height:40px;" alt="">
                        <img class="rounded-5 me-3" :src="user.image" style="width: 40px; height:40px;" alt="">
                    </div>
                </div>
                <div class="m-1 p-3">
                    <button class="btn btn-dark w-100 text-white">نظرات کاربران</button>
                </div>
            </div>

        </div>
        <div class="row me-2 flex-row-reverse">
            <p class="text-end m-2 fs-5 fw-bold"> کتاب های مشابه </p>
            <hr class="opacity-100 border-muted border mx-auto">
                <div class="col">
                    <a href="#" class="link-underline-opacity-0 link-dark">
                        <img class="book-cover d-block mx-auto" :src="book.image" alt="">
                    <p class="text-center p-1">سال بلوا</p>
                    </a> 
                </div>
                
                <div class="col p-5 me-0">
                    <button class="btn">
                        <i class="fa-solid fa-angle-left fa-3x"></i>
                    </button>
                </div>
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
            book: null,
            record: null,
            selected_status: 0,
            statuses: [0, 1, 2, 3],
            statuses_text: {
                0: 'انتخاب نشده',
                1: 'می خواهم بخوانم',
                2: 'دارم می خوانم',
                3: 'خوانده ام'
            }
        }
    },
    created() {
        axios.get(`/api/book/${this.$route.params.id}`)
        .then(response => {
            console.log(response.data.data)
            this.book = response.data.data
        });
    },  
    mounted() {

        console.log("mounted")

        let userLoaded = new Promise((resolve, reject) => {
             if (this.user) {
                console.log('User is already loaded')
                resolve()
            } else {
                this.$store.dispatch("user/loadUser").then(() => {
                    console.log('resolved')
                    resolve()
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
                this.status_code = this.record.status_code
            })
        })

    },
    methods: {
        async update_status() {
            console.log(this.selected_status)
            axios.get(`/api/update-book-status/${this.book.id}/${this.selected_status}`)
        }
    },
    computed: {
        ...mapState({
            user: state => state.user.data,
            // loggedIn: state => state.user.loggedIn,
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

        isCurrentReading() {
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

    },
}
</script>

<style scoped>
    .body-class {
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }
    .book-cover {
        width: 100px;
        max-height: 180px;
    }
</style>