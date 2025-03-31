<template>
    <page-header :user="user"></page-header>
    <div class="container-fluid body-class">

        <p class="title mx-1" style="margin-top: 110px;"><span>کتاب های محبوب</span></p>
        <book-swiper :books="popular"></book-swiper>

        <p class="title"><span>کتاب های فراگیر</span></p>
        <book-swiper :books="trending"></book-swiper>

        <p class="title"><span> فعالیت  دوستان</span></p>
        <activities-swiper-and-modal
            v-if="user" :activities="activities">
        </activities-swiper-and-modal>

        <p class="title"><span> قفسه های  دوستان</span></p>
        <shelves-swiper
            v-if="user" :shelves="shelves" :incluseUserProfile=true>
        </shelves-swiper>

    </div>
</template>

<script>

import { mapState } from 'vuex';
import PageHeader from "../layouts/PageHeader"
import SingleBookItem from "../layouts/SingleBookItem"
import BookSwiper from "../layouts/BookSwiper"
import ActivitiesSwiperAndModal from '../layouts/ActivitiesSwiperAndModal';
import ShelvesSwiper from '../layouts/ShelvesSwiper';
// import PageFooter from "../layouts/PageFooter"

export default {
    components: {
        PageHeader,
        SingleBookItem,
        BookSwiper,
        ActivitiesSwiperAndModal,
        ShelvesSwiper
        // PageFooter,
    },

    data() {
        return {
            popular: null,
            trending: null,
            activities: null,
            shelves: null,
        }
    },
    created() {
        axios.get('/api/trending-popular')
        .then(response => {
            console.log(response.data.data)
            this.popular = response.data.data.popular;
            this.trending = response.data.data.trending;
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
                    this.activities = response.data.data;
                });

                axios.get('/api/friends-shelves')
                .then(response => {
                    console.log(response.data.data)
                    this.shelves = response.data.data;
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
}
</script>


<style scoped>
.body-class {
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}

<<<<<<< HEAD
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
=======
.title {
    width: 100%;
    direction: rtl;
    border-bottom: 1.5px solid rgb(232, 232, 232);
    line-height: 0.1em;
    font-family: hamishe;
}
>>>>>>> cdfcd59b3d34a53368ef6485264ff5c115edf978

.title span {
    background:#fff;
    padding-left: 20px;
    padding-right: 20px;
    margin-right: 30px;
    font-weight:bold;
    font-size: large;
}

</style>
