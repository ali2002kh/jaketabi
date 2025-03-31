<template>
    <div v-if="host.is_private" class="col-md-2 col-sm-auto col-3 mt-5">
        <div class="text-start">
            <a href="#" class="text-dark link-underline link-underline-opacity-0">
                <button class="btn btn-dark px-3" data-bs-toggle="modal" data-bs-target="#friends">دوستان</button>
            </a>
        </div>
        <div class="modal fade" id="friends" tabindex="-1" aria-labelledby="friendsLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header ">
                        <button @click.prevent="toggleSearch" class="modal-title ms-0 btn-dark bg-white border-0"
                        style="cursor: pointer">
                            <i v-if="searchIsActive" class="fa-solid fa-user fa-lg"></i>
                            <i v-else class="fa-solid fa-magnifying-glass fa-lg"></i>
                        </button>
                        <h1 class="modal-title fs-5 text-center w-100" id="friendsLabel">دوستان</h1>
                        <button id="friend_close" type="button" class="btn-close  me-0" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div v-if="searchIsActive">
                            <input type="text" class="form-control" name="search"
                            @keyup="search" v-model="searchInput">
                            <br>
                            <div class="mt-2">
                                <div v-for="u in searchedUsers" :key="u.id" class="row flex-row-reverse align-items-center my-3">
                                    <div class="col d-flex flex-row-reverse" @click.prevent="showProfile(u.id)">
                                        <img class="item-img" style="width: 45px; height: 45px; border-radius: 100%;" :src="u.image" alt="">
                                        <div class="align-self-center me-2">
                                            {{ u.username }}
                                        </div>
                                    </div>
                                    <div v-if="u.status == 0" class="col-auto float-start">
                                        <button  class="btn btn-dark m-1 p-1 px-2"
                                        @click.prevent="sendFriendRequest(u.id)"
                                        >درخواست دوستی</button>
                                    </div>

                                    <div v-if="u.status == 1" class="col-auto float-start">
                                        <button class="btn btn-dark m-1 p-1 px-2"
                                        @click.prevent="cancelFriendRequest(u.id)"
                                        >لغو درخواست</button>
                                    </div>

                                    <div v-if="u.status == 2" class="col-auto float-start">
                                        <button class="btn btn-outline-success  p-1 px-3"
                                        @click.prevent="acceptFriendRequest(u.id)"
                                        >قبول  </button>
                                    </div>

                                    <div v-if="u.status == 2" class="col-auto float-start">
                                        <button class="btn btn-outline-danger p-1 px-4"
                                        @click.prevent="rejectFriendRequest(u.id)"
                                        >رد  </button>
                                    </div>

                                    <div v-if="u.status == 3" class="col-auto float-start">
                                        <button class="btn btn-dark m-1 p-1 px-2"
                                        @click.prevent="removeFriend(u.id)"
                                        >حذف دوستی</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div v-else class="">
                            <div class="row flex-row-reverse align-items-center my-3" v-for="f in friends" :key="f.id">
                                <div class="col d-flex flex-row-reverse" @click.prevent="showProfile(f.id)">
                                    <img class="item-img" style="width: 45px; height: 45px; border-radius: 100%;" :src="f.image" alt="">
                                    <div class="align-self-center me-2">
                                        {{ f.username }}
                                    </div>
                                </div>
                                <div class="col-auto float-start">
                                    <button class="btn btn-dark m-1 p-1 px-2"
                                    @click.prevent="removeFriend(f.id)"
                                    >حذف دوستی</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else class="col mt-5">
        <div class="text-start" v-if="friendship">

            <div v-if="friendship.status == 0" class="col-auto float-start">
                <button  class="btn btn-dark m-1 p-1 px-2"
                @click.prevent="sendFriendRequest(friendship.id)"
                >درخواست دوستی</button>
            </div>

            <div v-if="friendship.status == 1" class="col-auto float-start">
                <button class="btn btn-dark m-1 p-1 px-2"
                @click.prevent="cancelFriendRequest(friendship.id)"
                >لغو درخواست</button>
            </div>

            <div v-if="friendship.status == 2" class="col-auto float-start">
                <button class="btn btn-outline-success  p-1 px-3"
                @click.prevent="acceptFriendRequest(friendship.id)"
                >قبول  </button>
            </div>

            <div v-if="friendship.status == 2" class="col-auto float-start">
                <button class="btn btn-outline-danger p-1 px-4"
                @click.prevent="rejectFriendRequest(friendship.id)"
                >رد  </button>
            </div>

            <div v-if="friendship.status == 3" class="col-auto float-start">
                <button class="btn btn-dark m-1 p-1 px-2"
                @click.prevent="removeFriend(friendship.id)"
                >حذف دوستی</button>
            </div>
        </div>
    </div>
</template>


<script>

export default {

    props: {
        host: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            // is private
            friends: [],
            searchIsActive: false,
            searchInput: null,
            timeoutId: null,
            searchedUsers: [],

            // someone else
            friendship: null,
        }
    },

    beforeMount() {
        if (this.host.is_private) {
            this.friends = this.host.friends
            console.log("hi")
            // console.log("friends:"+this.friends[0].id)
        } else {
            axios.get(`/api/friendship/${this.$route.params.id}`)
            .then((response) => {
                this.friendship = response.data.data
            })
        }
    },

    methods: {

        async removeFriend(user_id) {
            await axios.get(`/api/reject-or-remove-friend/${user_id}`)
            .then(() => {
                if (this.host.is_private) {
                    this.friends = this.friends.filter(item => item.id !== user_id)
                    this.item = this.searchedUsers.find(item => item.id === user_id);
                    this.item.status = 0
                } else {
                    this.friendship.status = 0
                }
            })
        },

        async sendFriendRequest(user_id) {
            await axios.get(`/api/accept-or-add-friend/${user_id}`)
            .then(() => {
                if (this.host.is_private) {
                    this.item = this.searchedUsers.find(item => item.id === user_id);
                    this.item.status = 1
                } else {
                    this.friendship.status = 1
                }
            }).catch((error) => {
                if (error.response.status == 401) {
                    this.$router.push('/login')
                }
            })
        },

        async cancelFriendRequest(user_id) {
            await axios.get(`/api/reject-or-remove-friend/${user_id}`)
            .then(() => {
                if (this.host.is_private) {
                    this.item = this.searchedUsers.find(item => item.id === user_id);
                    this.item.status = 0
                } else {
                    this.friendship.status = 0
                }
            })
        },

        async acceptFriendRequest(user_id) {
            await axios.get(`/api/accept-or-add-friend/${user_id}`)
            .then(() => {
                if (this.host.is_private) {
                    this.item = this.searchedUsers.find(item => item.id === user_id);
                    this.item.status = 3
                    this.friends.push(this.item)
                } else {
                    this.friendship.status = 3
                }

            })
        },

        async rejectFriendRequest(user_id) {
            await axios.get(`/api/reject-or-remove-friend/${user_id}`)
            .then(() => {
                if (this.host.is_private) {
                    this.item = this.searchedUsers.find(item => item.id === user_id);
                    this.item.status = 0
                } else {
                    this.friendship.status = 0
                }
            })
        },

        async showProfile (id) {
            document.getElementById('friend_close').click()
            this.$router.replace({ name: 'profile', params: { id: id }});
        },

        async toggleSearch() {
            if (this.searchIsActive) {
                this.searchIsActive = false
            } else {
                this.searchIsActive = true
            }
        },

        async search() {

            clearTimeout(this.timeoutId);

            this.timeoutId = setTimeout(() => {
                if (this.searchInput.length > 1) {
                    axios.post('/api/search/user-friend', {
                        input: this.searchInput
                    }).then(response => {
                        console.log(response.data.data)
                        this.searchedUsers = response.data.data
                    })
                }
            }, 800);
        },
    },
}
</script>

<style scoped>

</style>
