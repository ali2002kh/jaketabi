<template>
    <page-header></page-header>

    <!-- sidebar -->
    <div class="mt-5 m-2 d-flex flex-row-reverse">
        <div class="sidebar flex-item d-flex flex-column align-items-end">
            <div class="sidebar-item w-100 mb-1 mt-3">
                <a href="#" class="sidebar-link link-dark link-underline link-underline-opacity-0 fs-6 fw-bold p-2 d-block text-end">
                    افزودن کتاب
                    <i class="fa-solid fa-plus fa-lg p-2"></i>
                </a>
            </div>
            <div v-if="isOwner" class="sidebar-item d-block w-100" @click.prevent="deleteShelf()">
                <a class="sidebar-link link-dark link-underline link-underline-opacity-0 fs-6 fw-bold p-2 d-block text-end">
                    حذف قفسه
                    <i class="fa-solid fa-trash fa-lg p-2"></i>
                </a>
            </div>
            <div class="sidebar-item mt-2 w-100">
                <div class="d-block w-100">
                    <label class="collapse sidebar-link fw-bold text-end d-flex flex-row-reverse align-items-center w-100 p-2" for="_1">
                        <i class="fa-solid fa-quote-right fa-lg p-2 d-block text-end"></i>
                        توضیحات
                    </label>
                    <input id="_1" type="checkbox">
                    <div class="detail ">
                        <p class="d-block text-end p-2 pe-5 fs-6" style="width:250px; word-wrap: break-word;">
                            {{shelf.description}}
                        </p>

                    </div>
                </div>
            </div>
        </div>
        <div class="mt-5 shelf-header flex-item w-75 d-flex flex-row-reverse justify-content-between align-items-center" 
            style=" height: 100%; margin-right:250px;">
            <div class="shelf-title d-flex flex-row-reverse align-items-center">
                <div class="m-2 p-2">
                    <a href="#" class="link-dark link-underline-opacity-0" data-toggle="tooltip" data-placement="bottom" title=" ویرایش">
                            <i class="fa-solid fa-pen fa-lg ps-2"></i>
                    </a>
                </div>
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
            <router-link v-for="b in shelf.books" :key="b.id" :to="{name: 'book', params: {id: b.id}}"  class="col p-2">
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
        }
    },
    created() {
        axios.get(`/api/shelf/${this.$route.params.id}`)
        .then(response => {
            console.log(response.data.data)
            this.shelf = response.data.data
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
        }
    }
}
</script>

<style scoped>
        .sidebar {
            position: fixed;
            right:0;
            width: 220px;
            height: 100%;
            margin-top: 20px;
            background-color:#f4f4f4;

        }
        .sidebar-link {
            transition: all 0.2s;
        }
        .sidebar-link:hover {
            color: white !important;
            background-color: rgb(33, 37, 41);
        }
        .collapse{
            cursor: pointer;
            display: block;
        }
        .collapse + input{
            display: none;
        }
        .collapse + input + .detail{
            display:none;
        }
        .collapse + input:checked + .detail{
            display:block;
        }
        .book-cover {
            width: 100px;
            max-height: 180px;
        }
</style>