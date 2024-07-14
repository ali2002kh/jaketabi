<template>
    <div class="container-fluid" v-if="user && user.role">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                    <li class="nav-item">
                        <router-link :to="{name: 'dashboard'}" class="nav-link align-middle px-0">
                            <i class="fa fa-dashboard"></i> <span class="ms-1 d-none d-sm-inline">داشبورد</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{name: 'books'}" class="nav-link align-middle px-0">
                            <i class="fa fa-shopping-bag"></i> <span class="ms-1 d-none d-sm-inline">کتاب ها</span>
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="user.role.role_id == 4">
                        <router-link :to="{name: 'publisher-admins'}" class="nav-link align-middle px-0">
                            <i class="fa fa-gift"></i> <span class="ms-1 d-none d-sm-inline">ادمین های نشر</span>
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="user.role.role_id == 1 || user.role.role_id == 2">
                        <router-link :to="{name: 'publishers'}" class="nav-link align-middle px-0">
                            <i class="fa fa-windows"></i> <span class="ms-1 d-none d-sm-inline">ناشران</span>
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="user.role.role_id == 2">
                        <router-link :to="{name: 'admins'}" class="nav-link align-middle px-0">
                            <i class="fa fa-windows"></i> <span class="ms-1 d-none d-sm-inline">ادمین ها</span>
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col py-3">
            <router-view></router-view>
        </div>
    </div>
</div>
</template>

<script>
import { mapState } from 'vuex';

export default {
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
            }
        })
    },

    
    computed: {
        ...mapState({
            user: state => state.user.data,
            loggedIn: state => state.user.loggedIn
        }),
    },
}
</script>