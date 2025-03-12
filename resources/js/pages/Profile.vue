<template>
   <page-header :user="user"></page-header>
    <div class="body-class container-fluid mb-4">
        <div v-if="host" class="profile-header row flex-row-reverse align-items-center mt-5">
            <div class="col-md-8 col-sm-8 col-6 mt-5 ">
                <div class="pe-3 d-flex flex-row-reverse">
                    <div>
                        <img :src="host.image" class="user-profile" alt="">
                    </div>
                    <div v-if="host.is_private" class="d-flex flex-column justify-content-center text-center me-3">
                        <p class="fs-6 p-0 m-0 ">{{ username }}</p>
                        <p class="fs-6 p-0 m-0">
                            {{ fname }} {{ lname }}
                        </p>
                    </div>
                    <div v-else class="d-flex flex-column justify-content-center text-center me-3">
                        <p class="fs-6 p-0 m-0 ">{{ host.username }}</p>
                        <p class="fs-6 p-0 m-0">
                            {{ host.name }}
                        </p>
                    </div>
                </div>
            </div>
            
            <friendship-management v-if="host" :host="host"></friendship-management>

            <edit-profile @profileReact="changeProfile" v-if="host && host.is_private" :host="host"></edit-profile>

        </div>
        <div v-if="host" class="book-lists">
            <!-- currently-reading list  -->
            <div class="d-flex flex-row-reverse align-items-center text-center mt-5 mx-2">
                <div class="col-auto title">
                     دارم میخوانم
                </div>
                <hr class="col opacity-100 border-muted border mx-3">
                <div v-if="host.has_more_reading" class="col-auto">
                    <router-link :to="{name: 'profileBooks', params: {id: host.id, status: 2 }}"
                    class="link-dark text-center" style="text-decoration:none;">
                        مشاهده همه
                    </router-link>
                </div>
            </div>
            <book-swiper :books="currentlyReading"></book-swiper>

            <!-- already-read list  -->
            <div class="d-flex flex-row-reverse align-items-center text-center mt-5 mx-2">
                <div class="col-auto title ">
                    خوانده ام
                </div>
                <hr class="col opacity-100 border-muted border mx-3">
                <div v-if="host.has_more_already_read" class="col-auto">
                    <router-link :to="{name: 'profileBooks', params: {id: host.id, status: 1 }}"
                    class="link-dark text-center" style="text-decoration:none;">
                        مشاهده همه
                    </router-link>
                </div>
            </div>
            <book-swiper :books="alreadyRead"></book-swiper>

            <!-- want-to-read list  -->
            <div class="d-flex flex-row-reverse align-items-center text-center mt-5 mx-2">
                <div class="col-auto title">
                    میخواهم بخوانم
                </div>
                <hr class="col opacity-100 border-muted border mx-3">
                <div v-if="host.has_more_want_to_read" class="col-auto">
                    <router-link :to="{name: 'profileBooks', params: {id: host.id, status: 1 }}"
                    class="link-dark text-center" style="text-decoration:none;">
                        مشاهده همه
                    </router-link>
                </div>
            </div>
            <book-swiper :books="wantToRead"></book-swiper>
        </div>

        <!-- shelves list  -->
        <div v-if="host" class="friends-shelves">
            <div class="d-flex flex-row-reverse align-items-center text-center mt-5 mx-2">
                <div class="col-1 title">
                   قفسه های من
                </div>
                <hr class="col opacity-100 border-muted border mx-3">
                <div class="col-auto">
                    <create-shelf @addShelf="addShelf" v-if="host.is_private"></create-shelf>
                    <router-link :to="{name: 'shelfList', params: {id: host.id}}"
                    class="link-dark text-center" style="text-decoration:none;">
                        مشاهده همه
                    </router-link>
                </div>
            </div>
            <shelves-swiper :shelves="host.shelves"></shelves-swiper>
        </div>
    </div>
</template>

<script>

import { mapState } from 'vuex'
import PageHeader from "../layouts/PageHeader"
import CreateShelf from '../layouts/CreateShelf'
import BookSwiper from '../layouts/BookSwiper'
import ShelvesSwiper from '../layouts/ShelvesSwiper'
import FriendshipManagement from '../layouts/FriendshipManagement'
import EditProfile from '../layouts/EditProfile'
// import PageFooter from "../layouts/PageFooter"

export default {
    components: {
        PageHeader,
        CreateShelf,
        BookSwiper,
        ShelvesSwiper,
        FriendshipManagement,
        EditProfile
    },
    data() {
        return {
            host: null,
            currentlyReading: [],
            fname: null,
            lname: null,
            username: null,
            wantToRead: [],
            alreadyRead: [],
            shelves_more_count: null,
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
                    console.log(this.host)

                    this.username = this.host.username
                    this.fname = this.host.fname
                    this.lname = this.host.lname
                    this.currentlyReading = this.host.reading
                    this.wantToRead = this.host.want_to_read
                    this.alreadyRead = this.host.already_read

            } else {
                axios.get(`/api/user/${this.$route.params.id}`)
                .then((response) => {
                    console.log(response.data.data)
                    this.host = response.data.data
                    this.username = this.host.username
                    this.fname = this.host.fname
                    this.lname = this.host.lname
                    this.currentlyReading = this.host.reading
                    this.wantToRead = this.host.want_to_read
                    this.alreadyRead = this.host.already_read
                })
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

        async logout () {
            await this.$store.dispatch("user/logout");
            this.$router.push('/login')
        },

        addShelf(shelf) {
            this.host.shelves.push(shelf)
        },

        changeProfile (fname, lname, username) {
            this.fname = fname
            this.lname = lname
            this.username = username
        }
    },
}

</script>

<style scoped>

.body-class {
font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}

.user-profile {
width: 60px;
height: 60px;
border-radius: 50%;
}

.title {
font-family: hamishe;
font-weight:bold;
font-size: large;
}

</style>
