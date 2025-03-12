<template>
<div class="body">
    <page-header :user="user"></page-header>
    <div class="container header mx-auto mt-5 row flex-row-reverse align-items-center">
        <div class="col-8 title text-end fs-4 fw-bold">
            <p v-if="isOwner" class="title mt-5">قفسه های من</p>
            <p v-else class="title mt-5"><span>
                {{ shelves[0].user.username }} قفسه های
            </span></p>
        </div>
        <div class="col-4 text-start">
            <create-shelf @addShelf="addShelf" v-if="isOwner"></create-shelf>
        </div>
        <hr class="opacity-100 border border-muted">
    </div>
    <!-- shelves list  -->
    <div class="container rounded-1 mb-2 p-2 py-4 mx-auto h-100" style="background: #f4f4f4; height:100vh">
        <div class="row flex-row-reverse align-items-center"
            v-for="row in rows" :key="'row' + row">
            <div class="d-flex flex-row-reverse">
                <div class="col-4 mb-3" v-for="(s,column) in shelvesInRow(row)" :key="'row' + row + column">
                    <single-shelf-item :shelf="s"></single-shelf-item>
                </div>
            </div>
        </div>
    </div>

</div>
</template>

<script>

import { mapState } from 'vuex'
import PageHeader from "../../layouts/PageHeader"
import SingleShelfItem from '../../layouts/SingleShelfItem'
import CreateShelf from '../../layouts/CreateShelf'

export default {
    components: {
        PageHeader,
        SingleShelfItem,
        CreateShelf,
    },

    data() {
        return {
            shelves: null,
            isOwner: false,
            columns: 3,
        }
    },

    created() {
        axios.get(`/api/shelves/${this.$route.params.id}`)
        .then(response => {
            console.log(response.data.data)
            this.shelves = response.data.data;
        });
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
                        resolve()
                    }
                })
            }
        })


        loadUser.then(() => {
            if (this.user && this.user.id == this.$route.params.id) {
                this.isOwner = true
            }
        })
    },

    computed: {
        ...mapState({
            user: state => state.user.data,
            loggedIn: state => state.user.loggedIn
        }),
        rows() {
            return this.shelves == null
            ? 0
            : Math.ceil(this.shelves.length / this.columns);
        },
    },

    methods: {
        shelvesInRow(row) {
            return this.shelves.slice((row - 1) * this.columns, row * this.columns)
        },
        addShelf(shelf) {
            this.shelves.push(shelf)
        }
    },
}
</script>

<style scoped>
.body {
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}

.title {
    font-family: hamishe;
}
</style>
