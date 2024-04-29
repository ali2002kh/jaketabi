<template>
    <div class="container my-5">
        <div class="row">
            <div class="col-sm-6 m-10">
                <div class="alert alert-danger" v-if="hasError">
                    <ul>
                        <li v-for="e in errors" :key="e">{{ e[0] }}</li>
                    </ul>
                </div>
                <form>
                    <div class="m-3">
                        <label for="email" class="form-label">ایمیل</label>
                        <input type="email" class="form-control" id="email" 
                        placeholder="name@example.com" name="email"
                        v-model="email"
                        >
                    </div>
                    <div class="m-3">
                        <label for="username" class="form-label">نام کاربری</label>
                        <input type="text" class="form-control" id="username" 
                         name="username"
                        v-model="username"
                        >
                    </div>
                    <div class="m-3">
                        <label for="number" class="form-label">شماره تلفن</label>
                        <input type="text" class="form-control" id="number" 
                        placeholder="09xxxxxxxxx" name="number"
                        v-model="number"
                        >
                    </div>
                    <div class="m-3">
                        <label for="password" class="form-label">رمز عبور</label>
                        <input type="password" class="form-control" 
                        id="password" name="password"
                        v-model="password"
                        >
                    </div>
                    <div class="m-3">
                        <label for="confirmPassword" class="form-label">تایید رمز عبور</label>
                        <input type="password" class="form-control" 
                        id="confirmPassword" name="confirmPassword"
                        v-model="confirmPassword"
                        >
                    </div>
                    <div class="m-3 d-grid">
                        <button type="submit" class="btn btn-dark m-3" 
                        @click.prevent="signup"
                        :disabled="loading"
                        >ثبت نام</button>
                        <router-link class="nav-link text-center" :to="{name: 'login'}">ورود</router-link>
                    </div>
                </form>
            </div>
        </div> 
    </div>
</template>

<script>
export default {

    data() {
        return {
            username: null,
            email: null,
            number: null,
            password: null,
            confirmPassword: null,
            loading: false,
            hasError: false,
            errors: [],
        }
    },
    methods: {
        async signup () {
            this.loading = true;

            try {
                await axios.get("/sanctum/csrf-cookie")
                await axios.post("/api/signup", {
                    username: this.username,
                    email: this.email,
                    number: this.number,
                    password: this.password,
                    confirmPassword: this.confirmPassword,
                }).then(response => {
                    this.$router.push('/');
                })
            } catch (error) {
                if (error.response && 
                    error.response.status && 
                    error.response.status == 422) {
                        this.hasError = true
                        console.log(error.response.data)
                        this.errors = error.response.data.errors
                }
            } finally {
                this.loading = false;
            }
        }  
    }, 
}
</script>