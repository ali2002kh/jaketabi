<template>
    <page-header></page-header>
    <div class="d-flex flex-row-reverse justify-content-between mt-5">
        <div class="d-flex flex-row-reverse align-items-center mt-5 me-4">
            <div class="ps-3 ">
                <img :src="user.image" style="widows: 60px; height: 60px; border-radius: 100%;" alt="">
            </div>
            <div class="d-flex flex-column align-items-center">
                <p class="fs-6 p-0 m-0 ">{{  user.username }}</p>
                <p class="fs-6 p-0 m-0">{{ user.name }}</p>
            </div>
        </div>
        <div v-if="user.is_private" class="d-flex flex-row-reverse align-items-center mt-5 ms-4">
            <div class="ps-4">
                <router-link :to="{name: 'friends'}" class="text-dark link-underline link-underline-opacity-0">
                    <h5>دوستان</h5>
                </router-link>
            </div>
            <div class="pe-4">
                <a href="#" class="text-dark link-underline link-underline-opacity-0">
                    <h5>ویرایش</h5>
                </a>
            </div>
        </div>
    </div>
    <!-- books lists -->
    
    <div class="card mt-4 m-2">
        <div class="card-header bg-light m-0 p-0 ">
            <p class="float-end pt-1 me-4 fs-5 ">دارم می خوانم</p>
        </div>
        <div class="card-body d-flex flex-row-reverse m-0 p-0">
            <div v-for="b in user.reading" :key="b.id" class="d-flex flex-column m-1 p-2 align-items-center text-center">
                <img :src="b.image" style="height: 150px; max-width:100px" alt="">
                <p class=""> {{ b.name }}</p>
            </div>
            <div v-if="user.has_more_reading" class="d-flex flex-column m-1 p-2 align-items-center text-center justify-content-center me-auto">
                <a href="#">
                    <img src="storage/icons/icons8-forward-button-48.png"  alt="">
                </a>
                <p>+{{ user.reading_more_count }}</p>
            </div>
        </div>
    </div>
    <div class="card mt-4 m-2">
        <div class="card-header bg-light m-0 p-0 ">
            <p class="float-end pt-1 me-4 fs-5"> خوانده ام </p>
        </div>
        <div class="card-body d-flex flex-row-reverse m-0 p-0">
            <div v-for="b in user.already_read" :key="b.id" class="d-flex flex-column m-1 p-2 align-items-center text-center">
                <img :src="b.image" style="height: 150px; max-width:100px" alt="">
                <p class=""> {{ b.name }}</p>
            </div>
            <div v-if="user.has_more_already_read" class="d-flex flex-column m-1 p-2 align-items-center text-center justify-content-center me-auto">
                <a href="#">
                    <img src="storage/icons/icons8-forward-button-48.png"  alt="">
                </a>
                <p>+{{ user.already_read_more_count }}</p>
            </div>
        </div>
    </div>
    <div class="card mt-4 m-2">
        <div class="card-header bg-light m-0 p-0 ">
            <p class="float-end pt-1 me-4 fs-5"> میخواهم بخوانم </p>
        </div>
        <div class="card-body d-flex flex-row-reverse m-0 p-0">
            <div v-for="b in user.want_to_read" :key="b.id" class="d-flex flex-column m-1 p-2 align-items-center text-center">
                <img :src="b.image" style="height: 150px; max-width:100px" alt="">
                <p class=""> {{ b.name }}</p>
            </div>
            <div v-if="user.has_more_want_to_read" class="d-flex flex-column m-1 p-2 align-items-center text-center justify-content-center me-auto">
                <a href="#">
                    <img src="storage/icons/icons8-forward-button-48.png"  alt="">
                </a>
                <p>+{{ user.want_to_read_more_count }}</p>
            </div>
        </div>
    </div>
    <div class="card mt-4 m-2">
        <div class="card-header d-flex flex-row-reverse justify-content-between bg-light m-0 ">
            <div class=" me-4 fs-5 ">قفسه های من</div>
            <a href="#" class="text-dark link-underline link-underline-opacity-0">
                <div class="d-flex align-items-center me-5">
                    <div v-if="user.is_private" class="me-2 fs-6">ایجاد قفسه جدید</div>
                    <img src="storage/icons8-add-48.png" style="width: 30px;" alt="">
                </div>
            </a>
        </div>
        <div class="card-body d-flex flex-row-reverse m-0 p-0">
            <div v-for="s in user.shelves" :key="s.id" class="card m-3">
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
            <div v-if="user.has_more_shelves" class="d-flex flex-column m-1 p-2 align-items-center text-center justify-content-center me-auto">
                <a href="#">
                    <img src="storage/icons/icons8-forward-button-48.png"  alt="">
                </a>
                <p>+{{ user.shelves_more_count }}</p>
            </div>
        </div>
    </div>
</template>

<script>

import { mapGetters, mapState, mapActions } from 'vuex';
import PageHeader from "../layouts/PageHeader"
// import PageFooter from "../layouts/PageFooter"

export default {
    components: {
        PageHeader,
    },
    data() {
        return {
            // user: null,
        } 
    },
    // computed: {
    //     user() {
    //         return this.$store.getters.getUser
    //     }
    // }
    beforeMount(){
        this.$store.dispatch("user/initState");
    },
    computed: {
        // ...mapGetters(["user"]),
        ...mapState({
            user: state => state.user.data,
    }),
        // ...mapActions(['initState'])
    },
}
</script>