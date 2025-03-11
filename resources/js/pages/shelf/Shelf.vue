<template>
    <page-header :user="user"></page-header>
    <div class="container-fluid mt-5 row flex-row-reverse ">
        <!-- sidebar -->
        <div class="col-lg-3 col-sm-4 mt-4 sidebar float-end bg-light row"
        :class="{'sticky-top' : this.windowWidth > 575}">
            <!-- <div class=" d-flex flex-column align-items-end mt-4 w-100"> -->
                <div v-if="isOwner" class="col-sm-12 col-6 sidebar-item mt-2" data-bs-toggle="modal" data-bs-target="#editShelf">
                    <a href="#" class="sidebar-link link-dark link-underline link-underline-opacity-0 fs-6 fw-bold p-2 d-block text-end"
                    :class="{'text-center mt-2' : this.windowWidth < 576}" style="text-decoration:none">
                        ویرایش قفسه
                        <i class="fa-solid fa-pen fa-md p-2"></i>
                    </a>
                </div>
                <div v-if="isOwner" class="col-sm-12 col-6 sidebar-item mt-2" data-bs-toggle="modal" data-bs-target="#deleteShelf">
                    <a href="#" class="sidebar-link link-dark link-underline link-underline-opacity-0 fs-6 fw-bold p-2 d-block text-end"
                    :class="{'text-center mt-2' : this.windowWidth < 576}" style="text-decoration:none">
                        حذف قفسه
                        <i class="fa-solid fa-trash fa-md p-2"></i>
                    </a>
                </div>
                <router-link v-else :to="{ name: 'profile', params: { id: shelf.user.id }}" class="v-else mt-4 row align-items-center router-links">
                    <div class="col-auto">
                        <img :src="shelf.user.image" class="user-profile" alt="">
                    </div>
                    <div class="col-auto">
                        <p class="fs-6 fw-bold">{{shelf.user.username}}</p>
                    </div>
                </router-link>
                <!-- <hr v-if="isOwner" class="border border-secondary w-75 mx-auto p-0 m-1"> -->
                <div class="sidebar-item w-100 mt-2 h-100 mb-5">
                    <div class="fw-bold fs-6 text-end d-flex flex-row-reverse align-items-center w-100 p-2">
                        <i class="fa-solid fa-quote-right fa-md p-2"></i>
                        توضیحات
                    </div>
                    <div class="w-100 p-2" id="showHideBtn" @click.prevent="showHide">
                        <p class="text-end fs-6 " style="word-wrap: break-word; direction:rtl;" id="description"
                        :class="{'hide-lines' : this.windowWidth < 576}">
                            {{shelf.description}}
                        </p>
                    </div>
                </div>
            <!-- </div> -->
        </div>

        <div class="content col-lg-9 col-sm-8" :class="{'me-3 ' : this.windowWidth > 575}">
            <div class="mt-5 shelf-header row flex-row-reverse align-items-center mx-2">
                <div class="shelf-title text-end col ">
                        <p class="text-dark fw-bold fs-5" style="white-space: nowrap;"> {{shelf.name}} </p>
                </div>
                <div class="col-auto text-start ">
                    <p class=" fs-6 fw-bold">تعداد کتاب ها : {{shelf.book_count}} </p>
                </div>
            </div>
            <hr class="row opacity-100 border-muted border mx-2" >
            <div class="row flex-row-reverse mx-1">
                <div v-for="b in shelf.books" :key="b.id" class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-auto p-1">
                    <single-book-item :book="b"></single-book-item>
                </div>

            </div>
        </div>

        <!-- Delete Shelf Modal -->
        <div class="modal fade" id="deleteShelf" tabindex="-1" aria-labelledby="deleteShelfLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h1 class="modal-title fs-6 w-100 text-center" id="deleteShelfLabel">حذف قفسه</h1>
                    </div>
                    <div class="modal-body text-end">
                        از حذف این قفسه مطمئنی؟
                    </div>
                    <div class="modal-footer d-flex flex-row justify-content-start">
                        <button type="button" class="btn btn-outline-danger"  @click.prevent="deleteShelf()">حذف</button>
                        <button id="closeAlert" type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">لغو</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Shelf Modal-->
        <div class="modal fade" id="editShelf" tabindex="-1" aria-labelledby="editShelfLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-6 w-100 text-center" id="editShelfLabel">ویرایش قفسه</h1>
                        <button id="close" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-end">
                        <div class="alert alert-danger" v-if="hasError" dir="rtl">
                            <ul class="error-list">
                                <li v-for="e in errors" :key="e" class="error-item">{{ e[0] }}</li>
                            </ul>
                        </div>
                        <div class="alert alert-success" v-if="success" dir="rtl">{{ message }}</div>
                        <form>
                            <div class="m-1">
                                <label for="shelfName" class="form-label">نام قفسه</label>
                                <input type="text" class="form-control text-end" id="shelfName" name="shelfName"
                                v-model="shelfName"
                                >
                            </div>
                            <div class="m-1 mt-3">
                                <label for="shelfDescription" class="form-label">توضیحات</label>
                                <textarea id="shelfDescription" class="form-control text-end" name="shelfDescription" v-model="shelfDescription"></textarea>
                            </div>
                            <div class="m-1 text-start">
                                <button type="submit" class="btn btn-dark m-1 mt-3"
                                @click.prevent="updateShelf"
                                >تایید</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { mapState } from 'vuex';
import PageHeader from "../../layouts/PageHeader"
import SingleBookItem from '../../layouts/SingleBookItem';
// import PageFooter from "../layouts/PageFooter"

export default {
    components: {
        PageHeader,
        SingleBookItem
        // PageFooter,
    },
    data() {
        return {
            shelf: null,
            isOwner: false,
            shelfName: null,
            shelfDescription: null,
            hasError: false,
            errors: [],
            success: false,
            message: null,
            windowWidth: window.innerWidth,
        }
    },
    created() {
        axios.get(`/api/shelf/${this.$route.params.id}`)
        .then(response => {
            console.log(response.data.data)
            this.shelf = response.data.data
            this.shelfName = this.shelf.name
            this.shelfDescription = this.shelf.description
        })
    },
    mounted() {

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
            if (this.user && this.user.id == this.shelf.user.id) {
                this.isOwner = true
            }
        })
        window.onresize = () => {
                this.windowWidth = window.innerWidth
        }
    },
    computed: {
        // ...mapGetters(["user"]),
        ...mapState({
            user: state => state.user.data,
        }),
        // ...mapActions(['initState'])
    },
    methods: {
        async deleteShelf() {
            await axios.get(`/api/remove-shelf/${this.$route.params.id}`)
            .then(() => {
                document.getElementById('closeAlert').click()
                this.$router.push(`/profile/${this.user.id}`)
            })
        },

        async updateShelf() {
            this.hasError = false
            this.errors = []
            this.success = false
            this.message = null
            await axios.post(`/api/update-shelf/${this.$route.params.id}`, {
                shelfName: this.shelfName,
                description: this.shelfDescription
            }).then((response) => {
                this.success = true;
                this.message = response.data.message
                this.shelf.name = this.shelfName
                this.shelf.description = this.shelfDescription
            }).catch ((error) => {
                if (error.response &&
                    error.response.status &&
                    error.response.status == 422) {
                        this.hasError = true
                        console.log(error.response.data)
                        this.errors = error.response.data.errors
                }
            })
        },

        showHide() {
            var description = document.getElementById('description');
            if (description.classList.contains('hide-lines')) {
                description.classList.remove('hide-lines');
            }
            else {
                description.classList.add('hide-lines');
            }
        },
    }
}
</script>

<style scoped>
.container-fluid {
    padding-right:0;
    padding-left:0;
    margin-right:auto;
    margin-left:auto;
    overflow: hidden;
 }

.sidebar::-webkit-scrollbar {
        display: none;
}
.sidebar-link {
    transition: all 0.2s;
}
.sidebar-link:hover {
    color: white !important;
    background-color: rgb(33, 37, 41);
}
.book-img {
width: 110px;
height: 160px;
}
.router-links {
    color: black;
    text-decoration: none;
}
.user-profile {
width: 60px;
height: 60px;
border-radius: 50%;
}

@media screen and (min-width:576px) {
.sidebar {
    height: 100vh;
    overflow-y: scroll !important;
    overflow-x:hidden !important;
    right: 0 !important;

}
.content {
    max-height: 100vh;
    overflow-y: scroll;
}
.content::-webkit-scrollbar {
    display: none;
}
}


@media screen and (max-width:575px) {
.sidebar {
    height: auto;

}
.content {
    position: relative;
}

.hide-lines {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

}
</style>
