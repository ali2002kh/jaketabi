<template>
    <div class="container-fluid">
        <div class="col mt-5">
            <div class="text-start">
                <a href="#" class="text-dark link-underline link-underline-opacity-0">
                    <button class="btn btn-dark px-3" data-bs-toggle="modal" data-bs-target="#newAdmin">اضافه کردن ادمین جدید</button>
                </a>
            </div>
            <div class="modal fade" id="newAdmin" tabindex="-1" aria-labelledby="newAdminLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header ">
                            <h1 class="modal-title fs-5 text-center w-100" id="newAdminLabel">جستجو کاربران</h1>
                            <button id="friend_close" type="button" class="btn-close  me-0" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                            @click.prevent="promoteToAdmin(u)"
                                            >ارتقا به ادمین</button>
                                        </div>
                                        <div v-if="u.role_id == 3 && u.publisher_id == user.publisher.id" class="col-auto float-start">
                                            <button  class="btn btn-dark m-1 p-1 px-2" 
                                            @click.prevent="demoteToNormal(u)"
                                            >حذف ادمین</button>
                                        </div>
                                        <div v-if="u.role_id == 3 && u.publisher_id == user.publisher.id" class="col-auto float-start">
                                            <button  class="btn btn-dark m-1 p-1 px-2" 
                                            @click.prevent="promoteToSuperAdmin(u)"
                                            >ارتقا به سوپر ادمین</button>
                                        </div>
                                        <div v-if="u.role_id == 4 && u.publisher_id == user.publisher.id" class="col-auto float-start">
                                            <p>سوپر ادمین</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <div v-for="u in admins" :key="u.id" class="row flex-row-reverse align-items-center my-3">
                <div class="col d-flex flex-row-reverse">
                    <img class="item-img" style="width: 45px; height: 45px; border-radius: 100%;" :src="u.image" alt="">
                    <div class="align-self-center me-2">
                        {{ u.username }}
                    </div>
                </div>
                <div v-if="u.role_id == 3 && u.publisher_id == user.publisher.id" class="col-auto float-start">
                    <button  class="btn btn-dark m-1 p-1 px-2" 
                    @click.prevent="demoteToNormal(u)"
                    >حذف ادمین</button>
                </div>
                <div v-if="u.role_id == 3 && u.publisher_id == user.publisher.id" class="col-auto float-start">
                    <button  class="btn btn-dark m-1 p-1 px-2" 
                    @click.prevent="promoteToSuperAdmin(u)"
                    >ارتقا به سوپر ادمین</button>
                </div>
                <div v-if="u.role_id == 4 && u.publisher_id == user.publisher.id" class="col-auto float-start">
                    <p>سوپر ادمین</p>
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
            admins: [],
        } 
    },
    beforeMount() {
        
        if (this.user.role.role_id != 4) {
            this.$router.push('/login')
        } else {
            axios.get(`/api/publisher-admins/${this.user.publisher.id}`)
            .then((response) => { 
                this.admins = response.data.data
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

        async promoteToAdmin(user) {
            await axios.get(`/api/promote-normal-user-to-publisher-admin/${user.id}/${this.user.publisher.id}`)
            .then(() => { 
                this.admins.push(user)
                this.item = this.searchedUsers.find(item => item.id === user.id);
                this.item.role_id = 3
                this.item.publisher_id = this.user.publisher.id
            })
        },

        async demoteToNormal(user) {
            await axios.get(`/api/demote-publisher-admin-to-normal-user/${user.id}/${this.user.publisher.id}`)
            .then(() => {
                this.admins = this.admins.filter(item => item.id !== user.id)
                this.item = this.searchedUsers.find(item => item.id === user.id);
                this.item.role_id = 0
            })
        },

        async promoteToSuperAdmin(user) {
            await axios.get(`/api/promote-publisher-admin-to-publisher-super-admin/${user.id}/${this.user.publisher.id}`)
            .then(() => { 
                this.item = this.searchedUsers.find(item => item.id === user.id);
                this.item.role_id = 4
            })
        },
    }
    
}
</script>