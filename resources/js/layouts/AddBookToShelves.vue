<template>
    <div class="row shelves-modal text-center p-4 m-1 py-2">
        <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#shelves-modal">
            اضافه کردن به قفسه (ها)
        </button>
    </div>

    <!-- Modal -->
    <teleport to="body">
        <div class="modal fade" id="shelves-modal" tabindex="-1" aria-labelledby="shelves-modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title w-100 text-center fs-5" id="shelves-modalLabel">قفسه ها</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div v-for="s in shelves" :key="s.id">
                            <div class="row flex-row-reverse align-items-center">
                                <div class="col fw-bold text-end">
                                    {{s.name}}
                                </div>
                                <div v-if="shelves_with_this_book.includes(s.id)" class="float-start col lh-lg">
                                    <button class="btn btn-sm btn-danger" @click.prevent="removeFromShelf(s.id)">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                </div>
                                <div v-else class="float-start col lh-lg">
                                    <button class="btn btn-sm btn-dark" @click.prevent="addToShelf(s.id)">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    </teleport>


</template>


<script>

export default {

    props: {
        record: {
            type: Object,
            required: true
        },
    },

    data() {
        return {
            shelves: [],
            shelves_with_this_book: [],
        }
    },

    created() {
        console.log("helllllllo"+this.record)

        axios.get(`/api/shelves/${this.record.user.id}`)
        .then(response => {
            console.log(response.data.data)
            this.shelves = response.data.data;

            if (this.record.shelves_with_this_book) {
                this.shelves_with_this_book = this.record.shelves_with_this_book
            }
        });
    },

    methods: {
        async addToShelf(shelf_id) {
            await axios.get(`/api/add-book-to-shelf/${shelf_id}/${this.$route.params.id}`)
            .then(() => {
                this.shelves_with_this_book.push(shelf_id)
            })
        },

        async removeFromShelf(shelf_id) {
            await axios.get(`/api/remove-book-from-shelf/${shelf_id}/${this.$route.params.id}`)
            .then(() => {
                this.shelves_with_this_book = this.shelves_with_this_book.filter(item => item!== shelf_id);
            })
        },
    },
}
</script>

<style scoped>

</style>
