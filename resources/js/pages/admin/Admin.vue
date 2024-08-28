<template>
    <div class="container-fluid" v-if="user && user.role">
    <div class="row flex-row-reverse text-end flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-light">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <div class="row " id="menu">
                    <div class="nav-item col-12 p-2 sidebar-link text-dark rounded-1 fs-5">
                        <router-link :to="{name: 'dashboard'}" class="router-links nav-link align-middle px-0">
                             <span class="ms-1 d-none d-sm-inline">داشبورد</span> <i class="fa ps-2 fa-dashboard "></i>
                        </router-link>
                    </div>
                    <div class="nav-item col-12 p-2 sidebar-link text-dark rounded-1 fs-5">
                        <router-link :to="{name: 'books'}" class="router-links nav-link align-middle px-0">
                            <span class="ms-1 d-none d-sm-inline">کتاب ها</span>  <i class="fa ps-2 fa-book"></i>
                        </router-link>
                    </div>
                    <div class="nav-item col-12 p-2 sidebar-link text-dark rounded-1 fs-5" v-if="user.role.role_id == 4">
                        <router-link :to="{name: 'publisher-admins'}" class="router-links nav-link align-middle px-0">
                            <span class="ms-1 d-none d-sm-inline">ادمین های نشر</span>  <i class="fa ps-2 fa-user-group"></i>
                        </router-link>
                    </div>
                    <div class="nav-item col-12 p-2 sidebar-link text-dark rounded-1 fs-5" v-if="user.role.role_id == 1 || user.role.role_id == 2">
                        <router-link :to="{name: 'publishers'}" class="router-links nav-link align-middle px-0">
                            <span class="ms-1 d-none d-sm-inline">ناشران</span>  <i class="fa ps-2 fa-store"></i>
                        </router-link>
                    </div>
                    <div class="nav-item col-12 p-2 sidebar-link text-dark rounded-1 fs-5" v-if="user.role.role_id == 2">
                        <router-link :to="{name: 'admins'}" class="router-links nav-link align-middle px-0">
                             <span class="ms-1 d-none d-sm-inline">ادمین ها</span> <i class="fa ps-2 fa-user-group"></i>
                        </router-link>
                    </div>
                </div>
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
<style scoped>
.container-fluid {
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}
.router-links {
    text-decoration: none;
}
.sidebar-link {
    transition: all 0.2s;
}
.sidebar-link:hover {
    color: white !important;
    background-color: rgb(33, 37, 41);
}
</style>