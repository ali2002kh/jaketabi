<template>
    <div class="container">
        <form class="row">
            <div class="col-sm-12">
                <div class="alert alert-danger" v-if="hasError">
                    <ul>
                        <li v-for="e in errors" :key="e">{{ e[0] }}</li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="m-1">
                    <label for="author" class="form-label">نویسنده *</label>
                    <input type="text" class="form-control" required
                    id="author" name="author" v-model="author"
                    >
                </div>
                <div class="m-1">
                    <label for="translator" class="form-label">مترجم</label>
                    <input type="text" class="form-control" 
                    id="translator" name="translator" v-model="translator"
                    >
                </div>
                <div class="m-1">
                    <label for="lcc" class="form-label">رده‌بندی کنگره</label>
                    <input type="text" class="form-control" 
                    id="lcc" name="lcc" v-model="lcc"
                    >
                </div>
                <div class="m-1">
                    <label for="ddc" class="form-label">رده‌بندی دیویی</label>
                    <input type="text" class="form-control" 
                    id="ddc" name="ddc" v-model="ddc"
                    >
                </div>
                <div class="m-1">
                    <label for="isbn_period" class="form-label">ISBN دوره</label>
                    <input type="text" class="form-control" 
                    id="isbn_period" name="isbn_period" v-model="isbn_period"
                    >
                </div>
            </div>
            <div class="col-sm-5">
                <div class="m-1">
                    <label for="isbn" class="form-label">* ISBN</label>
                    <input type="text" class="form-control" required
                    id="isbn" name="isbn" v-model="isbn"
                    >
                </div>
                <div class="m-1">
                    <label for="name" class="form-label">نام کتاب *</label>
                    <input type="text" class="form-control" required
                    id="name" name="name" v-model="name"
                    >
                </div>
                <div class="m-1">
                    <label for="description" class="form-label">توضیحات</label>
                    <textarea id="description" class="form-control" name="description" v-model="description"></textarea>
                </div>
                <div class="row my-1">
                    <div class="col-sm-3 m-1">
                        <label for="page_count" class="form-label">تعداد صفحه</label>
                        <input type="number" class="form-control" id="page_count" name="page_count"
                        v-model="page_count"
                        >
                    </div>
                    
                </div>
                <div class="m-1">
                    <label for="category_id" class="form-label">دسته‌بندی *</label>
                    <select class="form-control w-50" id="category_id" required
                    name="category_id" v-model="category_id">
                        <option disabled value="">انتخاب کنید</option>
                        <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                    </select>
                </div>

                <div class="m-1" v-if="choosePublisher">
                    <label for="publisher_id" class="form-label">نشر *</label>
                    <select class="form-control w-50" id="publisher_id" required
                    name="publisher_id" v-model="publisher_id">
                        <option disabled value="">انتخاب کنید</option>
                        <option v-for="p in publishers" :key="p.id" :value="p.id">{{ p.name }}</option>
                    </select>
                </div>

                <div class="m-1">
                    <label for="image" class="form-label">عکس کتاب</label>
                    <input class="form-control" type="file" id="image" @change="onFileChange($event)">
                </div>

            </div>
            <div>
                <p class="mt-5 mb-0 w-25" data-bs-toggle="modal" data-bs-target="#selectGenre">
                    <a href="#" class="link-dark text-center" style="text-decoration:none;">ژانرها</a>
                </p>

                <div class="modal fade" id="selectGenre" tabindex="-1" aria-labelledby="selectGenreLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">ژانرهای مورد نظر را انتخاب کنید</h5>
                                <button id="genres_close" type="button" class="btn-close  me-0" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <ul>
                                    <li v-for="g in genres" :key="g.id">
                                    <input type="checkbox" v-model="selectedGenres" :value="g.id">
                                    {{ g.name }}
                                    </li>
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="col-auto btn btn-sm btn-secondary px-3 mt-3"
                                        data-bs-dismiss="modal" aria-label="Close">لغو</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-1 d-grid">
                <button type="submit" class="btn btn-dark m-3" 
                @click.prevent="create"
                >تایید</button>
            </div>
        </form>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    data() {
        return {
            // fields
            isbn: null,
            name: null,
            file: '',
            author: null,
            category_id: null,
            // release_date: null,
            publisher_id: null,
            description: null,
            translator: null,
            page_count: null,
            lcc: null,
            ddc: null,
            isbn_period: null,
            selectedGenres: [],
            
            // helpers
            categories: null,
            genres: [],
            choosePublisher: false,
            publishers: null,
            hasError: false,
            errors: [],
        }
    },
    created() {
        console.log(this.user)
        axios.get('/api/book-categories')
        .then(response => {
            this.categories = response.data.data
        })

        axios.get('/api/genres')
        .then(response => {
            this.genres = response.data.data
        })
    },

    beforeMount() {
        if (this.user.role.role_id == 3 || this.user.role.role_id == 4) {
            this.publisher_id = this.user.publisher.id
        } else {
            axios.get('/api/publishers')
            .then(response => {
                this.publishers = response.data.data
                this.choosePublisher = true
            })
        }
    },

    methods: {
        async onFileChange(event) {
            this.file = event.target.files[0]
        },

        async create() {
            console.log(this.details)

            let fd = new FormData()

            console.log(this)
            
            fd.append('isbn', this.isbn ?? '')
            fd.append('name', this.name ?? '')
            fd.append('file', this.file)
            fd.append('author', this.author ?? '')
            fd.append('category_id', this.category_id ?? '')
            // fd.append('release_date', this.release_date ?? '')
            fd.append('publisher_id', this.publisher_id ?? '')
            fd.append('description', this.description ?? '')
            fd.append('translator', this.translator ?? '')
            fd.append('page_count', this.page_count ?? '')
            fd.append('lcc', this.lcc ?? '')
            fd.append('ddc', this.ddc ?? '')
            fd.append('isbn_period', this.isbn_period ?? '')
            fd.append('genres', this.selectedGenres ?? '')
            
            fd.append('_method', 'POST')

            await axios.post('/api/store-book', fd,
                {
                    headers: {
                    'Content-Type': `multipart/form-data; boundary=${fd._boundary}`
                    }
                } 
            ).then(() => {
                this.$router.push('/admin/books')
            }).catch(error => {
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
            })
        },
    },

    computed: {
        ...mapState({
            user: state => state.user.data,
            loggedIn: state => state.user.loggedIn
        }),
    },
}

</script>


