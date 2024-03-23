<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container-fluid">
                <ul class="nav navbar-nav me-auto">
                    <li class="nav-item ps-4">
                        <a class="nav-link" href="#">
                            <img class="w-50" src="storage/icons/bell.png" alt="">
                        </a>
                    </li>
                    <li class="nav-item ps-2">
                        <a class="nav-link" href="#">
                            <img class="w-50" src="storage/icons/search.png" alt="">
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav justify-content-center">
                    <li class="nav-item">
                        <p class="navbar-text text-white fs-5 my-2">ج‍‍‍‍اکتابی</p>
                    </li>
                </ul>
                <ul class="nav navbar-nav ms-auto">
                    <li class="nav-item ps-4">
                        <a class="nav-link" href="#">
                            <img class="w-50" src="storage/icons/profile-white.png" alt="">
                        </a>
                    </li>
                    <li class="nav-item ps-2">
                        <a class="nav-link" href="#">
                            <img class="w-50" src="storage/icons/white-home.png" alt="">
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
</template>

<script>
export default {
    data() {
        return {
            
        } 
    },
    created() {
        // axios.get('/api/categories')
        // .then(response => {
        //     console.log(response.data.data)
        //     this.categories = response.data.data;
        // });

        // axios.get('/api/user')
        // .then(response => {
        //     console.log(response.data.data)
        //     this.user = response.data.data;
        //     this.isLoggedIn = true;
        // }).catch(error => {
        //     if (error.response && 
        //     error.response.status && 
        //     error.response.status == 401) {
        //         this.isLoggedIn = false;
        //         this.user = null;
        //     };
        // });
    },
    // methods: {
    //     async logout () {
    //         axios.post('/logout')
    //         .then(response => {
    //             this.isLoggedIn = false;
    //             this.user = null;
    //             this.$router.push('/login')
    //         })
    //     },

    //     async search() {
    //         axios.post('/api/search', {
    //             input: this.input
    //         }).then(response => {
    //             console.log(response.data.data)
    //             this.products = response.data.data
    //         })
    //     },

    //     async showProduct (product_id) {
    //         document.getElementById('close').click()
    //         // this.$router.push('/product/' + product_id)
    //         this.$router.replace({ name: 'product', params: { id: product_id }});
    //     }
    // },
}
</script>

<style scoped>
.item-img {
    width : 80px;
    height: 80px;
    border-radius: 10px;
}
</style>