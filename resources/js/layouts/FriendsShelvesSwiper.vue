<template>
    <swiper class="row flex-row-reverse align-items-center mx-1 p-1 rounded-1 mb-2" style="background-color: #f4f4f4; align-items:center;"
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
            <single-shelf-item :shelf="s"></single-shelf-item>
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
</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import SingleShelfItem from './SingleShelfItem.vue';

export default {

    props: {
        shelves: {
            type: Array,
            required: true
        }
    },

    components: {
        Swiper,
        SwiperSlide,
        SingleShelfItem
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

.router-links {
    color: black;
    text-decoration: none;
}

.user-profile {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.shelf {
    width:362px !important;
}

.swiper-slide {
    margin-left: 20px !important;
    margin-right: 0 !important;
}

</style>


