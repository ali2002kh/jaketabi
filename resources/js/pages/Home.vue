<template>
    <PageHeader></PageHeader>
    <div class="container-fluid body-class">
        <p class="title mx-1" style="margin-top: 110px;"><span>کتاب های محبوب</span></p>
        <div class="popular-books row flex-row-reverse mx-1 p-1 rounded-1" style="background-color: #f4f4f4;">
            <div v-for="p in popular" :key="p.id"  class="col-auto p-1 mx-3">
                <router-link :to="{name: 'book', params: {id: p.id}}" class="router-links">
                    <img class="book-img d-block border mx-auto" :src="p.image" alt="">
                    <p class="text-center p-1">{{ p.name }}</p>
                </router-link> 
            </div> 
            <nav aria-label="Page navigation example">
                <ul class="pagination ms-3">
                    <li class="page-item"><button v-if="paginator['popular']['hasPrev']" @click.prevent="prev('popular')" class="page-link">Previous</button></li>
                    <li class="page-item"><button v-if="paginator['popular']['hasNext']" @click.prevent="next('popular')" class="page-link">Next</button></li>
                </ul>
            </nav>
        </div>

        <p class="title mt-5"><span>کتاب های فراگیر</span></p>
        <div class="trending-books row flex-row-reverse mx-1 p-1 rounded-1" style="background-color: #f4f4f4;">
            <div v-for="t in trending" :key="t.id"   class="col-auto p-1 mx-3">
                <router-link :to="{name: 'book', params: {id: t.id}}"  class="router-links">
                    <img class="book-img d-block mx-auto" :src="t.image" alt="">
                    <p class="text-center p-1">{{ t.name }}</p>
                </router-link> 
            </div> 
            <nav aria-label="Page navigation example">
                <ul class="pagination ms-3">
                    <li class="page-item"><button v-if="paginator['trending']['hasPrev']" @click.prevent="prev('trending')" class="page-link">Previous</button></li>
                    <li class="page-item"><button v-if="paginator['trending']['hasNext']" @click.prevent="next('trending')" class="page-link">Next</button></li>
                </ul>
            </nav>
        </div>

        <p v-if="user" class="title mt-5"><span> فعالیت  دوستان</span></p>
        <div v-if="user" class="friends-activity row flex-row-reverse mx-1 p-1 rounded-1" style="background-color: #f4f4f4;">
            <div v-for="a in activities" :key="a.id" class="col-auto p-1 mx-3 ">
                <div class="row flex-row-reverse ">
                    <div class="col">
                        <router-link :to="{name: 'book', params: {id: a.id}}" class="router-links">
                            <img :src="a.image" class="book-img d-block mx-auto" alt="...">
                            <p class="text-center p-1 mx-auto">{{ a.name }}</p>
                        </router-link>
                    </div>
                    <div class="col pe-0" data-bs-toggle="modal" :data-bs-target="'#friendsActivitiesModal' + a.id">
                        <div class="row mb-1" v-for="f in a.preview_friends" :key="f.id">
                            <div>
                                <img :src="f.image" class="user-profile mt-1">  
                            </div>
                            <!-- <router-link :to="{name: 'profile', params: {id: f.id}}" class="router-links">
                                                          
                            </router-link> -->
                        </div>
                    </div>
                </div>
            </div>
            <nav aria-label="Page navigation example">
                <ul class="pagination ms-3">
                    <li class="page-item"><button v-if="paginator['activities']['hasPrev']" @click.prevent="prev('activities')" class="page-link">Previous</button></li>
                    <li class="page-item"><button v-if="paginator['activities']['hasNext']" @click.prevent="next('activities')" class="page-link">Next</button></li>
                </ul>
            </nav>
            <div v-for="a in activities" :key="a.id">
                <div class="modal fade" :id="'friendsActivitiesModal' + a.id" tabindex="-1" role="dialog"
                aria-labelledby="friendsActivitiesModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title w-100 text-center" id="friendsActivitiesModalLabel">فعالیت دوستان</h5>
                                <button :id="'close_modal' + a.id" type="button" class="btn-close"
                                    data-bs-dismiss="modal" aria-label="Close"></button>                                
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col">
                                        <p v-if="a.friends_who_already_read_this_book" class="text-center w-100 bg-light p-1 border rounded-1 mt-2">
                                                خوانده اند</p>
                                        <div class="row px-1" v-for="f in a.friends_who_already_read_this_book" :key="f.id">
                                            <div class="d-flex flex-row-reverse align-items-center"
                                            @click.prevent="showProfile(f.id , a.id)">
                                                <p><img :src="f.image" class="user-profile mt-1">  </p> 
                                                <p class="me-2">{{f.username}}</p>                     
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <p v-if="a.friends_who_are_reading_this_book" class="text-center w-100 bg-light p-1 border rounded-1 mt-2">
                                            دارند می خوانند </p>
                                        <div class="row px-1" v-for="f in a.friends_who_are_reading_this_book" :key="f.id">
                                            <div class="d-flex flex-row-reverse align-items-center"
                                            @click.prevent="showProfile(f.id , a.id)">
                                                <p><img :src="f.image" class="user-profile mt-1">  </p> 
                                                <p class="me-2">{{f.username}}</p>                     
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p v-if="user" class="title mt-5"><span> قفسه های  دوستان</span></p>
        <div v-if="user" class="friends-shelves row flex-row-reverse align-items-center mx-1 p-1 rounded-1 mb-2"
        style="background: #f4f4f4; height: 250px;">
            <div class="pagination col-auto me-0 px-0" aria-label="Page navigation example">
                <button v-if="paginator['shelves']['hasPrev']" @click.prevent="prev('shelves')" class="btn btn-link ps-1 m-0">
                    <i class="fa-solid fa-circle-chevron-right text-dark fa-2x"></i>
                </button>
                <button v-else class="btn btn-link ps-1 m-0" disabled><i class="fa-solid fa-circle-chevron-right text-dark fa-2x"></i></button>
            </div>
            <div class="col-11 g-0 m-0 d-flex flex-row-reverse p-0">
                <div v-for="s in shelves" :key="s.id" class="col-4 p-1">
                    <div class="shelf-title row m-1 bg-dark rounded-top text-white bg-gradient">
                        <p class="text-center  mx-auto fw-bold m-1">{{s.name}}</p>    
                    </div>
                    <div class="shelf-books row d-flex flex-row-reverse justify-content-start align-items-center
                     bg-light mx-1 p-1 border-start border-end border-bottom rounded-bottom" 
                     style="min-height:130px;" >
                        <div v-for="b in s.books" :key="b.id" class="col-auto mx-0">
                            <img  class="shelf-book-img" :src="b.image" :alt="b.name">
                        </div>
                        <router-link :to="{name:'shelf', params: {id: s.id}}" 
                        class="col-auto me-auto p-2">
                            <i class="fa-solid fa-angle-left fa-xl text-dark"></i>
                        </router-link>
                    </div>
                    <div class="shelf-footer row align-items-center py-2 g-0 my-2">
                        <div class="col-auto ps-2">
                            <img :src="s.user.image" :alt="s.user.name" class="user-profile">
                        </div>
                        <div class="col-auto ps-2">
                            <router-link :to="{name: 'profile', params: {id: s.user.id}}" class="router-links">
                                {{ s.user.username }}
                            </router-link>
                        </div>
                    </div>
                </div>
                <!-- <nav aria-label="Page navigation example">
                    <ul class="pagination ms-3">
                        <li class="page-item"><button v-if="paginator['shelves']['hasPrev']" @click.prevent="prev('shelves')" class="page-link">Previous</button></li>
                        <li class="page-item"><button v-if="paginator['shelves']['hasNext']" @click.prevent="next('shelves')" class="page-link">Next</button></li>
                    </ul>
                </nav> -->
            </div>
            <div class="pagination col-auto p-0 me-auto" aria-label="Page navigation example">
                <button v-if="paginator['shelves']['hasNext']" @click.prevent="next('shelves')" class="btn btn-link pe-1 m-0" >
                    <i class="fa-solid fa-circle-chevron-left fa-2x text-dark"></i>
                </button>
                <button v-else class="next-btn btn btn-link pe-1 m-0" disabled><i class="fa-solid fa-circle-chevron-left text-dark fa-2x"></i></button>
            </div>
        </div>
    </div>
</template>

<script>

import { mapState } from 'vuex';
import PageHeader from "../layouts/PageHeader"
import { result } from 'lodash';
// import PageFooter from "../layouts/PageFooter"

export default {
    components: {
        PageHeader,
        // PageFooter,
    },
    data() {
        return {
            popular: null,
            trending: null,
            activities: null,
            shelves: null,

            paginator: {
                'popular': {
                    'page': 1,
                    'pageSize': 2,
                    'all': null,
                    'hasNext': true,
                    'hasPrev': false,
                    'pageCount': 4,
                },
                'trending': {
                    'page': 1,
                    'pageSize': 2,
                    'all': null,
                    'hasNext': true,
                    'hasPrev': false,
                    'pageCount': 4,
                },
                'activities': {
                    'page': 1,
                    'pageSize': 2,
                    'all': null,
                    'hasNext': true,
                    'hasPrev': false,
                    'pageCount': 4,
                },
                'shelves': {
                    'page': 1,
                    'pageSize': 3,
                    'all': null,
                    'hasNext': true,
                    'hasPrev': false,
                    'pageCount': 4,
                },
            }
        } 
    },
    created() {
        axios.get('/api/trending-popular')
        .then(response => {
            console.log(response.data.data)
            this.paginator['popular']['all'] = response.data.data.popular;
            this.paginator['trending']['all'] = response.data.data.trending;

            this.paginate('popular')
            this.paginate('trending')
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
            if (this.user) {
                axios.get('/api/friends-activities')
                .then(response => {
                    console.log(response.data.data)
                    this.paginator['activities']['all'] = response.data.data;
                    this.paginate('activities')                 
                });

                axios.get('/api/friends-shelves')
                .then(response => {
                    console.log(response.data.data)
                    this.paginator['shelves']['all'] = response.data.data;
                    this.paginate('shelves')   
                });
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
        async showProfile (user_id , modal_id) {
            document.getElementById('close_modal' + modal_id).click()
            this.$router.replace({ name: 'profile', params: { id: user_id }});
        },

        async paginate (title) {
            result = this.paginator[title]['all'].slice(
                (this.paginator[title]['page'] - 1) * this.paginator[title]['pageSize'], 
                (this.paginator[title]['page'] - 1) * this.paginator[title]['pageSize'] + this.paginator[title]['pageSize']
            )  
            if (title == 'popular') {
                this.popular = result
            } else if (title == 'trending') {
                this.trending = result
            } else if (title == 'activities') {
                this.activities = result
            } else if (title == 'shelves') {
                this.shelves = result
            }
        },

        async next (title) {
            this.paginator[title]['page']++
            this.paginate(title)
            this.paginator[title]['hasPrev'] = true
            if (this.paginator[title]['pageCount'] <= this.paginator[title]['page']) {
                this.paginator[title]['hasNext'] = false
            }
        },

        async prev (title) {
            this.paginator[title]['page']--
            this.paginate(title)
            this.paginator[title]['hasNext'] = true
            if (1 >= this.paginator[title]['page']) {
                this.paginator[title]['hasPrev'] = false
            }
        },
    },
}
</script>


<style scoped>
    .body-class {
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    } 
    .book-img {
            max-width: 120px;
            max-height: 200px;
    }
    
    .shelf-book-img {
        max-width: 80px;
        max-height: 120px;
    }
    .title {
        width: 100%; 
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

    .user-profile {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

.router-links {
    color: black;
    text-decoration: none;
}
.lists-body {
    background: #f4f4f4; 
    height: 250px;
}
.next-btn {
    transform: translate(100%, 0);
}
</style>
