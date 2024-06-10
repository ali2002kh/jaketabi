<template>
    <PageHeader v-if="fixed"></PageHeader>
    <br>
    <hr>
    <hr>
    <div v-for="c in comments" :key="c.id">
        <div>{{ c.user.username }}</div>
        <img :src="c.user.image" class="user-profile" alt="">
        <div>{{ c.message }}</div>
    </div>

    <div class="alert alert-danger" v-if="hasError">
        <ul>
            <li v-for="e in errors" :key="e">{{ e[0] }}</li>
        </ul>
    </div>
    <div class="alert alert-success" v-if="success">{{ message }}</div>
    <form>
        <div class="m-1 mt-3">
            <label for="commentMessage" class="form-label">متن نظر</label>
            <textarea id="commentMessage" class="form-control text-end" name="commentMessage" v-model="commentMessage"></textarea>
        </div>
        <div class="m-1 text-start">
            <button type="submit" class="btn btn-dark m-1 mt-3" 
            @click.prevent="sendComment"
            >ارسال</button>
        </div>
    </form>
    
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
.user-profile {
    width: 60px;
    height: 60px;
    border-radius: 50%;
}
</style>