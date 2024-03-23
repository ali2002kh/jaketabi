<template>
    <page-header></page-header>
    <p class="title" style="margin-top: 110px;"><span>کتاب های محبوب</span></p>
        <div class="multiple-card-slider">
            <div id="carouselExampleControls" class="carousel">
                <div class="carousel-inner">
                    <router-link v-for="p in popular" :key="p.id" :to="{name: 'book', params: {id: p.id}}" class="carousel-item">
                        <div class="card">
                            <div class="img-wrapper">
                                <img :src="p.image" class="d-block w-100" alt="..."> 
                            </div>
                            <div class="card-body">
                                
                                <p class="card-title">{{ p.name }}</p>
                            </div>
                        </div>
                    </router-link>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <p class="title" style="margin-top: 35px;"><span>کتاب های فراگیر</span></p>
        <div class="multiple-card-slider">
            <div id="carouselExampleControls2" class="carousel">

                <div class="carousel-inner">
                    <router-link v-for="t in trending" :key="t.id" :to="{name: 'book', params: {id: t.id}}" class="carousel-item">
                        <div class="card">
                            <div class="img-wrapper">
                                <img :src="t.image" class="d-block w-100" alt="..."> 
                            </div>
                            <div class="card-body">
                                
                                <p class="card-title">{{ t.name }}</p>
                            </div>
                        </div>
                    </router-link>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls2" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls2" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <p class="title" style="margin-top: 35px;"><span> فعالیت  دوستان</span></p>
        <div class="card-slider">
            <div id="carouselExampleControls3" class="carousel">
                <div class="carousel-inner">


                    <div v-for="a in activities" :key="a.id" class="carousel-item-activity active">
                        <div class="activity-card float-end">
                            <ul class="user-image">
                                <a href="#" class="profiles-wrapper">
                                    <li v-for="f in a.preview_friends" :key="f.id"><img :src="f.image"></li>
                                </a>
                            </ul>
                            <div class="img-wrapper">
                                <img :src="a.image" class="d-block h-75" alt="...">
                                <div class="card-body">
                                    <a href="#" ><p class="card-title">{{ a.name }}</p></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls2" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls2" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <p class="title" style="margin-top: 35px;"><span> قفسه های  دوستان</span></p>
        <!-- <script src="js/index.js"></script> -->
</template>

<script>

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
        } 
    },
    created() {
        axios.get('/api/popular')
        .then(response => {
            console.log(response.data.data)
            this.popular = response.data.data;
        });

        axios.get('/api/trending')
        .then(response => {
            console.log(response.data.data)
            this.trending = response.data.data;
        });

        axios.get('/api/friends-activities')
        .then(response => {
            console.log(response.data.data)
            this.activities = response.data.data;
        });
    },
}
</script>

