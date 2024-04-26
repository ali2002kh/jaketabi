<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container-fluid">
                <ul class="nav navbar-nav me-auto">
                    <li class="nav-item ps-4">
                        <a class="nav-link" href="#">
                            <i class="fa-solid fa-bell fa-lg"></i>
                        </a>
                    </li>


                    <!-- <li class="nav-item ps-2">
                        <a class="nav-link" href="#">
                            <img class="w-50" src="storage/icons/search.png" alt="">
                        </a>
                    </li> -->

                    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#searchModal">
                        <i class="fa-solid fa-magnifying-glass fa-lg"></i>
                    </button>


                </ul>
                <ul class="nav navbar-nav justify-content-center">
                    <li class="nav-item">
                        <p class="navbar-text text-white fs-5">ج‍‍‍‍اکتابی</p>
                    </li>
                </ul>
                <ul class="nav navbar-nav ms-auto">
                    <li class="nav-item ps-4">
                        <router-link class="nav-link" :to="{name: 'profile', params: {id: 1}}">
                            <i class="fa-solid fa-user fa-lg"></i>
                        </router-link>
                    </li>
                    <li class="nav-item ps-2">
                        <router-link class="nav-link" :to="{name: 'home'}">
                            <i class="fa-solid fa-house fa-lg"></i>
                        </router-link>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="searchModalLabel">جستجو</h1>
                    <button id="close" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" name="search" 
                    @keyup="search" v-model="input">
                    <br>
                    <div class="container d-grid mt-2">


                        <p v-if="books[0]" class="text-center">کتاب ها</p>
                        <a href="" v-for="b in books" :key="b.id" class="nav-link" @click.prevent="showBook(b.id)">
                            <div class="d-flex m-2">
                                <img class="item-img me-3" :src="b.image" alt="">
                                <div class="d-flex align-items-center">
                                    <p class="text-center">{{ b.name }}</p>
                                </div>
                            </div>
                        </a>

                        <hr v-if="shelves[0]">
                        <p v-if="shelves[0]" class="text-center">قفسه ها</p>
                        <a href="" v-for="s in shelves" :key="s.id" class="nav-link" @click.prevent="showShelf(s.id)">
                            <div class="d-flex m-2">
                                <div class="d-flex align-items-center">
                                    <p class="text-center">{{ s.name }}</p>
                                </div>
                            </div>
                        </a>

                        <hr v-if="genres[0]">
                        <p v-if="genres[0]" class="text-center">ژانر ها</p>
                        <a href="" v-for="g in genres" :key="g.id" class="nav-link" @click.prevent="showGenre(g.id)">
                            <div class="d-flex m-2">
                                <div class="d-flex align-items-center">
                                    <p class="text-center">{{ g.name }}</p>
                                </div>
                            </div>
                        </a>

                        <hr v-if="categories[0]">
                        <p v-if="categories[0]" class="text-center">دسته بندی ها</p>
                        <a href="" v-for="c in categories" :key="c.id" class="nav-link" @click.prevent="showCategory(c.id)">
                            <div class="d-flex m-2">
                                <div class="d-flex align-items-center">
                                    <p class="text-center">{{ c.name }}</p>
                                </div>
                            </div>
                        </a>

                        <hr v-if="publishers[0]">
                        <p v-if="publishers[0]" class="text-center">ناشر ها</p>
                        <a href="" v-for="p in publishers" :key="p.id" class="nav-link" @click.prevent="showPublisher(p.id)">
                            <div class="d-flex m-2">
                                <div class="d-flex align-items-center">
                                    <p class="text-center">{{ p.name }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    data() {
        return {
            input: '',
            books: [],
            shelves: [],
            genres: [],
            categories: [],
            publishers: [],
            timeoutId: null,
        } 
    },
    beforeMount() {
        if (this.user) {
            console.log('User is already loaded')
        } else {    
            console.log('call loadUser')
            this.$store.dispatch("user/loadUser");
        }
    },
    computed: {
        ...mapState({
            user: state => state.user.data,
        }),
    },
    created() {
        // axios.get('/api/categories')
        // .then(response => {
        //     console.log(response.data.data)
        //     this.categories = response.data.data;
        // });

        // axios.get('/api/user')
        // .then(response => {
        //     console.log(response.data.data)
        //     this.user = response.data.data;
        //     this.isLoggedIn = true;
        // }).catch(error => {
        //     if (error.response && 
        //     error.response.status && 
        //     error.response.status == 401) {
        //         this.isLoggedIn = false;
        //         this.user = null;
        //     };
        // });
    },
    methods: {
    //     async logout () {
    //         axios.post('/logout')
    //         .then(response => {
    //             this.isLoggedIn = false;
    //             this.user = null;
    //             this.$router.push('/login')
    //         })
    //     },

        async search() {

            clearTimeout(this.timeoutId);

            this.timeoutId = setTimeout(() => {
                if (this.input.length > 1) {
                    axios.post('/api/search', {
                        input: this.input
                    }).then(response => {
                        console.log(response.data.data)
                        this.books = response.data.data.books
                        this.shelves = response.data.data.shelves
                        this.genres = response.data.data.genres
                        this.categories = response.data.data.categories
                        this.publishers = response.data.data.publishers
                    })
                }
            }, 1500);
        },

        async showBook (id) {
            document.getElementById('close').click()
            this.$router.replace({ name: 'book', params: { id: id }});
        },

        async showShelf (id) {
            document.getElementById('close').click()
            this.$router.replace({ name: 'shelf', params: { id: id }});
        },

        async showGenre (id) {
            document.getElementById('close').click()
            // this.$router.replace({ name: 'genre', params: { id: id }});
        },

        async showCategory (id) {
            document.getElementById('close').click()
            // this.$router.replace({ name: 'category', params: { id: id }});
        },

        async showPublisher (id) {
            document.getElementById('close').click()
            // this.$router.replace({ name: 'publisher', params: { id: id }});
        },
    },
}
</script>

<style scoped>
.item-img {
    width : 80px;
    height: 80px;
    border-radius: 10px;
}
</style>