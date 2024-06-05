<template>
    <PageHeader></PageHeader>
    <div class="container-fluid body-class">
        <p class="title mx-1" style="margin-top: 110px;"><span>کتاب های محبوب</span></p>
        <div class="popular-books row flex-row-reverse mx-1 p-1 rounded-1" style="background-color: #f4f4f4;">
            <div v-for="p in popular" :key="p.id"  class="col-auto p-1 mx-3">
                <router-link :to="{name: 'book', params: {id: p.id}}" class="link-underline-opacity-0 link-dark">
                    <img class="book-img d-block border mx-auto" :src="p.image" alt="">
                    <p class="text-center p-1">{{ p.name }}</p>
                </router-link> 
            </div> 
        </div>

        <p class="title mt-5"><span>کتاب های فراگیر</span></p>
        <div class="trending-books row flex-row-reverse mx-1 p-1 rounded-1" style="background-color: #f4f4f4;">
            <div v-for="t in trending" :key="t.id"   class="col-auto p-1 mx-3">
                <router-link :to="{name: 'book', params: {id: t.id}}"  class="link-underline link-underline-opacity-0 link-dark">
                    <img class="book-img d-block mx-auto" :src="t.image" alt="">
                    <p class="text-center p-1">{{ t.name }}</p>
                </router-link> 
            </div> 
        </div>

        <p v-if="user" class="title mt-5"><span> فعالیت  دوستان</span></p>
        <div class="friends-activity row flex-row-reverse mx-1 p-1 rounded-1" style="background-color: #f4f4f4;">
            <div v-for="a in activities" :key="a.id" class="col-auto p-1 mx-3">
                <div class="row flex-row-reverse">
                    <div v-for="f in a.preview_friends" :key="f.id" class="col-8 ">
                        <router-link :to="{name: 'book', params: {id: a.id}}" class="link-underline link-underline-opacity-0 link-dark">
                            <img :src="a.image" class="book-img d-block mx-auto" alt="...">
                            <p class="text-center p-1 mx-auto">{{ a.name }}</p>
                        </router-link>
                    </div>
                    <div v-for="f in a.preview_friends" :key="f.id" class="col-4">
                        <div class="d-flex float-end">
                            <a href="#" >
                                <img :src="f.image" class="user-profile mt-1">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p v-if="user" class="title mt-5"><span> قفسه های  دوستان</span></p>
        <div class="friends-shelves row flex-row-reverse mx-1 p-1 rounded-1" style="background-color: #f4f4f4;">
            <div v-if="user" class="p-1">
                <div v-for="s in shelves" :key="s.id" class="col-4 float-end m-1">
                    <div class="shelf-title row m-1 bg-dark rounded-top text-white bg-gradient">
                        <p class="text-center  mx-auto fw-bold p-1 m-1">{{ s.name }}</p>
                    </div>
                    <div class="shelf-books row justify-content-end align-items-center bg-light rounded-1 mx-auto m-1 p-1" >
                        <router-link :to="{name:'shelf', params: {id: s.id}}" 
                        class="col-auto p-2" style="margin-right: 160px">
                            <i class="fa-solid fa-circle-chevron-left fa-xl text-dark"></i>
                        </router-link>
                        <div v-for="b in s.books" :key="b.id" class="col-auto p-2">
                            <img  class="shelf-book-img" :src="b.image" :alt="b.name">
                        </div>
                    </div>
                    <div class="shelf-footer row align-items-center py-2 g-0 my-2">
                        <div class="col-auto ps-2">
                            <img :src="s.user.image" :alt="s.user.name" class="user-profile">
                        </div>
                        <div class="col-auto ps-2">
                            <router-link :to="{name: 'profile', params: {id: s.user.id}}" class="link-dark">
                                {{ s.user.username }}
                            </router-link>
                        </div>
                    </div>
                </div>
                <img src="storage/sources//icons8-forward-52.png" style="width: 30px; height:30px; margin-top:100px; margin-right:15px;" alt="">
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
            this.trending = response.data.data.trending;
            this.popular = response.data.data.popular;
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


</style>
