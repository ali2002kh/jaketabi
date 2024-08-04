<template>
    <PageHeader v-if="fixed"></PageHeader>
    <div class="body container mb-5" style="margin-top: 110px;">
        <p class="title"><span>نظرات کاربران</span></p>
        <div class="row flex-row-reverse rounded-2 bg-light shadow-sm p-3 mt-4 align-items-center mx-2">
            <div class="col-md-1 col-sm-2 col-12 p-2 text-end" 
            :class="{'text-center' : windowWidth > 576}">
                <i class="fa-solid fa-circle-user fa-3x text-dark"></i>
            </div>
            <form class="col-md-11 col-sm-10 col-12 row flex-row-reverse align-items-center p-2">
                <div class="col-md-10 col-sm-9 col-12">
                    <!-- <label for="commentMessage" class="form-label">متن نظر</label> -->
                    <textarea id="commentMessage" class="form-control text-end w-100" name="commentMessage" 
                    v-model="commentMessage" placeholder=".نظرت رو اینجا بنویس">
                    </textarea>
                </div>
                <div class="col-md-1 col-sm-2 col-auto me-auto text-start" :class="{'mt-2': windowWidth < 577}">
                    <button type="submit" class="btn btn-dark " 
                    @click.prevent="sendComment"
                    >ارسال</button>
                </div>
            </form>
        </div>
        <div class="alert alert-danger mx-2 mt-3" v-if="hasError" dir="rtl">
            <ul class="error-list">
                <li v-for="e in errors" :key="e" class="error-item">{{ e[0] }}</li>
            </ul>
        </div>
        <div class="alert alert-success mx-2 mt-3" v-if="success" dir="rtl">{{ message }}</div>

        <div class="text-end fw-bold fs-6 mx-3 mt-4" style="direction:rtl">
            {{comments.length}} نظر ثبت شده   
        </div>
        <hr class="opacity-100 border-muted border mx-2">
        
        <div v-for="c in comments" :key="c.id" 
        class="row flex-row-reverse rounded-2 bg-light shadow-sm p-3 mt-4 align-items-center mx-2">
            <img class="col-sm-2 col-3 user-profile p-2" :src="c.user.image" alt="">
            <router-link :to="{name: 'profile', params: {id: c.user.id}}" 
            class="col-auto text-end p-2 router-links">
                {{ c.user.username }}
            </router-link>
            
            <div class="row mx-1 text-end p-2" style="direction:rtl">
                {{ c.message }}
            </div>
        </div>
    </div>

    
</template>

<script>

import { mapState } from 'vuex';
import PageHeader from "../../layouts/PageHeader"
import moment from "moment";
// import PageFooter from "../layouts/PageFooter"

export default {
    components: {
        PageHeader,
        // PageFooter,
    },
    data() {
        return {
            moment: moment,
            comments: null,
            commentMessage: null,
            hasError: false,
            errors: [],
            success: false,
            message: null,
            fixed: false,
            windowWidth: window.innerWidth,
        }
    },
    created() {
        axios.get(`/api/book/comments/${this.$route.params.id}`)
        .then(response => {
            console.log(response.data.data)
            this.comments = response.data.data
        });
    },  
    mounted() {

        let userLoaded = new Promise((resolve, reject) => {
                if (this.user) {
                console.log('User is already loaded')
                resolve()
            } else {
                this.$store.dispatch("user/loadUser").then(() => {
                    console.log('resolved')
                    resolve()
                }).catch(() => {
                    reject()
                })
                console.log('called')
                
            }
        })

        userLoaded.then(() => {
            console.log(this.user)
            
        })

        userLoaded.finally(() => {
            this.fixed = true
        })
        window.onresize = () => {
                this.windowWidth = window.innerWidth
        }
    },
    methods: {
        
        async sendComment() {
            this.hasError = false
            this.errors = []
            this.success = false
            this.message = null
            await axios.post(`/api/add-book-comment/${this.$route.params.id}`, {
                commentMessage: this.commentMessage,
            }).then((response) => {
                this.success = true;
                this.message = response.data.message
                this.comments.push(response.data.data.comment)
            }).catch ((error) => {
                if (error.response && 
                    error.response.status && 
                    error.response.status == 422) {
                        this.hasError = true
                        console.log(error.response.data)
                        this.errors = error.response.data.errors
                }
            })
        } 
    },
    computed: {
        ...mapState({
            user: state => state.user.data,
            loggedIn: state => state.user.loggedIn,
        }),

    },
}
</script>

<style scoped>
.body {
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}
.user-profile {
    width: 60px;
    height: 60px;
    border-radius: 50%;
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
.router-links {
    color: black;
    text-decoration: none;
}
</style>