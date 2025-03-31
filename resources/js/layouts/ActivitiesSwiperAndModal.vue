<template>
    <swiper class="row flex-row-reverse  mx-1 p-1 rounded-1 mb-5"
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
        @slideChange="onSlideChange">

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
                    <single-book-item :book="a"></single-book-item>
                </div>
            </div>
        </swiper-slide>
    </swiper>

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
</template>

<script>

import SingleBookItem from "../layouts/SingleBookItem"
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

export default {

    props: {
        activities: {
            type: Array,
            required: true
        }
    },

    components: {
        SingleBookItem,
        Swiper,
        SwiperSlide,
    },

    methods: {
        async showProfile (user_id , modal_id) {
            document.getElementById('close_modal' + modal_id).click()
            this.$router.replace({ name: 'profile', params: { id: user_id }});
        },
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
}
</script>

<style scoped>

.user-profile {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.swiper-slide {
    margin-left: 20px !important;
    margin-right: 0 !important;
}

</style>


