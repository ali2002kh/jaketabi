<template>
    <PageHeader v-if="user" :id="user.id" :image="user.image"></PageHeader>
    <div class="row">
        <div class="col-sm-6 my-3">
            صفحه ی کتاب
        </div>
        <div class="container my-4">
            <div class="row">
                <div class="col-sm-4">
                    <div class="container d-flex">
                        <div class="d-grid">
                            <p class="text-muted mb-1">{{ book.name }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        </div>
    </div>
    <div v-if="isNotSelected">
        {{ record.status }}
    </div>
    <div v-if="isWantToRead">
        {{ record.status }}
    </div>
</template>

<script>

import { mapState } from 'vuex';
import PageHeader from "../layouts/PageHeader"
// import PageFooter from "../layouts/PageFooter"

export default {
    components: {
        PageHeader,
        // PageFooter,
    },
    data() {
        return {
            book: null,
            record: null,
        }
    },
    created() {
        axios.get(`/api/book/${this.$route.params.id}`)
        .then(response => {
            console.log(response.data.data)
            this.book = response.data.data
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
                })
                console.log('called')
               
            }
        })

        userLoaded.then(() => {
            axios.get(`/api/book-record/${this.user.id}/${this.$route.params.id}`)
            .then(response => {
                console.log(response.data.data)
                this.record = response.data.data
            })
        })

    },
    computed: {
        ...mapState({
            user: state => state.user.data,
        }),

        isNotSelected() {
            if (this.record && this.record.status_code == 0) {
                return true
            }
            return false
        },

        isWantToRead() {
            if (this.record && this.record.status_code == 1) {
                return true
            }
            return false
        },

        isCurrentReading() {
            if (this.record && this.record.status_code == 2) {
                return true
            }
            return false
        },

        isAlreadyRead() {
            if (this.record && this.record.status_code == 3) {
                return true
            }
            return false
        },

    },
}
</script>