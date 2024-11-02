<template>
<div class="body bg-dark">
  <div class="container">
    <div class="row d-flex justify-content-center align-items-center">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-2-strong my-4 rounded-4">
          <div class="card-body p-5">

            <h3 class="mb-5 text-center">ورود</h3>

            <div class="alert alert-danger" v-if="hasError" dir="rtl">
                <ul class="error-list">
                    <li v-for="e in errors" :key="e" class="error-item">{{ e[0] }}</li>
                </ul>
            </div>

            <div data-mdb-input-init class="form-outline mb-4 text-end">              
                <label for="emailOrNumberOrUsername" class="form-label">نام کاربری (ایمیل/ شماره موبایل)</label>
                <input type="email" class="form-control form-control-lg" 
                    id="emailOrNumberOrUsername"
                    name="emailOrNumberOrUsername"
                    v-model="emailOrNumberOrUsername"/>
            </div>

            <div data-mdb-input-init class="form-outline mb-4 text-end">
                <label class="form-label" for="password">رمز عبور</label>
                <input type="password" class="form-control form-control-lg" 
                    id="password" 
                    name="password"
                    v-model="password" />
            </div>

            <!-- Checkbox -->
            <!-- <div class="form-check d-flex justify-content-start mb-4">
              <input class="form-check-input" type="checkbox" value="" id="form1Example3" />
              <label class="form-check-label" for="form1Example3"> Remember password </label>
            </div> -->

            <button data-mdb-button-init data-mdb-ripple-init class="btn btn-dark p-4 py-2 text-center" type="submit"
            @click.prevent="login">ورود</button>

            <hr class="my-4">
            <router-link class="nav-link text-end" :to="{name: 'register'}">ثبت نام</router-link>


          </div>
        </div>
      </div>
    </div>
  </div>
</div>
    <!-- <div class="container my-5">
        <div class="row">
            <div class="col-sm-6 m-10">
                <div class="alert alert-danger" v-if="hasError" dir="rtl">
                    <ul class="error-list">
                        <li v-for="e in errors" :key="e" class="error-item">{{ e[0] }}</li>
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
    </div> -->
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
<style scoped>
    .body {
        min-height: 100vh !important;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }
</style>