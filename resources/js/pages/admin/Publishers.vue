<template>
    <div class="container-fluid">
        <div v-for="p in publishers" :key="p.id" class="">
            <div class="row flex-row-reverse justify-content-between align-items-center">
                <a class="col-6 fs-5 text-dark" :href="'#adminsList' + p.id" data-bs-toggle="collapse" style="text-decoration:none"> 
                    {{p.name}} 
                </a>
                <div class="col-6 text-start">
                    <a href="#" class="text-dark link-underline link-underline-opacity-0">
                        <div class="btn btn-outline-dark px-3" data-bs-toggle="modal" :data-bs-target="'#newAdmin' + p.id">  ادمین جدید </div>
                    </a>
                </div>
            </div>
            <hr>
            <!-- new admin modal  -->
            <div class="modal fade" :id="'newAdmin' + p.id" tabindex="-1" aria-labelledby="#newAdminLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header ">
                            <h1 class="modal-title fs-5 text-center w-100" :id="'#newAdminLabel' + p.id">جستجو کاربران</h1>
                            <button :id="'newAdminClose' + p.id" type="button" class="btn-close  me-0" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <input type="text" class="form-control" name="search" 
                                @keyup="search" v-model="searchInput">
                                <br>
                                <div class="mt-2">
                                    <div v-for="u in searchedUsers" :key="u.id" class="row flex-row-reverse align-items-center my-3">
                                        <div class="col d-flex flex-row-reverse">
                                            <img class="item-img" style="width: 45px; height: 45px; border-radius: 100%;" :src="u.image" alt="">
                                            <div class="align-self-center me-2">
                                                {{ u.username }}
                                            </div>
                                        </div>
                                        <div v-if="u.role_id == 0" class="col-auto float-start">
                                            <button  class="btn btn-dark m-1 p-1 px-2" 
                                            @click.prevent="promoteToAdmin(u, p.id)"
                                            >ارتقا به ادمین</button>
                                        </div>
                                        <!-- <div v-if="u.role_id == 3 && u.publisher_id == p.id" class="col-auto float-start">
                                            <p>ادمین</p>
                                        </div> -->
                                        <div v-if="(u.role_id == 3 || u.role_id == 4) && u.publisher_id == p.id" class="col-auto float-start">
                                            <button  class="btn btn-dark m-1 p-1 px-2" 
                                            @click.prevent="demoteToNormal(u)"
                                            >حذف ادمین</button>
                                        </div>
                                        <div v-if="u.role_id == 3 && u.publisher_id == p.id" class="col-auto float-start">
                                            <button  class="btn btn-dark m-1 p-1 px-2" 
                                            @click.prevent="promoteToSuperAdmin(u)"
                                            >ارتقا به سوپر ادمین</button>
                                        </div>
                                        <!-- <div v-if="u.role_id == 4 && u.publisher_id == p.id" class="col-auto float-start">
                                            <p>سوپر ادمین</p>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="collapse mt-2 row flex-row-reverse  justify-content-between m-1  my-4" :id="'adminsList' + p.id">
                <div class="col-6">
                    <div class="text-center">سوپر ادمین ها</div>
                    <hr>
                    <div v-for="u in p.admins" :key="u.id" class="my-2">
                        <div v-if="u.role_id == 4" class="row flex-row-reverse">
                            <div class="col-2">
                                <img class="" style="width: 45px; height: 45px; border-radius: 100%;" :src="u.image" alt="">
                            </div>
                            <div class="col-2 align-self-center">
                                {{ u.username }}
                            </div>
                            <div v-if="u.role_id == 3 || u.role_id == 4" class="col-8 text-start">
                                <button  class="btn btn-dark m-1 p-1 px-2" 
                                @click.prevent="demoteToNormal(u)"
                                >حذف ادمین</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 ">
                    <div class="text-center"> ادمین ها</div>
                    <hr>
                    <div v-for="u in p.admins" :key="u.id" class=" my-2">
                        <div v-if="u.role_id == 3" class="row flex-row-reverse">
                            <div class="col-2">
                                <img class="item-img" style="width: 45px; height: 45px; border-radius: 100%;" :src="u.image" alt="">
                            </div>
                            <div class="col-2 align-self-center">
                                {{ u.username }}
                            </div>
                            <div v-if="u.role_id == 3 || u.role_id == 4" class="col-4 text-start">
                                <button  class="btn btn-dark m-1 p-1 px-2" 
                                @click.prevent="demoteToNormal(u)"
                                >حذف ادمین</button>
                            </div>
                            <div class="col-4 float-start">
                                <button  class="btn btn-dark m-1 p-1 px-2" 
                                @click.prevent="promoteToSuperAdmin(u)"
                                >ارتقا به سوپر ادمین</button>
                            </div>
                        </div>
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
            searchInput: '',
            searchedUsers: [],
            timeoutId: null,
            publishers: [],
        } 
    },
    beforeMount() {
        
        if (this.user.role.role_id != 1 && this.user.role.role_id != 2) {
            this.$router.push('/login')
        } else {
            axios.get('/api/admin/publishers')
            .then((response) => { 
                this.publishers = response.data.data
                console.log(this.publishers)
            })
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
                    axios.post('/api/search/user-role', {
                        input: this.searchInput,
                    }).then(response => {
                        console.log(response.data.data)
                        this.searchedUsers = response.data.data
                    })
                }
            }, 800);
        },

        async promoteToAdmin(user, publisher_id) {
            await axios.get(`/api/promote-normal-user-to-publisher-admin/${user.id}/${publisher_id}`)
            .then(() => { 
                // list
                this.publisher = this.publishers.find(item => item.id === publisher_id);
                this.publisher.admins.push(user)

                // search
                this.admin = this.searchedUsers.find(item => item.id === user.id);
                this.admin.role_id = 3
                this.admin.publisher_id = publisher_id
            })
        },

        async demoteToNormal(user) {

            if (user.role_id == 3) {
                await axios.get(`/api/demote-publisher-admin-to-normal-user/${user.id}/${user.publisher_id}`)
                .then(() => {
                    // list
                    this.publisher = this.publishers.find(item => item.id === user.publisher_id);
                    this.publisher.admins = this.publisher.admins.filter(item => item.id !== user.id)

                    // search
                    this.admin = this.searchedUsers.find(item => item.id === user.id);
                    if (this.admin) {
                        this.admin.role_id = 0
                    }
                })
            } else if (user.role_id == 4) {
                await axios.get(`/api/demote-publisher-super-admin-to-normal-user/${user.id}`)
                .then(() => {
                    // list
                    this.publisher = this.publishers.find(item => item.id === user.publisher_id);
                    this.publisher.admins = this.publisher.admins.filter(item => item.id !== user.id)

                    // search
                    this.admin = this.searchedUsers.find(item => item.id === user.id);
                    if (this.admin) {
                        this.admin.role_id = 0
                    }
                })
            }
        },

        async promoteToSuperAdmin(user) {
            await axios.get(`/api/promote-publisher-admin-to-publisher-super-admin/${user.id}/${user.publisher_id}`)
            .then(() => { 
                // list
                this.publisher = this.publishers.find(item => item.id === user.publisher_id)
                this.admin = this.publisher.admins.find(item => item.id === user.id)
                this.admin.role_id = 4

                // search
                this.admin = this.searchedUsers.find(item => item.id === user.id);
                if (this.admin) {
                    this.admin.role_id = 4
                }
            })
        },
    }
    
}
</script>