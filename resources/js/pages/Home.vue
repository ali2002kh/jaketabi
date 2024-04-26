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
        <div class="shelves-row">
            <img src="storage/sources//icons8-forward-52-right.png" style="width: 30px; height:30px; margin-top:100px;" alt="">
            <div v-for="s in shelves" :key="s.id" class="shelves-card">
                <p class="shelf-title">{{ s.name }}</p>
                <div class="card-top">
                    <img v-for="b in s.books" :key="b.id" class="shelves-image" :src="b.image" :alt="b.name">
                    <a href="#">
                        <router-link :to="{name:'shelf', params: {id: s.id}}">
                            <img class="shelf-icon" src="storage/sources/icons8-forward-button-48.png" alt="">
                        </router-link>
                    </a>
                </div>
                <div class="card-bottom">
                    <img :src="s.user.image" :alt="s.user.name">
                    <router-link :to="{name: 'profile', params: {id: s.user.id}}">{{ s.user.username }}</router-link>
                </div>
            </div>
            <img src="storage/sources//icons8-forward-52.png" style="width: 30px; height:30px; margin-top:100px; margin-right:15px;" alt="">
        </div>
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
            shelves: null,
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

        axios.get('/api/friends-shelves')
        .then(response => {
            console.log(response.data.data)
            this.shelves = response.data.data;
        });
    },
}
</script>


<style>
    body {
font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif !important;
        /* background-color: black; */
        /* min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center; */
    }
    /* @font-face {
        font-family: hamishe;
        font-style: normal;
        src: url("../storage/fonts/Digi\ Hamishe\ Bold.ttf");
    } */
    .title {
        width: 100%; 
        /* text-align: right;  */
        direction: rtl;
        border-bottom: 1.5px solid rgb(101, 99, 99); 
        line-height: 0.1em;
        margin: 10px 0 20px; 
        /* margin-right: 30px; */
        font-family: hamishe;
    } 
        
    .title span { 
            background:#fff; 
            padding:0 10px; 
            margin-right: 50px;
    }
    a{
        color: black;
        text-decoration: none;
        
    }
    a:hover{
        color:rgb(130, 114, 90);
        /* text-decoration:underline; */
        font-size: 108%;
    }
    
    
    /* .carousel-item{
        height: 280px;
    } */
    .carousel-inner {
    padding: 1em;
    /* margin-top: 120px; */
    
    }
    .shelves-row {
        display: flex;
        flex-direction: row;
        background-color: #f4f4f4;
        height: 250px;
        direction: rtl;
        margin-bottom: 10px;
    }
    .shelf-icon {
        width: 30px;
        height: 30px;
        margin-top: 50px;
    }
    .shelves-card {
        display: flex;
        flex-direction: column;
        height: 230px;
        margin: 20px;
        border-radius: 5px;
        overflow: hidden;
        background-color: rgb(44, 43, 43) !important
    }
    .shelf-title {
        text-align: center;
        font-size: 15px;
        color: white;
        margin: 0px;
        padding: 4px;
        flex-basis: 10%;
    }
    .card-top {
        flex-basis: 70%;
        display: flex;
        flex-direction: row;
        padding: 5px;
        background-color:rgb(229, 229, 229);

    }
    .card-bottom {
        flex-basis: 20%;
        display: flex;
        flex-direction: row;
        direction: ltr;
        padding-top: 5px;
        background-color:#f4f4f4;
    }
    .card-bottom img {
        border-radius: 50%;
        width: 30px;
        height: 30px;
        vertical-align: middle;
        margin-left: 10px;
        margin-bottom: 3px;
        overflow: hidden;
    }
    .card-bottom p {
        font-size: 14px;
        padding: 3px;
    }
    .shelves-image {
        width: 100px;
        padding: 5px;
        margin-left: 5px;
    }
    .activity-card {
        display: flex;
        flex-direction: row;
        margin-right: 10px;
        border: none;
        text-align: center;
        height: 250px;
    }
    .activity-card img {
        height: 210px;
    }
    .user-image {
        list-style: none;
        
        /* padding-left: 0; */
    }
    .user-image li {
        /* padding-left: 0; */
        padding: 10px;
        margin-top: 5px;
        padding-top: 5px;
    }
    .profiles-wrapper {
        display: block;
        margin: 5px;
    }
    .profiles-wrapper:hover {
        background-color: #c5c2c27e;
        border-radius: 3%;
    }
    .user-image img {
        border-radius: 50%;
        width: 42px;
        height: 42px;
    }
    
    .card {
        margin: 0 0.5em;
        border: none;
        text-align: center;
        height: 250px;
        background-color:#f4f4f4;
    }
    
    .card-body {
        padding-top: 5px;
        font-size:15px;
        text-align: center;
        /* max-width: 150px; */
        max-width: 150px;
    }
    .carousel-control-prev,
    .carousel-control-next {
        background-color: #e1e1e1;
        width: 6vh;
        height: 6vh;
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
    }
    
    @media (min-width: 768px) {
        .carousel-item {
            margin-right: 1px;
            flex: 0 0 14.2%;
            display: block;
            justify-content: center;
            align-items: center;
        }
        .carousel-item-activity{
            margin-right: 1px;
            flex: 0 0 20%;
            display: block;
            justify-content: center;
            align-items: center;
        }
        .carousel-inner {
            display: flex;
            background-color:#f4f4f4;
            
        }
    }
    .card .img-wrapper {
        max-width: 150px;
        height: 13em;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .card img {
        max-height: 100%;
    }
    @media (max-width: 767px) {
        .card .img-wrapper {
            height: 17em;
        }
    }
</style>
