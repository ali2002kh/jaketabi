<template>
    <PageHeader></PageHeader>
    <div class="container-fluid body-class">
        <p class="title mx-1" style="margin-top: 110px;"><span>کتاب های محبوب</span></p>
        <swiper class="swiper1 popular-books row flex-row-reverse p-1 rounded-1 mb-5" style="background-color: #f4f4f4; align-items:center;"
            dir="rtl"
            :modules="modules"
            :slides-per-view="5"
            :space-between="20"
            navigation
            :breakpoints="{
                '320': {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                '480': {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                '640': {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
                '768': {
                    slidesPerView: 5,
                    spaceBetween: 40,
                },
                '1024': {
                    slidesPerView: 7,
                    spaceBetween: 50,
                },
            }"
            @swiper="onSwiper"
            @slideChange="onSlideChange"
        >
            <swiper-slide v-for="p in popular" :key="p.id" class="p-1">
                <router-link :to="{name: 'book', params: {id: p.id}}" class="router-links">
                    <img class="book-img d-block border mx-auto" :src="p.image" alt="">
                    <p class="book-title mx-auto text-center p-1">{{ p.name }}</p>
                </router-link> 
            </swiper-slide>
        </swiper>


        <p class="title"><span>کتاب های فراگیر</span></p>
        <swiper class="swiper2 trending-books row flex-row-reverse mx-1 p-1 rounded-1 mb-5" style="background-color: #f4f4f4; align-items:center;"
            dir="rtl"
            :modules="modules"
            :slides-per-view="5"
            :space-between="20"
            navigation
            :breakpoints="{
                '320': {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                '480': {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                '640': {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
                '768': {
                    slidesPerView: 5,
                    spaceBetween: 40,
                },
                '1024': {
                    slidesPerView: 7,
                    spaceBetween: 50,
                },
            }"
            @swiper="onSwiper"
            @slideChange="onSlideChange"
        >
            <swiper-slide v-for="t in trending" :key="t.id" class="p-1">
                <router-link :to="{name: 'book', params: {id: t.id}}"  class="router-links">
                    <img class="book-img d-block mx-auto" :src="t.image" alt="">
                    <p class="book-title mx-auto text-center p-1">{{ t.name }}</p>
                </router-link> 
            </swiper-slide>
        </swiper>

        <p v-if="user" class="title"><span> فعالیت  دوستان</span></p>
        <swiper v-if="user" class="swiper3 friends-activity row flex-row-reverse  mx-1 p-1 rounded-1 mb-5" 
            style="background-color: #f4f4f4; align-items:center;"
            dir="rtl"
            :modules="modules"
            :slides-per-view="5"
            :space-between="20"
            navigation
            :breakpoints="{
                '320': {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    slidesOffsetBefore: -70,
                    slidesOffsetAfter: 50,
                },
                '450': {
                    slidesPerView: 2,
                    spaceBetween: 20,
                    slidesOffsetBefore: -50,
                },
                '680': {
                    slidesPerView: 3,
                    spaceBetween: 20,
                    slidesOffsetBefore: -30,
                },
                '840': {
                    slidesPerView: 4,
                    spaceBetween: 40,
                },
                '1080': {
                    slidesPerView: 5,
                    spaceBetween: 50,
                    slidesOffsetAfter: 0,
                },
            }"
            @swiper="onSwiper"
            @slideChange="onSlideChange"
        >
            <swiper-slide v-for="a in activities" :key="a.id" class="p-1">
                <div class="row flex-row-reverse py-auto">
                    <div class="col-auto g-0 pe-2" data-bs-toggle="modal" :data-bs-target="'#friendsActivitiesModal' + a.id">
                        <div class="row mb-1 ms-0" v-for="f in a.preview_friends" :key="f.id">
                            <div>
                                <img :src="f.image" class="user-profile mt-1">  
                            </div>
                        </div>
                    </div>
                    <div class="col-auto g-0">
                        <router-link :to="{name: 'book', params: {id: a.id}}" class="router-links">
                            <img :src="a.image" class="book-img d-block mx-auto" alt="...">
                            <p class="book-title mx-auto text-center py-1 ">{{ a.name }}</p>
                        </router-link>
                    </div>
                </div> 
            </swiper-slide>
        </swiper>

        <!-- friends activities modal  -->
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

        <p v-if="user" class="title"><span> قفسه های  دوستان</span></p>
        <swiper v-if="user" class="swiper4 friends-shelves row flex-row-reverse align-items-center mx-1 p-1 rounded-1 mb-2" style="background-color: #f4f4f4; align-items:center;"
            dir="rtl"
            :modules="modules"
            :space-between="0"
            navigation
            :breakpoints="{
                '320': {
                    slidesPerView: 'auto',
                    centeredSlides: true,
                },

                '800': {
                    slidesPerView: 2,
                    slidesPerGroup: 2,
                    slidesPerGroupSkip: 2,
                },
                '1200': {
                    slidesPerView: 3.1,
                    slidesPerGroup: 3,
                    slidesPerGroupSkip: 3,
                    spaceBetween: 10,
                },
            }"
            @swiper="onSwiper"
            @slideChange="onSlideChange"
        >
            <swiper-slide v-for="s in shelves" :key="s.id" class="p-1 shelf">
                <div class="shelf-title row m-1 bg-dark rounded-top text-white bg-gradient">
                    <p class="text-center  mx-auto fw-bold m-1">{{s.name}}</p>    
                </div>
                <div class="shelf-books row d-flex flex-row-reverse justify-content-start align-items-center
                    bg-light m-1 border-start border-end border-bottom rounded-bottom" 
                    style="min-height:130px;" >
                    <router-link :to="{name:'shelf', params: {id: s.id}}" 
                    class="col-auto me-auto p-2">
                        <i class="fa-solid fa-angle-left fa-xl text-dark"></i>
                    </router-link>
                    <div v-for="b in s.books" :key="b.id" class="col-auto mx-0">
                        <img  class="shelf-book-img" :src="b.image" :alt="b.name">
                    </div>
                </div>
                <div class="shelf-footer row float-start align-items-center py-2 g-0 my-2">
                    <div class="col-auto ps-2">
                        <router-link :to="{name: 'profile', params: {id: s.user.id}}" class="router-links">
                            {{ s.user.username }}
                        </router-link>
                    </div>
                    <div class="col-auto ps-2">
                        <img :src="s.user.image" :alt="s.user.name" class="user-profile">
                    </div>
                </div> 
            </swiper-slide>
        </swiper>
        
    </div>
</template>

<script>

import { mapState } from 'vuex';
import PageHeader from "../layouts/PageHeader"
import { result } from 'lodash';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
// import PageFooter from "../layouts/PageFooter"

export default {
    components: {
        PageHeader,
        Swiper,
        SwiperSlide,
        // PageFooter,
    },
    setup() {
      const onSwiper = (swiper) => {
        console.log(swiper);
      };
      const onSlideChange = () => {
        console.log('slide change');
      };
      return {
        onSwiper,
        onSlideChange,
        modules: [Navigation, Pagination],
      };
    },
    data() {
        return {
            popular: null,
            trending: null,
            activities: null,
            shelves: null,

            // paginator: {
            //     'popular': {
            //         'page': 1,
            //         'pageSize': 6,
            //         'all': null,
            //         'hasNext': true,
            //         'hasPrev': false,
            //         'pageCount': 3,
            //     },
            //     'trending': {
            //         'page': 1,
            //         'pageSize': 6,
            //         'all': null,
            //         'hasNext': true,
            //         'hasPrev': false,
            //         'pageCount': 3,
            //     },
            //     'activities': {
            //         'page': 1,
            //         'pageSize': 4,
            //         'all': null,
            //         'hasNext': true,
            //         'hasPrev': false,
            //         'pageCount': 4,
            //     },
            //     'shelves': {
            //         'page': 1,
            //         'pageSize': 3,
            //         'all': null,
            //         'hasNext': true,
            //         'hasPrev': false,
            //         'pageCount': 4,
            //     },
            // }
        } 
    },
    created() {
        axios.get('/api/trending-popular')
        .then(response => {
            console.log(response.data.data)
            // this.paginator['popular']['all'] = response.data.data.popular;
            this.popular = response.data.data.popular;
            // this.paginator['trending']['all'] = response.data.data.trending;
            this.trending = response.data.data.trending;

            // this.paginate('popular')
            // this.paginate('trending')
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
                    // this.paginator['activities']['all'] = response.data.data;
                    this.activities = response.data.data;
                    this.paginate('activities')                 
                });

                axios.get('/api/friends-shelves')
                .then(response => {
                    console.log(response.data.data)
                    // this.paginator['shelves']['all'] = response.data.data;
                    this.shelves = response.data.data;
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
            width: 110px;
            height: 160px;
    }

    .book-title {
        direction: rtl;
        white-space: initial;
        max-width: 120px;
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
.swiper-slide {
    margin-left: 20px !important;
    margin-right: 0 !important;
}
.shelf {
    width:362px !important;
}
</style>
