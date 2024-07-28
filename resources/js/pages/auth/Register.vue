<template>
    <div class="container my-5" dir="rtl">
        <div class="row">
            <div class="col-sm-6 mx-auto">
                <div class="alert alert-danger" v-if="hasError" dir="rtl">
                    <ul class="error-list">
                        <li v-for="e in errors" :key="e" class="error-item">{{ e[0] }}</li>
                    </ul>
                </div>
                <form dir="rtl">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">ایمیل</label>
                                <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email" v-model="email">
                            </div>
                            <div class="form-group mb-3">
                                <label for="username" class="form-label">نام کاربری</label>
                                <input type="text" class="form-control" id="username" name="username" v-model="username">
                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">رمز عبور</label>
                                <input type="password" class="form-control" id="password" name="password" v-model="password">
                            </div>
                            <div class="form-group mb-3">
                                <label for="confirmPassword" class="form-label">تایید رمز عبور</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" v-model="confirmPassword">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="row">
                                <div class="form-group mb-3">
                                    <label for="number" class="form-label">شماره تلفن</label>
                                    <input type="text" class="form-control" id="number" placeholder="09xxxxxxxxx" name="number" v-model="number">
                                    <div class="m-3" v-if="waitToSend">
                                        لطفا صبر کنید
                                    </div>
                                    <button v-else type="button" class="btn btn-success m-3" @click.prevent="sendOtp" :disabled="sent">
                                        {{ sent ? `ارسال مجدد کد تایید در ${timer} ثانیه` : 'ارسال کد تایید' }}
                                    </button>
                                </div>
                            </div>  
                            <div class="form-group mb-3" v-if="showOtpField">
                                <label for="otp" class="form-label">کد تایید</label>
                                <input type="text" class="form-control" id="otp" name="otp" @keyup="checkOtp" v-model="otp">
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-dark mb-3" @click.prevent="signup" :disabled="loading || otpIsNotGiven">
                            ثبت نام
                        </button>
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
            showOtpField: false,
            token: null,
            otp: null,
            sent: false,
            timer: 120, // 2 minute
            interval: null,
            otpIsNotGiven: true,
            waitToSend: false,
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
                await axios.post(`/api/register/${this.token}`, {
                    username: this.username,
                    email: this.email,
                    password: this.password,
                    confirmPassword: this.confirmPassword,
                    otp: this.otp,
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
                this.loading = false
            }
        },

        async sendOtp () {

            if (this.sent) {
                return;
            }
            this.sent = true
            this.waitToSend = true

            await axios.post("/api/generate-otp", {
                number: this.number,
            }).then(response => {
                console.log(response)
                this.token = response.headers.token
                this.showOtpField = true
                this.interval = setInterval(() => {
                    this.timer--;
                    if (this.timer <= 0) {
                        this.sent = false;
                        this.timer = 120;
                        clearInterval(this.interval);
                    }
                }, 1000);
            }).catch (error => {
                this.sent = false
                if (error.response && 
                    error.response.status && 
                    error.response.status == 422) {
                        this.hasError = true
                        console.log(error.response.data)
                        this.errors = error.response.data.errors
                }  
            }).finally (() => {
                this.waitToSend = false
            })
            
        },

        async checkOtp () {

            if (this.otp.length == 6) {
                this.otpIsNotGiven = false
            } else {
                this.otpIsNotGiven = true
            }
        },
    }, 
}
</script>
