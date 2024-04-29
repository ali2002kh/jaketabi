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
                        <label for="emailOrNumberOrUsername" class="form-label">ایمیل یا شماره تلفن یا نام کاربری</label>
                        <input type="text" class="form-control" 
                        id="emailOrNumberOrUsername"
                        name="emailOrNumberOrUsername"
                        v-model="emailOrNumberOrUsername"
                        >
                    </div>
                    <div class="m-3">
                        <label for="password" class="form-label">رمز عبور</label>
                        <input type="password" class="form-control" 
                        id="password" name="password"
                        v-model="password"
                        >
                    </div>
                    <div class="m-3 d-grid">
                        <button type="submit" class="btn btn-dark m-3" :disabled="loading"
                        @click.prevent="login"
                        >ورود</button>
                        <router-link class="nav-link text-center" :to="{name: 'signup'}">ثبت نام</router-link>
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
            emailOrNumberOrUsername: null,
            password: null,
            loading: false,
            hasError: false,
            errors: [],
        }
    },
    methods: {
        async login () {
            this.loading = true;

            try {
                await axios.get("/sanctum/csrf-cookie")
                await axios.post("/api/login", {
                    emailOrNumberOrUsername: this.emailOrNumberOrUsername,
                    password: this.password,
                }).then(response => {
                    this.$router.push('/') 
                })
            } catch (error) {
                if (error.response && error.response.status) {
                    this.hasError = true
                    console.log(error.response.data)
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors
                    } else if (error.response.status == 421) {
                        var e = []
                        e[0] = error.response.data.message
                        this.errors[0] = e
                        console.log(this.errors)
                    }     
                }
            }  finally {
                this.loading = false;
            }
        }  
    }, 
}
</script>