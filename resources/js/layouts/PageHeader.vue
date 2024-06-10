<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container-fluid">
                <ul class="nav navbar-nav me-auto">
                    <li class="nav-item ps-4">
                        <a class="nav-link text-white" data-toggle="tooltip" data-placement="bottom" title="اعلان ها" href="#">
                            <i class="fa-solid fa-bell fa-lg"></i>
                        </a>
                    </li>

                    <li>
                        <button type="button" class="btn btn-link text-white" data-toggle="tooltip" data-placement="bottom" title="جستجو" data-bs-toggle="modal" data-bs-target="#searchModal">
                            <i class="fa-solid fa-magnifying-glass fa-lg"></i>
                        </button>
                    </li>
                </ul>
                <ul class="nav navbar-nav justify-content-center">
                    <li class="nav-item">
                        <p class="navbar-text text-white fs-5">ج‍‍‍‍اکتابی</p>
                    </li>
                </ul>
                <ul class="nav navbar-nav ms-auto align-items-center">

                    <!-- <li class="nav-item ps-4">
                        <router-link class="nav-link" :to="{name: 'profile', params: {id: id}}">
                            <i class="fa-solid fa-user fa-lg"></i>
                        </router-link>
                    </li> -->

                    <li class="nav-item dropdown pe-2" v-if="user">
                            <router-link :to="{name: 'profile', params: {id: user.id}}" 
                                data-bs-toggle="dropdown" aria-expanded="false" title="پروفایل"
                                class="text-white dropdown-toggle">
                                    <i class="fa-solid fa-user fa-lg"></i>
                            </router-link>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item text-end" href="#">پروفایل</a></li>
                                <li>
                                    <a class="dropdown-item text-end" href="#">خروج</a>
                                </li>
                            </ul>
                    </li>
                    <li class="nav-item pe-3 " v-else>
                        <router-link :to="{name: 'login'}" data-toggle="tooltip" data-replacement="bottom" title="ورود" class="text-white">
                            <i class="fa-solid fa-right-to-bracket fa-lg"></i>
                        </router-link>
                    </li>

                    <li class="nav-item pe-4">
                        <router-link class="nav-link text-white" :to="{name: 'home'}" data-toggle="tooltip" data-replacement="bottom" title="خانه">
                            <i class="fa-solid fa-house fa-lg"></i>
                        </router-link>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 w-100 text-center" id="searchModalLabel">جستجو</h1>
                    <button id="close" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-end">
                    <input type="text" class="form-control text-end" name="search" 
                    @keyup="search" v-model="input">
                    <br>
                    <div class="">
                        <p v-if="books[0]" class="text-center w-100 bg-light p-1 border rounded-1 mt-2">کتاب ها</p>
                        <a href="" v-for="b in books" :key="b.id" class="nav-link" @click.prevent="showBook(b.id)">
                            <div class="d-flex flex-row-reverse align-items-center">
                                <p><img class="item-img" :src="b.image" alt=""></p> 
                                <div class="me-2">
                                    <p class="">{{ b.name }}</p>
                                </div>
                            </div>
                        </a>

                        <p v-if="shelves[0]" class="text-center w-100 bg-light p-1 border rounded-1 mt-2">قفسه ها</p>
                        <a href="" v-for="s in shelves" :key="s.id" class="nav-link" @click.prevent="showShelf(s.id)">
                            <div class="text-end me-2">
                                <p>{{ s.name }}</p>
                            </div>
                        </a>

                        <!-- <hr v-if="genres[0] && shelves[0]"> -->
                        <p v-if="genres[0]" class="text-center w-100 bg-light p-1 border rounded-1 mt-2">ژانر ها</p>
                        <a href="" v-for="g in genres" :key="g.id" class="nav-link" @click.prevent="showGenre(g.id)">
                            <div class="text-end me-2">
                                <p>{{ g.name }}</p>
                            </div>
                        </a>

                        <!-- <hr v-if="categories[0] && genres[0]"> -->
                        <p v-if="categories[0]" class="text-center w-100 bg-light p-1 border rounded-1 mt-2">دسته بندی ها</p>
                        <a href="" v-for="c in categories" :key="c.id" class="nav-link" @click.prevent="showCategory(c.id)">
                           <div class="text-end me-2">
                                <p>{{ c.name }}</p>
                            </div>
                        </a>

                        <!-- <hr v-if="publishers[0] && categories[0]"> -->
                        <p v-if="publishers[0]" class="text-center w-100 bg-light p-1 border rounded-1 mt-2">ناشر ها</p>
                        <a href="" v-for="p in publishers" :key="p.id" class="nav-link" @click.prevent="showPublisher(p.id)">
                            <div class="text-end me-2">
                                <p>{{ p.name }}</p>
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
    computed: {
        ...mapState({
            user: state => state.user.data,
            loggedIn: state => state.user.loggedIn
        }),
    },
    methods: {
        async logout () {
            await this.$store.dispatch("user/logout")
            .then(() => this.$router.push('/login'))
        },

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
            }, 800);
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
            this.$router.replace({ name: 'bookList', params: { title:'genre', id: id }});
        },

        async showCategory (id) {
            document.getElementById('close').click()
            this.$router.replace({ name: 'bookList', params: { title:'bookCategory', id: id }});
        },

        async showPublisher (id) {
            document.getElementById('close').click()
            this.$router.replace({ name: 'bookList', params: { title:'publisher', id: id }});
        },
    },
}
</script>

<style scoped>
.item-img {
    width : 55px;
    height: 75px;
}

</style>