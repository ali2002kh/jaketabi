<template>
<div class="body">
    <PageHeader></PageHeader>
    <div class="container header mx-auto mt-5 row flex-row-reverse align-items-center">
        <div class="col-8 title text-end fs-4 fw-bold">
            <p v-if="isOwner" class="title mt-5">قفسه های من</p>
            <p v-else class="title mt-5"><span>  
                {{ shelves[0].user.username }} قفسه های
            </span></p>
        </div>
        <div class="col-4 text-start">
            <p v-if="isOwner" class="mt-5 " data-bs-toggle="modal" data-bs-target="#createShelf">
                <a href="#" class="link-dark text-center d-flex align-items-center" style="text-decoration:none;">
                    ایجاد قفسه جدید
                    <i class="fa-solid fa-plus px-2"></i>
                </a> 
            </p>
        </div>
        <hr class="opacity-100 border border-muted">
    </div>
    <!-- create shelf modal  -->
    <div class="modal fade" id="createShelf" tabindex="-1" aria-labelledby="createShelfLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 w-100 text-center" id="createShelfLabel">ایجاد قفسه جدید</h1>
                    <button id="create_shelf_close" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-end">
                    <div class="alert alert-danger" v-if="hasError" dir="rtl">
                        <ul class="error-list">
                            <li v-for="e in errors" :key="e" class="error-item">{{ e[0] }}</li>
                        </ul>
                    </div>
                    <div class="alert alert-success" v-if="success" dir="rtl">{{ message }}</div>
                    <form>
                        <div class="m-1">
                            <label for="shelfName" class="form-label ">نام قفسه</label>
                            <input type="text" class="form-control text-end" id="shelfName" name="shelfName"
                            v-model="shelfName"
                            >
                        </div>
                        <div class="m-1">
                            <label for="shelfDescription" class="form-label ">توضیحات</label>
                            <textarea id="shelfDescription" class="form-control text-end" name="shelfDescription" v-model="shelfDescription"></textarea>
                        </div>
                        <div class="m-1 text-start">
                            <button type="submit" class="btn btn-dark m-3 ms-0 px-3" 
                            @click.prevent="storeShelf"
                            >تایید</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- shelves list  -->
    <div class="container rounded-1 mb-2 p-2 py-4 mx-auto h-100" style="background: #f4f4f4; height:100vh">
        <div class="row flex-row-reverse align-items-center" 
            v-for="row in rows" :key="'row' + row">
            <div class="d-flex flex-row-reverse">
                <div class="col-4 mb-3" v-for="(s,column) in shelvesInRow(row)" :key="'row' + row + column">
                    <div class="shelf-title row mx-2 bg-dark rounded-top text-white bg-gradient">
                        <p class="text-center mx-auto fw-bold m-1"> {{ s.name }} </p>
                    </div>
                    <div class="shelf-books row flex-row-reverse justify-content-start align-items-center bg-light mx-2
                        p-1 border-start border-end border-bottom rounded-bottom"
                        style="min-height:130px;" >
                        <div v-for="b in s.books" :key="b.id" class="col-auto p-2">
                            <img :src="b.image" class="shelf-book-img mx-1" alt="">
                        </div>
                        <router-link :to="{name:'shelf', params:{id: s.id}}" class="col-auto me-auto p-2">
                            <i class="fa-solid fa-angle-left fa-xl text-dark"></i>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
</template>

<script>

import { mapState } from 'vuex';
import PageHeader from "../../layouts/PageHeader"

export default {
    components: {
        PageHeader,
    },

    data() {
        return {
            shelves: null,
            isOwner: false,

            shelfName: null,
            shelfDescription: null,
            hasError: false,
            errors: [],
            success: false,
            message: null,
            columns: 3,
        } 
    },

    created() {
        axios.get(`/api/shelves/${this.$route.params.id}`)
        .then(response => {
            console.log(response.data.data)
            this.shelves = response.data.data;
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

        
        loadUser.then(() => {
            if (this.user && this.user.id == this.$route.params.id) {
                this.isOwner = true
            }
        })
    },

    computed: {
        ...mapState({
            user: state => state.user.data,
            loggedIn: state => state.user.loggedIn
        }),
        rows() {
            return this.shelves == null
            ? 0
            : Math.ceil(this.shelves.length / this.columns);
        },
    },

    methods: {
        async storeShelf() {
            this.hasError = false
            this.errors = []
            this.success = false
            this.message = null
            await axios.post('/api/store-shelf', {
                shelfName: this.shelfName,
                description: this.shelfDescription
            }).then((response) => {
                this.success = true;
                this.message = response.data.message
                this.shelves.push(response.data.data.shelf)
            }).catch ((error) => {
                if (error.response && 
                    error.response.status && 
                    error.response.status == 422) {
                        this.hasError = true
                        console.log(error.response.data)
                        this.errors = error.response.data.errors
                }
            })
        },
        shelvesInRow(row) {
            return this.shelves.slice((row - 1) * this.columns, row * this.columns)
        },
    },
}
</script>

<style scoped>
.body {
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}
.shelf-book-img {
    max-width: 80px;
    max-height: 120px;
}
.book-img {
    max-width: 120px;
    max-height: 200px;
}
.title {
    font-family: hamishe;
}
</style>