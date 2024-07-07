<template>
    <PageHeader></PageHeader>
    <div class="body-class container-fluid mb-4">
        <div v-if="host" class="profile-header row flex-row-reverse align-items-center mt-5">
            <div class="col-9 mt-5 ">
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
            <div v-if="host.is_private" class="col mt-5">
                <div class="text-start">
                    <a href="#" class="text-dark link-underline link-underline-opacity-0">
                        <button class="btn btn-dark px-3" data-bs-toggle="modal" data-bs-target="#friends">دوستان</button>
                    </a>
                </div>
                <div class="modal fade" id="friends" tabindex="-1" aria-labelledby="friendsLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header ">
                                <button @click.prevent="toggleSearch" class="modal-title ms-0 btn-dark bg-white border-0"
                                style="cursor: pointer">
                                    <i v-if="searchIsActive" class="fa-solid fa-user fa-lg"></i>
                                    <i v-else class="fa-solid fa-magnifying-glass fa-lg"></i>
                                </button>
                                <h1 class="modal-title fs-5 text-center w-100" id="friendsLabel">دوستان</h1>
                                <button id="friend_close" type="button" class="btn-close  me-0" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div v-if="searchIsActive">
                                    <input type="text" class="form-control" name="search" 
                                    @keyup="search" v-model="searchInput">
                                    <br>
                                    <div class="mt-2">
                                        <div v-for="u in searchedUsers" :key="u.id" class="row flex-row-reverse align-items-center my-3">
                                            <div class="col d-flex flex-row-reverse" @click.prevent="showProfile(u.id)">
                                                <img class="item-img" style="width: 45px; height: 45px; border-radius: 100%;" :src="u.image" alt="">
                                                <div class="align-self-center me-2">
                                                    {{ u.username }}
                                                </div>
                                            </div>
                                            <div v-if="u.status == 0" class="col-auto float-start">
                                                <button  class="btn btn-dark m-1 p-1 px-2" 
                                                @click.prevent="sendFriendRequest(u.id)"
                                                >درخواست دوستی</button>
                                            </div>
                                            
                                            <div v-if="u.status == 1" class="col-auto float-start">
                                                <button class="btn btn-dark m-1 p-1 px-2" 
                                                @click.prevent="cancelFriendRequest(u.id)"
                                                >لغو درخواست</button>
                                            </div>

                                            <div v-if="u.status == 2" class="col-auto float-start">
                                                <button class="btn btn-outline-success  p-1 px-3" 
                                                @click.prevent="acceptFriendRequest(u.id)"
                                                >قبول  </button>
                                            </div>

                                            <div v-if="u.status == 2" class="col-auto float-start">
                                                <button class="btn btn-outline-danger p-1 px-4" 
                                                @click.prevent="rejectFriendRequest(u.id)"
                                                >رد  </button>
                                            </div>
                                            
                                            <div v-if="u.status == 3" class="col-auto float-start">
                                                <button class="btn btn-dark m-1 p-1 px-2" 
                                                @click.prevent="removeFriend(u.id)"
                                                >حذف دوستی</button>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
    
                                <div v-else class="">
                                    <div class="row flex-row-reverse align-items-center my-3" v-for="f in friends" :key="f.id">
                                        <div class="col d-flex flex-row-reverse" @click.prevent="showProfile(f.id)">
                                                <img class="item-img" style="width: 45px; height: 45px; border-radius: 100%;" :src="f.image" alt="">
                                                <div class="align-self-center me-2">
                                                    {{ f.username }}
                                                </div>
                                        </div>
                                        <div class="col-auto float-start">
                                                <button class="btn btn-dark m-1 p-1 px-2" 
                                                @click.prevent="removeFriend(f.id)"
                                                >حذف دوستی</button>
                                        </div>
                                    </div>
                                </div>
    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div v-if="host.is_private" class="col mt-5">
                <div class="text-center">
                    <a  href="#" class="text-dark link-underline link-underline-opacity-0">
                        <button class="btn btn-dark px-3" data-bs-toggle="modal" data-bs-target="#editProfile">ویرایش</button>
                    </a>
                </div>
                <div class="modal fade" id="editProfile" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5 w-100 text-center" id="editProfileLabel">ویرایش اطلاعات شخصی</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-end" style="font-size:14px">
                                <div class="alert alert-danger py-1 text-start" v-if="hasError2">
                                    <div class="py-0" v-for="e in errors2" :key="e">- {{ e[0] }}</div>
                                </div>
                                <div class="alert alert-success py-1" v-if="success2" style="font-size:14px">
                                    {{ message2 }}
                                </div>
                                <form style="font-size:14px">
                                    <div class="row">
                                        <div class="col">
                                            <label for="image" class="form-label pe-2">عکس پروفایل</label>
                                            <input class="form-control text-end" style="font-size:14px"
                                            type="file" id="image" @change="onFileChange($event)">
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col">
                                            <label for="email" class="form-label pe-2">ایمیل</label>
                                            <input type="email" class="form-control text-start" style="font-size:14px"
                                            id="email" name="email" v-model="email"
                                            >
                                        </div>
                                        <div class="col">
                                            <label for="username" class="form-label pe-2">نام کاربری</label>
                                            <input type="text" class="form-control text-start" style="font-size:14px"
                                            id="username" name="username"
                                            v-model="username"
                                            >
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col">
                                            <label for="lname" class="form-label pe-2">نام خانوادگی</label>
                                            <input type="text" class="form-control text-end" style="font-size:14px"
                                             id="lname" name="lname"
                                            v-model="lname"
                                            >
                                        </div>
                                        <div class="col">
                                            <label for="fname" class="form-label pe-2">نام</label>
                                            <input type="text" class="form-control text-end" style="font-size:14px"
                                             id="fname" name="fname"
                                            v-model="fname"
                                            >
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col">
                                            <label for="confirmPassword" class="form-label pe-2">تایید رمز عبور</label>
                                            <input type="password" class="form-control text-start"  style="font-size:14px"
                                            id="confirmPassword" name="confirmPassword"
                                            v-model="confirmPassword"
                                            >
                                        </div>
                                        <div class="col">
                                            <label for="password" class="form-label pe-2">رمز عبور</label>
                                            <input type="password" class="form-control text-start" style="font-size:14px"
                                            id="password" name="password" v-model="password"
                                            >
                                        </div>
                                    </div>
                                    
                                    <div class="row ms-1" >
                                        <button type="submit" class="col-auto btn btn-sm btn-dark mt-3 px-3 me-2" 
                                        @click.prevent="updateProfile"
                                        >بروزرسانی</button>
                                        <button type="button" class="col-auto btn btn-sm btn-secondary px-3 mt-3"
                                        data-bs-dismiss="modal" aria-label="Close">لغو</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="col mt-5">
                <div class="text-start">

                    <div v-if="friendship.status == 0" class="col-auto float-start">
                        <button  class="btn btn-dark m-1 p-1 px-2" 
                        @click.prevent="sendFriendRequest(friendship.id)"
                        >درخواست دوستی</button>
                    </div>
                    
                    <div v-if="friendship.status == 1" class="col-auto float-start">
                        <button class="btn btn-dark m-1 p-1 px-2" 
                        @click.prevent="cancelFriendRequest(friendship.id)"
                        >لغو درخواست</button>
                    </div>

                    <div v-if="friendship.status == 2" class="col-auto float-start">
                        <button class="btn btn-outline-success  p-1 px-3" 
                        @click.prevent="acceptFriendRequest(friendship.id)"
                        >قبول  </button>
                    </div>

                    <div v-if="friendship.status == 2" class="col-auto float-start">
                        <button class="btn btn-outline-danger p-1 px-4" 
                        @click.prevent="rejectFriendRequest(friendship.id)"
                        >رد  </button>
                    </div>
                    
                    <div v-if="friendship.status == 3" class="col-auto float-start">
                        <button class="btn btn-dark m-1 p-1 px-2" 
                        @click.prevent="removeFriend(friendship.id)"
                        >حذف دوستی</button>
                    </div>

                </div>
            </div>

        </div>
        <div v-if="host" class="book-lists">
            <p class="title mt-5"><span> دارم می خوانم </span></p>
            <div class="lists-body currently-reading row flex-row-reverse align-items-center mx-1 p-1 rounded-1">
                <div class="col-auto p-1 mx-3" v-for="b in host.reading" :key="b.id">
                    <router-link :to="{name:'book', params:{id: b.id}}"  
                    class="router-links d-flex flex-column m-1 p-2 align-items-center text-center">
                        <img :src="b.image" class="book-img" alt="">
                        <p class="book-title"> {{ b.name }}</p>
                    </router-link>
                </div>
                <div v-if="host.has_more_reading" class="col-auto" style="margin-right: 1000px;">
                    <router-link :to="{name: 'profileBooks', params: {id: host.id, status: 2 }}">
                        <button class="text-dark link-underline link-underline-opacity-0">
                            <i class="fa-solid fa-circle-chevron-left fa-2x"></i>
                            <p>+{{ host.already_read_more_count }}</p>
                        </button>
                    </router-link> 
                </div>
            </div>
            <p class="title mt-5"><span>  خوانده ام  </span></p>
            <div class="lists-body already-read row flex-row-reverse align-items-center mx-1 p-1 rounded-1">
                <div class="col-auto p-1 mx-3" v-for="b in host.already_read" :key="b.id" >
                    <router-link :to="{name:'book', params:{id: b.id}}" 
                    class="router-links d-flex flex-column m-1 p-2 align-items-center text-center">
                        <img :src="b.image" class="book-img" alt="">
                        <p class="book-title"> {{ b.name }}</p>
                    </router-link>
                </div>
                <div v-if="host.has_more_already_read" class="col-auto" style="margin-right: 1000px;">
                    <router-link :to="{name: 'profileBooks', params: {id: host.id, status: 3 }}">
                        <button class="text-dark link-underline link-underline-opacity-0">
                            <i class="fa-solid fa-circle-chevron-left fa-2x"></i>
                            <p>+{{ host.already_read_more_count }}</p>
                        </button>
                    </router-link> 
                </div>
            </div>
            <p class="title mt-5"><span>  می خواهم بخوانم  </span></p>
            <div class="lists-body want-to-read row flex-row-reverse align-items-center mx-1 p-1 rounded-1">
                <div class="col-auto p-1 mx-3" v-for="b in host.want_to_read" :key="b.id" >
                    <router-link :to="{name:'book', params:{id: b.id}}" 
                    class="router-links d-flex flex-column m-1 p-2 align-items-center text-center">
                        <img :src="b.image" style="height: 150px; max-width:100px" alt="">
                        <p class="mt-2 book-title"> {{ b.name }}</p>
                    </router-link>
                </div>
                <div v-if="host.has_more_want_to_read" class="col-auto" style="margin-right: 1000px;">
                    <router-link :to="{name: 'profileBooks', params: {id: host.id, status: 1 }}">
                        <button class="text-dark link-underline link-underline-opacity-0">
                            <i class="fa-solid fa-circle-chevron-left fa-2x"></i>
                            <p>+{{ host.already_read_more_count }}</p>
                        </button>
                    </router-link> 
                </div>
            </div>
        </div>
        <div v-if="host" class="friends-shelves">
            <p v-if="host.is_private" class="mt-5 mb-0 w-25" data-bs-toggle="modal" data-bs-target="#createShelf">
                <a href="#" class="link-dark text-center" style="text-decoration:none;">ایجاد قفسه جدید</a> 
            </p>
            <p :class="{'mt-5' : !host.is_private}" class="title"><span> قفسه های من</span></p>
            <div class="modal fade" id="createShelf" tabindex="-1" aria-labelledby="createShelfLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5 w-100 text-center" id="createShelfLabel">ایجاد قفسه جدید</h1>
                            <button id="create_shelf_close" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-end">
                            <div class="alert alert-danger text-start py-1" style="font-size:14px"
                             v-if="hasError">
                                    <div v-for="e in errors" :key="e">- {{ e[0] }}</div>
                            </div>
                            <div class="alert alert-success py-1" style="font-size:14px"
                             v-if="success">{{ message }}</div>
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
            <div v-if="host" class="lists-body row flex-row-reverse align-items-center mx-1 p-1 rounded-1">
                <div class="col-11 g-0 d-flex flex-row-reverse p-1">
                        <div v-for="s in host.shelves" :key="s.id" class="col-4 me-3">
                            <div class="shelf-title row m-1 bg-dark rounded-top text-white bg-gradient">
                                <p class="text-center  mx-auto fw-bold m-1"> {{ s.name }} </p>
                            </div>
                            <div class="shelf-books row flex-row-reverse justify-content-start align-items-center bg-light mx-1
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
                    <div v-if="host.has_more_shelves" class="col-auto me-auto">
                        <router-link :to="{name: 'shelfList', params: {id: host.id}}"
                        class="text-dark text-center" style="text-decoration:none" href="#">
                            <i class="fa-solid fa-circle-chevron-left fa-2x"></i>
                            <p>+{{ shelves_more_count }}</p>
                        </router-link>
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
    },
    data() {
        return {
            host: null,

            // create shelf
            shelfName: null,
            shelfDescription: null,
            hasError: false,
            errors: [],
            success: false,
            message: null,
            shelves_more_count: null,

            // friendship options
            friends: [],
            searchIsActive: false,
            searchInput: null,
            timeoutId: null,
            searchedUsers: [],
                // host page
            friendship: null,

            // edit profile
            file: '',
            email: null,
            username: null,
            fname: null,
            lname: null,
            password: null,
            confirmPassword: null,
            hasError2: false,
            errors2: [],
            success2: false,
            message2: null,
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

                axios.get(`/api/friendship/${this.$route.params.id}`)
                .then((response) => {
                    this.friendship = response.data.data
                })
            }

            if (this.host.has_more_shelves) {
                console.log(this.host.shelves_more_count)
                this.shelves_more_count = this.host.shelves_more_count
            }

            this.email = this.host.email
            this.username = this.host.username
            this.fname = this.host.fname
            this.lname = this.host.lname
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

        async onFileChange(event) {
            this.file = event.target.files[0]
        },

        async updateProfile() {
            this.hasError2 = false
            this.errors2 = []
            this.success2 = false
            this.message2 = null

            
            let fd = new FormData()

            fd.append('fname', this.fname ?? '')
            fd.append('lname', this.lname ?? '')
            fd.append('email', this.email ?? '')
            fd.append('username', this.username ?? '')
            fd.append('password', this.password ?? '')
            fd.append('confirmPassword', this.confirmPassword ?? '')
            fd.append('file', this.file)
            fd.append('_method', 'POST')

            await axios.post('/api/update-profile', fd,
                {
                    headers: {
                    'Content-Type': `multipart/form-data; boundary=${fd._boundary}`
                    }
                } 
            ).then(response => {
                this.success2 = true;
                this.message2 = response.data.message
            }).catch (error => {
                if (error.response && 
                    error.response.status && 
                    error.response.status == 422) {
                        this.hasError2 = true
                        console.log(error.response.data)
                        this.errors2 = error.response.data.errors
                }
            })
            
        },

        async removeFriend(user_id) {
            await axios.get(`/api/reject-or-remove-friend/${user_id}`)
            .then(() => {
                if (this.host.isPrivate) {
                    this.friends = this.friends.filter(item => item.id !== user_id)
                    this.item = this.searchedUsers.find(item => item.id === user_id);
                    this.item.status = 0
                } else {
                    this.friendship.status = 0
                } 
            })
        },

        async sendFriendRequest(user_id) {
            await axios.get(`/api/accept-or-add-friend/${user_id}`)
            .then(() => {
                if (this.host.isPrivate) {
                    this.item = this.searchedUsers.find(item => item.id === user_id);
                    this.item.status = 1
                } else {
                    this.friendship.status = 1
                }   
            })
        },

        async cancelFriendRequest(user_id) {
            await axios.get(`/api/reject-or-remove-friend/${user_id}`)
            .then(() => {
                if (this.host.isPrivate) {
                    this.item = this.searchedUsers.find(item => item.id === user_id);
                    this.item.status = 0
                } else {
                    this.friendship.status = 0
                }
            })
        },

        async acceptFriendRequest(user_id) {
            await axios.get(`/api/accept-or-add-friend/${user_id}`)
            .then(() => {
                if (this.host.isPrivate) {
                    this.item = this.searchedUsers.find(item => item.id === user_id);
                    this.item.status = 3
                    this.friends.push(this.item)
                } else {
                    this.friendship.status = 3
                }
                
            })
        },

        async rejectFriendRequest(user_id) {
            await axios.get(`/api/reject-or-remove-friend/${user_id}`)
            .then(() => {
                if (this.host.isPrivate) {
                    this.item = this.searchedUsers.find(item => item.id === user_id);
                    this.item.status = 0
                } else {
                    this.friendship.status = 0
                }
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
            }, 800);
        },
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
.shelf-book-img {
max-width: 80px;
max-height: 120px;
}
.book-title {
    direction: rtl;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 120px;
}
.book-img {
width: 110px;
height: 160px;
}
.title {

direction: rtl;
border-bottom: 1.5px solid rgb(232, 232, 232); 
line-height: 0.1em;
font-family: hamishe;
} 
.title span { 
    background:#fff; 
    padding-left: 20px;
    padding-right: 20px;
    margin-right: 30px;
    font-weight:bold;
    font-size: large;
}
.title-span {
    background:#0e0e0e;
    padding-left: 20px;
    padding-right: 20px;
    margin-right: 30px;

    font-size: medium !important;
    font-weight: normal !important;
}
.lists-body {
    background: #f4f4f4;
    height: 250px;
}
.router-links {
    color: black;
    text-decoration: none;
}

</style>