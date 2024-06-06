<template>
    <page-header></page-header>

    <!-- sidebar -->
    <div class="mt-5 m-2 d-flex flex-row-reverse">
        <div class="sidebar flex-item d-flex flex-column align-items-end">
            <div v-if="isOwner" class="sidebar-item d-block w-100 mt-2" data-bs-toggle="modal" data-bs-target="#editShelf">
                <a href="#" class="sidebar-link link-dark link-underline link-underline-opacity-0 fs-6 fw-bold p-2 d-block text-end"
                style="text-decoration:none">
                    ویرایش قفسه
                    <i class="fa-solid fa-pen fa-md p-2"></i>
                </a>
            </div>
            <div v-if="isOwner" class="sidebar-item d-block w-100" data-bs-toggle="modal" data-bs-target="#deleteShelf">
                <a href="#" class="sidebar-link link-dark link-underline link-underline-opacity-0 fs-6 fw-bold p-2 d-block text-end"
                style="text-decoration:none">
                    حذف قفسه
                    <i class="fa-solid fa-trash fa-md p-2"></i>
                </a>
            </div>
            <hr v-if="isOwner" class="border border-secondary w-75 mx-auto p-0 m-1">
            <div class="sidebar-item w-100">
                <div class="d-block w-100 h-100 mb-5">
                    <div class="fw-bold fs-6 text-end d-flex flex-row-reverse align-items-center w-100 p-2">
                        <i class="fa-solid fa-quote-right fa-md p-2"></i>
                        توضیحات
                    </div>
                    <div class="d-block w-100 p-2" style="right:0;">
                        <p class="text-end fs-6" style="word-wrap: break-word;">
                            {{shelf.description}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Delete Shelf Modal -->
        <div class="modal fade" id="deleteShelf" tabindex="-1" aria-labelledby="deleteShelfLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h1 class="modal-title fs-6 w-100 text-center" id="deleteShelfLabel">حذف قفسه</h1>
                    </div>
                    <div class="modal-body text-end">
                        از حذف این قفسه مطمئنی؟
                    </div>
                    <div class="modal-footer d-flex flex-row justify-content-start">
                        <button type="button" class="btn btn-danger"  @click.prevent="deleteShelf()">حذف</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">لغو</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Shelf Modal-->
        <div class="modal fade" id="editShelf" tabindex="-1" aria-labelledby="editShelfLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-6 w-100 text-center" id="editShelfLabel">ویرایش قفسه</h1>
                        <button id="close" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-end">
                        <div class="alert alert-danger" v-if="hasError">
                            <ul>
                                <li v-for="e in errors" :key="e">{{ e[0] }}</li>
                            </ul>
                        </div>
                        <div class="alert alert-success" v-if="success">{{ message }}</div>
                        <form>
                            <div class="m-1">
                                <label for="shelfName" class="form-label">نام قفسه</label>
                                <input type="text" class="form-control text-end" id="shelfName" name="shelfName"
                                v-model="shelfName"
                                >
                            </div>
                            <div class="m-1 mt-3">
                                <label for="shelfDescription" class="form-label">توضیحات</label>
                                <textarea id="shelfDescription" class="form-control text-end" name="shelfDescription" v-model="shelfDescription"></textarea>
                            </div>
                            <div class="m-1 text-start">
                                <button type="submit" class="btn btn-dark m-1 mt-3" 
                                @click.prevent="updateShelf"
                                >تایید</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-5 shelf-header flex-item w-75 d-flex flex-row-reverse justify-content-between align-items-center" 
            style=" height: 100%; margin-right:250px;">
            <div class="shelf-title">
                <div class="justify-content-center">
                    <h4 class="text-dark fw-bold" style="white-space: nowrap;"> {{shelf.name}} </h4>
                </div>
            
            </div>
            <div class="">
                <p class=" fs-6 fw-bold">تعداد کتاب ها : {{shelf.book_count}} </p>
            </div>
        </div>
    </div>
    <hr class="opacity-100 border-muted border w-75 mx-auto" style="margin-right:250px !important;">
    <div class="container w-75 rounded-1" style="margin-right: 250px; " >
        <div class="row row-cols-6 flex-row-reverse">
            <router-link v-for="b in shelf.books" :key="b.id" :to="{name: 'book', params: {id: b.id}}"  class="router-links col p-2">
                <div>
                    <img :src="b.image" class="book-cover d-block mx-auto" alt="">
                    <p class="text-center p-1"> {{b.name}} </p>
                </div> 
            </router-link>
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
            shelf: null,
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
        axios.get(`/api/shelf/${this.$route.params.id}`)
        .then(response => {
            console.log(response.data.data)
            this.shelf = response.data.data
            this.shelfName = this.shelf.name
            this.shelfDescription = this.shelf.description
        })
    },  
    mounted() {

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
            if (this.user && this.user.id == this.shelf.user.id) {
                this.isOwner = true
            }
        })
    },
    computed: {
        // ...mapGetters(["user"]),
        ...mapState({
            user: state => state.user.data,
        }),
        // ...mapActions(['initState'])
    },
    methods: {
        async deleteShelf() {
            await axios.get(`/api/remove-shelf/${this.$route.params.id}`)
            .then(() => {
                this.$router.push(`/profile/${this.user.id}`)
            }) 
        },

        async updateShelf() {
            this.hasError = false
            this.errors = []
            this.success = false
            this.message = null
            await axios.post(`/api/update-shelf/${this.$route.params.id}`, {
                name: this.shelfName,
                description: this.shelfDescription
            }).then((response) => {
                this.success = true;
                this.message = response.data.message
                this.shelf.name = this.shelfName
                this.shelf.description = this.shelfDescription
            }).catch ((error) => {
                if (error.response && 
                    error.response.status && 
                    error.response.status == 422) {
                        this.hasError = true
                        console.log(error.response.data)
                        this.errors = error.response.data.errors
                }
            })
        }
    }
}
</script>

<style scoped>
        .sidebar {
            position: fixed;
            overflow-y: scroll;
            overflow-x:hidden;
            right:0;
            top: 0;
            bottom: 0;
            width: 220px;
            margin-top: 75px;
            background-color:#f4f4f4;

        }
        .sidebar-link {
            transition: all 0.2s;
        }
        .sidebar-link:hover {
            color: white !important;
            background-color: rgb(33, 37, 41);
        }
        .book-cover {
            width: 100px;
            max-height: 180px;
        }
        .router-links {
            color: black;
            text-decoration: none;
        }
</style>