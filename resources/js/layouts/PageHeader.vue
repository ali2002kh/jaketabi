<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <ul class="nav navbar-nav me-auto">
                <li class="nav-item ps-4" v-if="user">
                    <button type="button" class="btn btn-link text-white" data-toggle="tooltip" data-placement="bottom" title="اعلان ها" data-bs-toggle="modal" data-bs-target="#notificationsModal">
                        <i class="fa-solid fa-bell fa-lg"></i>
                    </button>
                </li>

                <li>
                    <button type="button" class="btn btn-link text-white" data-toggle="tooltip" data-placement="bottom" title="جستجو" data-bs-toggle="modal" data-bs-target="#searchModal">
                        <i class="fa-solid fa-magnifying-glass fa-lg"></i>
                    </button>
                </li>
            </ul>
            <ul class="nav navbar-nav justify-content-center">
                <li class="nav-item">
                    <p class="navbar-text text-white fs-5">جاکتابی</p>
                </li>
            </ul>
            <ul class="nav navbar-nav ms-auto align-items-center">

                <li class="nav-item dropdown pe-2" v-if="user">
                        <a href="" data-bs-toggle="dropdown" aria-expanded="false"
                        class="text-white dropdown-toggle">
                            <i class="fa-solid fa-user fa-lg"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <router-link :to="{name: 'profile', params: {id: user.id}}"
                                class="dropdown-item text-end">پروفایل</router-link></li>
                            <li>
                                <a @click.prevent="logout" class="dropdown-item text-end" href="#">خروج</a>
                            </li>
                        </ul>
                </li>
                <li class="nav-item pe-3 " v-else>
                    <router-link :to="{name: 'login'}" data-toggle="tooltip" data-replacement="bottom" title="ورود" class="text-white">
                        <i class="fa-solid fa-right-to-bracket fa-lg"></i>
                    </router-link>
                </li>

                <li class="nav-item pe-3" v-if="user && user.role">
                    <router-link :to="{name: 'dashboard'}" data-toggle="tooltip" data-replacement="bottom" title="ادمین" class="text-white">
                        ادمین
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

                        <p v-if="genres[0]" class="text-center w-100 bg-light p-1 border rounded-1 mt-2">ژانر ها</p>
                        <a href="" v-for="g in genres" :key="g.id" class="nav-link" @click.prevent="showGenre(g.id)">
                            <div class="text-end me-2">
                                <p>{{ g.name }}</p>
                            </div>
                        </a>

                        <p v-if="categories[0]" class="text-center w-100 bg-light p-1 border rounded-1 mt-2">دسته بندی ها</p>
                        <a href="" v-for="c in categories" :key="c.id" class="nav-link" @click.prevent="showCategory(c.id)">
                        <div class="text-end me-2">
                                <p>{{ c.name }}</p>
                            </div>
                        </a>

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
    <div v-if="user" class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 w-100 text-center" id="notificationsModalLabel">درخواست ها</h1>
                    <button id="closeNotification" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div v-if="!friend_requests[0]" class="row flex-row-reverse align-items-center my-3">
                        درخواستی ندارید
                    </div>
                    <div class="row flex-row-reverse align-items-center my-3" v-for="r in friend_requests" :key="r.id">
                        <div class="col d-flex flex-row-reverse" @click.prevent="showProfile(r.id)">
                            <img class="item-img" style="width: 45px; height: 45px; border-radius: 100%;" :src="r.image" alt="">
                            <div class="align-self-center me-2">
                                {{ r.username }}
                            </div>
                        </div>
                        <div class="col-auto float-start">
                            <button class="btn btn-outline-success  p-1 px-3"
                            @click.prevent="acceptFriendRequest(r.id)"
                            >قبول  </button>
                        </div>

                        <div class="col-auto float-start">
                            <button class="btn btn-outline-danger p-1 px-4"
                            @click.prevent="rejectFriendRequest(r.id)"
                            >رد  </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {

    props: {
        user: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            input: '',
            books: [],
            shelves: [],
            genres: [],
            categories: [],
            publishers: [],
            timeoutId: null,
            friend_requests: [],
        }
    },

    mounted() {

        while (typeof this.user === 'undefined') {
            setTimeout(() => {
                if (typeof this.user != 'undefined') {
                    this.friend_requests = this.user.friend_requests
                }
            }, 100)
        }
    },

    methods: {

        async acceptFriendRequest(user_id) {
            await axios.get(`/api/accept-or-add-friend/${user_id}`)
            .then(() => {
                this.friend_requests = this.friend_requests.filter(item => item.id!== user_id);
            })
        },

        async rejectFriendRequest(user_id) {
            await axios.get(`/api/reject-or-remove-friend/${user_id}`)
            .then(() => {
                this.friend_requests = this.friend_requests.filter(item => item.id!== user_id);
            })
        },

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

        async showProfile (id) {
            document.getElementById('closeNotification').click()
            this.$router.replace({ name: 'profile', params: { id: id }});
        },
    },
}
</script>

<style scoped>
.item-img {
    width : 55px;
    height: 75px;
}
.dropdown-item:active {
    background: rgb(231, 231, 231) !important;
}
</style>
