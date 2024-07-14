<template>
    <div class="container-fluid" v-if="user && user.role">
    
        <div class="modal-body">
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
                        <button  class="btn btn-dark m-1 p-1 px-2" 
                        @click.prevent="sendFriendRequest(b.id)"
                        >درخواست دوستی</button>
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
                        this.$router.push('/login')
                        reject()
                    }
                })
            }
        })

        loadUser.then(() => {
            if (!this.user.role) {
                this.$router.push('/login')
            } else {
                if (this.user.role.role_id == 3 || this.user.role.role_id == 4) {
                    this.publisher = this.user.publisher
                }
            }
        })
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
    }
    
}
</script>