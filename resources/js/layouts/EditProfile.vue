<template>
    <div v-if="host.is_private" class="col-md-2 col-sm-auto col-3 mt-5">
        <div class="text-center">
            <a  href="#" class="text-dark link-underline link-underline-opacity-0">
                <button class="btn btn-dark px-3" data-bs-toggle="modal" data-bs-target="#editProfile">ویرایش</button>
            </a>
        </div>
        <div class="modal fade" id="editProfile" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 w-100 text-center" id="editProfileLabel">ویرایش اطلاعات شخصی</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-end" style="font-size:14px">
                        <div class="alert alert-danger" v-if="hasError" dir="rtl">
                            <ul class="error-list">
                                <li v-for="e in errors" :key="e" class="error-item">{{ e[0] }}</li>
                            </ul>
                        </div>
                        <div class="alert alert-success" v-if="success" dir="rtl">{{ message }}</div>
                        <form style="font-size:14px">
                            <div class="row">
                                <div class="col">
                                    <label for="image" class="form-label pe-2">عکس پروفایل</label>
                                    <input class="form-control text-end" style="font-size:14px"
                                    type="file" id="image" @change="onFileChange($event)">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col">
                                    <label for="email" class="form-label pe-2">ایمیل</label>
                                    <input type="email" class="form-control text-start" style="font-size:14px"
                                    id="email" name="email" v-model="email"
                                    >
                                </div>
                                <div class="col">
                                    <label for="username" class="form-label pe-2">نام کاربری</label>
                                    <input type="text" class="form-control text-start" style="font-size:14px"
                                    id="username" name="username"
                                    v-model="username"
                                    >
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col">
                                    <label for="lname" class="form-label pe-2">نام خانوادگی</label>
                                    <input type="text" class="form-control text-end" style="font-size:14px"
                                        id="lname" name="lname"
                                    v-model="lname"
                                    >
                                </div>
                                <div class="col">
                                    <label for="fname" class="form-label pe-2">نام</label>
                                    <input type="text" class="form-control text-end" style="font-size:14px"
                                        id="fname" name="fname"
                                    v-model="fname"
                                    >
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col">
                                    <label for="confirmPassword" class="form-label pe-2">تایید رمز عبور</label>
                                    <input type="password" class="form-control text-start"  style="font-size:14px"
                                    id="confirmPassword" name="confirmPassword"
                                    v-model="confirmPassword"
                                    >
                                </div>
                                <div class="col">
                                    <label for="password" class="form-label pe-2">رمز عبور</label>
                                    <input type="password" class="form-control text-start" style="font-size:14px"
                                    id="password" name="password" v-model="password"
                                    >
                                </div>
                            </div>

                            <div class="row ms-1" >
                                <button type="submit" class="col-auto btn btn-sm btn-dark mt-3 px-3 me-2"
                                @click.prevent="updateProfile"
                                >بروزرسانی</button>
                                <button type="button" class="col-auto btn btn-sm btn-secondary px-3 mt-3"
                                data-bs-dismiss="modal" aria-label="Close">لغو</button>
                            </div>
                        </form>
                    </div>
                </div>
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
            file: '',
            email: null,
            username: null,
            fname: null,
            lname: null,
            password: null,
            confirmPassword: null,
            hasError: false,
            errors: [],
            success: false,
            message: null,
        }
    },

    beforeMount() {
        this.email = this.host.email
        this.username = this.host.username
        this.fname = this.host.fname
        this.lname = this.host.lname
    },

    emits: ['profileReact'],

    methods: {

        async onFileChange(event) {
            this.file = event.target.files[0]
        },

        async updateProfile() {
            this.hasError = false
            this.errors = []
            this.success = false
            this.message = null


            let fd = new FormData()

            fd.append('fname', this.fname ?? '')
            fd.append('lname', this.lname ?? '')
            fd.append('email', this.email ?? '')
            fd.append('username', this.username ?? '')
            fd.append('password', this.password ?? '')
            fd.append('confirmPassword', this.confirmPassword ?? '')
            fd.append('file', this.file)
            fd.append('_method', 'POST')

            await axios.post('/api/update-profile', fd,
                {
                    headers: {
                    'Content-Type': `multipart/form-data; boundary=${fd._boundary}`
                    }
                }
            ).then(response => {
                this.success = true;
                this.message = response.data.message
                this.$emit('profileReact', this.fname, this.lname, this.username);
            }).catch (error => {
                if (error.response &&
                    error.response.status &&
                    error.response.status == 422) {
                        this.hasError = true
                        console.log(error.response.data)
                        this.errors = error.response.data.errors
                }
            })

        },
    },
}

</script>

<style scoped>

</style>

