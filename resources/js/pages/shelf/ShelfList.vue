<template>
    <PageHeader></PageHeader>
    <p class="title mt-5"><span>قفسه های 
        {{ shelves[0].user.username }}
    </span></p>

    <p v-if="isOwner" class="mt-5 mb-0 w-25" data-bs-toggle="modal" data-bs-target="#createShelf">
        <a href="#" class="link-dark text-center" style="text-decoration:none;">ایجاد قفسه جدید</a> 
    </p>

    <div class="modal fade" id="createShelf" tabindex="-1" aria-labelledby="createShelfLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createShelfLabel">ایجاد قفسه جدید</h1>
                    <button id="create_shelf_close" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" v-if="hasError">
                        <ul>
                            <li v-for="e in errors" :key="e">{{ e[0] }}</li>
                        </ul>
                    </div>
                    <div class="alert alert-success" v-if="success">{{ message }}</div>
                    <form>
                        <div class="m-1">
                            <label for="shelfName" class="form-label">نام قفسه</label>
                            <input type="text" class="form-control" id="shelfName" name="shelfName"
                            v-model="shelfName"
                            >
                        </div>
                        <div class="m-1">
                            <label for="shelfDescription" class="form-label">توضیحات</label>
                            <textarea id="shelfDescription" class="form-control" name="shelfDescription" v-model="shelfDescription"></textarea>
                        </div>
                        <div class="m-1 d-grid">
                            <button type="submit" class="btn btn-dark m-3" 
                            @click.prevent="storeShelf"
                            >تایید</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="col-11 g-0 d-flex flex-row-reverse p-1">
        <div v-for="s in shelves" :key="s.id" class="col-4 me-1">
            <div class="shelf-title row m-1 bg-dark rounded-top text-white bg-gradient">
                <p class="text-center  mx-auto fw-bold m-1"> {{ s.name }} </p>
            </div>
            <div class="shelf-books row flex-row-reverse justify-content-start align-items-center bg-light rounded-1 mx-1 p-1"
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
    },

    methods: {
        async storeShelf() {
            this.hasError = false
            this.errors = []
            this.success = false
            this.message = null
            await axios.post('/api/store-shelf', {
                name: this.shelfName,
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

    },
}
</script>

<style scoped>
.shelf-book-img {
    max-width: 80px;
    max-height: 120px;
    }
    .book-img {
    max-width: 120px;
    max-height: 200px;
    }
</style>