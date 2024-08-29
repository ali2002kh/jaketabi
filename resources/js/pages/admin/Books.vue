<template>
    <div class="container mt-3">
    
        <div class="col-auto row flex-row-reverse align-items-center justify-content-between mx-2">
            <div class="col-6 fs-5">جستجو</div>
            <router-link :to="{name: 'create-book'}" class="btn btn-dark col-auto text-start" 
            >  کتاب جدید
            </router-link>
        </div>
        <hr>
        <div class="container">
            <input type="text" class="form-control" name="search" 
            @keyup="search" v-model="searchInput">
            <br>
            <div class="mt-2">
                <div v-for="b in books" :key="b.id" class="row flex-row-reverse align-items-center my-3">
                    <div class="col d-flex flex-row-reverse">
                        <img class="item-img" style="width: 45px; height: 45px; border-radius: 100%;" :src="b.image" alt="">
                        <div class="align-self-center me-2">
                            {{ b.name }}
                        </div>
                    </div>
                    <div class="col-auto float-start">
                        <router-link :to="{name: 'edit-book', params: {id: b.id}}"  class="btn btn-dark m-1 p-1 px-2" 
                        >ویرایش</router-link>
                    </div>
                    <div class="col-auto float-start">
                        <button  class="btn btn-outline-danger m-1 p-1 px-2" 
                        @click.prevent="removeBook(b.id)"
                        >حذف</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    data() {
        return {
            publisher: null,
            searchInput: '',
            books: [],
            timeoutId: null,
        } 
    },
    beforeMount() {
        
        if (this.user.role.role_id == 3 || this.user.role.role_id == 4) {
            this.publisher = this.user.publisher
        }
    
    },

    
    computed: {
        ...mapState({
            user: state => state.user.data,
            loggedIn: state => state.user.loggedIn
        }),
    },

    methods: {
        async search() {

            clearTimeout(this.timeoutId);

            this.timeoutId = setTimeout(() => {
                if (this.searchInput.length > 1) {
                    if (this.publisher) {
                        axios.post('/api/search/book', {
                            input: this.searchInput,
                            publisher_id: this.publisher.id,
                        }).then(response => {
                            console.log(response.data.data)
                            this.books = response.data.data
                        })
                    } else {
                        axios.post('/api/search/book', {
                        input: this.searchInput,
                        }).then(response => {
                            console.log(response.data.data)
                            this.books = response.data.data
                        })
                    }
                    
                }
            }, 800);
        },

        async removeBook(book_id) {
            await axios.get(`/api/book/remove/${book_id}`)
            .then(() => {
                this.books = this.books.filter(item => item.id !== book_id) 
            })
        },
    }
    
}
</script>
<style scoped>
.router-links {
    color: black;
    text-decoration: none;
}
</style>