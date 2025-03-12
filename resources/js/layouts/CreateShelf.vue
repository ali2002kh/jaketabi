<template>
    <p class="mt-5 " data-bs-toggle="modal" data-bs-target="#createShelf">
        <a href="#" class="link-dark text-center d-flex align-items-center" style="text-decoration:none;">
            ایجاد قفسه جدید
            <i class="fa-solid fa-plus px-2"></i>
        </a>
    </p>

    <!-- create shelf modal  -->
    <div class="modal fade" id="createShelf" tabindex="-1" aria-labelledby="createShelfLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 w-100 text-center" id="createShelfLabel">ایجاد قفسه جدید</h1>
                    <button id="create_shelf_close" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-end">
                    <div class="alert alert-danger" v-if="hasError" dir="rtl">
                        <ul class="error-list">
                            <li v-for="e in errors" :key="e" class="error-item">{{ e[0] }}</li>
                        </ul>
                    </div>
                    <div class="alert alert-success" v-if="success" dir="rtl">{{ message }}</div>
                    <form>
                        <div class="m-1">
                            <label for="shelfName" class="form-label ">نام قفسه</label>
                            <input type="text" class="form-control text-end" id="shelfName" name="shelfName"
                            v-model="shelfName"
                            >
                        </div>
                        <div class="m-1">
                            <label for="shelfDescription" class="form-label ">توضیحات</label>
                            <textarea id="shelfDescription" class="form-control text-end" name="shelfDescription" v-model="shelfDescription"></textarea>
                        </div>
                        <div class="m-1 text-start">
                            <button type="submit" class="btn btn-dark m-3 ms-0 px-3"
                            @click.prevent="storeShelf"
                            >تایید</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>


<script>

export default {

    data() {
        return {
            shelfName: null,
            shelfDescription: null,
            hasError: false,
            errors: [],
            success: false,
            message: null,
        }
    },

    emits: ['addShelf'],

    methods: {
        async storeShelf() {
            this.hasError = false
            this.errors = []
            this.success = false
            this.message = null
            await axios.post('/api/store-shelf', {
                shelfName: this.shelfName,
                description: this.shelfDescription
            }).then((response) => {
                this.success = true;
                this.message = response.data.message
                this.$emit('addShelf', response.data.data.shelf);
            }).catch ((error) => {
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
