<template>
    <PageHeader></PageHeader>
    <div v-if="host" class="d-flex flex-row-reverse justify-content-between mt-5">
        <div class="d-flex flex-row-reverse align-items-center mt-5 me-4">
            <div class="ps-3 ">
                <img :src="host.image" style="widows: 60px; height: 60px; border-radius: 100%;" alt="">
            </div>
            <div class="d-flex flex-column align-items-center">
                <p class="fs-6 p-0 m-0 ">{{  host.username }}</p>
                <a v-if="host.is_private" @click.prevent="logout" href="#" class="link-underline link-underline-opacity-0 text-dark">
                    خروج
                </a>
            </div>
        </div>
        <div v-if="host.is_private" class="d-flex flex-row-reverse align-items-center mt-5 ms-4">
            <div class="ps-4">
                <!-- <router-link :to="{name: 'friends'}" class="text-dark link-underline link-underline-opacity-0">
                    <h5>دوستان</h5>
                </router-link> -->
                <div class="text-dark link-underline link-underline-opacity-0" data-bs-toggle="modal" data-bs-target="#friends">دوستان</div>

            </div>
            <div class="modal fade" id="friends" tabindex="-1" aria-labelledby="friendsLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="friendsLabel">دوستان</h1>
                            <button id="friend_close" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <button @click.prevent="toggleSearch">
                                <i v-if="searchIsActive" class="fa-solid fa-user fa-lg"></i>
                                <i v-else class="fa-solid fa-magnifying-glass fa-lg"></i>
                            </button>


                            <div v-if="searchIsActive">
                                <input type="text" class="form-control" name="search" 
                                @keyup="search" v-model="searchInput">
                                <br>
                                <div class="container d-grid mt-2">
                                    <div v-for="u in searchedUsers" :key="u.id" class="nav-link">
                                        <div class="d-flex m-2" @click.prevent="showProfile(u.id)">
                                            <img class="item-img me-3" style="widows: 60px; height: 60px; border-radius: 100%;" :src="u.image" alt="">
                                            <div class="d-flex align-items-center">
                                                <p class="text-center">{{ u.username }}</p>
                                            </div>
                                        </div>
                                        <button v-if="u.status == 0" class="btn btn-dark m-3" 
                                        @click.prevent="sendFriendRequest(u.id)"
                                        >درخواست دوستی</button>

                                        <button v-if="u.status == 1" class="btn btn-dark m-3" 
                                        @click.prevent="cancelFriendRequest(u.id)"
                                        >لغو درخواست</button>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="">
                                <div class="row" v-for="f in friends" :key="f.id">
                                    <div @click.prevent="showProfile(f.id)">
                                        <div class="col">
                                            {{ f.username }}
                                        </div>
                                        <div class="col">
                                            <img :src="f.image" style="widows: 60px; height: 60px; border-radius: 100%;" alt="">
                                        </div>
                                    </div>
                                    <button class="btn btn-dark m-3" 
                                    @click.prevent="removeFriend(f.id)"
                                    >حذف دوستی</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="pe-4">
                <a href="#" class="text-dark link-underline link-underline-opacity-0">
                    <h5>ویرایش</h5>
                </a>
            </div>
        </div>
    </div>
    <!-- books lists -->
    
    <div class="card mt-4 m-2" v-if="host">
        <div class="card-header bg-light m-0 p-0 ">
            <p class="float-end pt-1 me-4 fs-5 ">دارم می خوانم</p>
        </div>
        <div class="card-body d-flex flex-row-reverse m-0 p-0">
            <router-link :to="{name:'book', params:{id: b.id}}" v-for="b in host.reading" :key="b.id" class="d-flex flex-column m-1 p-2 align-items-center text-center">
                <img :src="b.image" style="height: 150px; max-width:100px" alt="">
                <p class=""> {{ b.name }}</p>
            </router-link>
            <div v-if="host.has_more_reading" class="d-flex flex-column m-1 p-2 align-items-center text-center justify-content-center me-auto">
                <a href="#">
                    <img src="storage/icons/icons8-forward-button-48.png"  alt="">
                </a>
                <p>+{{ host.reading_more_count }}</p>
            </div>
        </div>
    </div>
    <div class="card mt-4 m-2" v-if="host">
        <div class="card-header bg-light m-0 p-0 ">
            <p class="float-end pt-1 me-4 fs-5"> خوانده ام </p>
        </div>
        <div class="card-body d-flex flex-row-reverse m-0 p-0">
            <router-link :to="{name:'book', params:{id: b.id}}" v-for="b in host.already_read" :key="b.id" class="d-flex flex-column m-1 p-2 align-items-center text-center">
                <img :src="b.image" style="height: 150px; max-width:100px" alt="">
                <p class=""> {{ b.name }}</p>
            </router-link>
            <div v-if="host.has_more_already_read" class="d-flex flex-column m-1 p-2 align-items-center text-center justify-content-center me-auto">
                <a href="#">
                    <img src="storage/icons/icons8-forward-button-48.png"  alt="">
                </a>
                <p>+{{ host.already_read_more_count }}</p>
            </div>
        </div>
    </div>
    <div class="card mt-4 m-2" v-if="host">
        <div class="card-header bg-light m-0 p-0 ">
            <p class="float-end pt-1 me-4 fs-5"> میخواهم بخوانم </p>
        </div>
        <div class="card-body d-flex flex-row-reverse m-0 p-0">
            <router-link :to="{name:'book', params:{id: b.id}}" v-for="b in host.want_to_read" :key="b.id" class="d-flex flex-column m-1 p-2 align-items-center text-center">
                <img :src="b.image" style="height: 150px; max-width:100px" alt="">
                <p class=""> {{ b.name }}</p>
            </router-link>
            <div v-if="host.has_more_want_to_read" class="d-flex flex-column m-1 p-2 align-items-center text-center justify-content-center me-auto">
                <a href="#">
                    <img src="storage/icons/icons8-forward-button-48.png"  alt="">
                </a>
                <p>+{{ host.want_to_read_more_count }}</p>
            </div>
        </div>
    </div>
    <div class="card mt-4 m-2" v-if="host">
        <div class="card-header d-flex flex-row-reverse justify-content-between bg-light m-0 ">
            <div class=" me-4 fs-5 ">قفسه های من</div>
            <a href="#" class="text-dark link-underline link-underline-opacity-0">
                <div class="d-flex align-items-center me-5">
                    <div v-if="host.is_private" class="me-2 fs-6" data-bs-toggle="modal" data-bs-target="#createShelf">ایجاد قفسه جدید</div>
                    <img src="storage/icons8-add-48.png" style="width: 30px;" alt="">
                </div>
            </a>
        </div>

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

        <div class="card-body d-flex flex-row-reverse m-0 p-0" v-if="host">
            <div v-for="s in host.shelves" :key="s.id" class="card m-3">
                <div class="card-header m-0 p-1 text-center">{{ s.name }}</div>
                <div class="card-body d-flex flex-row-reverse align-items-center p-1 m-0">
                    <div v-for="b in s.books" :key="b.id" class="d-flex flex-column p-2  align-items-center text-center" >
                        <img :src="b.image" style="height: 120px; max-width:80px;" alt="">
                        <p style="font-size:13px; overflow: hidden;">{{ b.name }}</p>
                    </div>
                    <div class="me-auto">
                        <a href="#">
                            <img src="storage/icons/icons8-continue-48 (1).png" style="width: 30px;" alt="">
                        </a>
                    </div>
                </div>
            </div>
            <div v-if="host.has_more_shelves" class="d-flex flex-column m-1 p-2 align-items-center text-center justify-content-center me-auto">
                <a href="#">
                    <img src="storage/icons/icons8-forward-button-48.png"  alt="">
                </a>
                <p>+{{ shelves_more_count }}</p>
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
    },
    data() {
        return {
            host: null,
            shelfName: null,
            shelfDescription: null,
            hasError: false,
            errors: [],
            success: false,
            message: null,
            shelves_more_count: null,
            friends: null,
            searchIsActive: false,
            searchInput: null,
            timeoutId: null,
            searchedUsers: [],
        } 
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
                    this.host = this.user
                    this.friends = this.host.friends
                    console.log(this.host)
            } else {
                axios.get(`/api/user/${this.$route.params.id}`)
                .then((response) => {
                    console.log(response.data.data)
                    this.host = response.data.data
                })
            }

            if (this.host.has_more_shelves) {
                console.log(this.host.shelves_more_count)
                this.shelves_more_count = this.host.shelves_more_count
            }
        })
    },
    computed: {
        // ...mapGetters(["user"]),
        ...mapState({
            user: state => state.user.data,
            loggedIn: state => state.user.loggedIn
        }),
        // ...mapActions(['initState'])
    },
    methods: {
        async logout () {
            await this.$store.dispatch("user/logout");
            this.$router.push('/login')
        },

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
                // console.log(response.data.data.shelf)
                if(this.host.has_more_shelves){
                    this.shelves_more_count = this.shelves_more_count + 1
                } else if (this.host.shelves.length == 3) {
                    this.host.has_more_shelves = true
                    this.shelves_more_count = 1
                    console.log(this.shelves_more_count)
                } else {
                    this.host.shelves.push(response.data.data.shelf)
                }
                // console.log(this.user.shelves)
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

        async removeFriend(friend_id) {
            await axios.get(`/api/reject-or-remove-friend/${friend_id}`)
            .then(() => {
                this.friends = this.friends.filter(item => item.id !== friend_id)
            })
        },

        async sendFriendRequest(user_id) {
            await axios.get(`/api/accept-or-add-friend/${user_id}`)
            .then(() => {
                this.item = this.searchedUsers.find(item => item.id === user_id);
                this.item.status = 1
            })
        },

        async cancelFriendRequest(user_id) {
            await axios.get(`/api/reject-or-remove-friend/${user_id}`)
            .then(() => {
                this.item = this.searchedUsers.find(item => item.id === user_id);
                this.item.status = 0
            })
        },

        async showProfile (id) {
            document.getElementById('friend_close').click()
            this.$router.replace({ name: 'profile', params: { id: id }});
        },

        async toggleSearch() {
            if (this.searchIsActive) {
                this.searchIsActive = false
            } else {
                this.searchIsActive = true
            }
        },

        async search() {

            clearTimeout(this.timeoutId);

            this.timeoutId = setTimeout(() => {
                if (this.searchInput.length > 1) {
                    axios.post('/api/search/user', {
                        input: this.searchInput
                    }).then(response => {
                        console.log(response.data.data)
                        this.searchedUsers = response.data.data
                    })
                }
            }, 1500);
        },
    },
}
</script>