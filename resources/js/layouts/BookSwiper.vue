<template>
    <swiper class="row flex-row-reverse p-1 rounded-1 mb-5" style="background-color: #f4f4f4; align-items:center;"
        dir="rtl"
        :modules="modules"
        :slides-per-view="5"
        :space-between="20"
        navigation
        :breakpoints="{
            '320': {
                slidesPerView: sizes[0],
                spaceBetween: 20,
            },
            '480': {
                slidesPerView: sizes[1],
                spaceBetween: 20,
            },
            '640': {
                slidesPerView: sizes[2],
                spaceBetween: 20,
            },
            '768': {
                slidesPerView: sizes[3],
                spaceBetween: 40,
            },
            '1024': {
                slidesPerView: sizes[4],
                spaceBetween: 50,
            },
        }"
        @swiper="onSwiper"
        @slideChange="onSlideChange">
        <swiper-slide v-for="book in books" :key="book.id" class="p-1">
            <single-book-item :book="book"></single-book-item>
        </swiper-slide>
    </swiper>
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
        books: {
            type: Array,
            required: true
        },
        sizes: {
            type: Array,
            required: false,
            default: [1, 3, 4, 5, 7]
        }
    },

    components: {
        SingleBookItem,
        Swiper,
        SwiperSlide,
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

.swiper-slide {
    margin-left: 20px !important;
    margin-right: 0 !important;
}

</style>


