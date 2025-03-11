<template>
    <div class="shelf-title row mx-2 bg-dark rounded-top text-white bg-gradient">
        <p class="text-center mx-auto fw-bold m-1"> {{ shelf.name }} </p>
    </div>
    <div class="shelf-books row flex-row-reverse justify-content-start align-items-center bg-light mx-2
        p-1 border-start border-end border-bottom rounded-bottom"
        style="min-height:130px;" >
        <div v-for="b in shelf.books" :key="b.id" class="col-auto p-2">
            <img :src="b.image" class="shelf-book-img mx-1" alt="">
        </div>
        <router-link :to="{name:'shelf', params:{id: shelf.id}}" class="col-auto me-auto p-2">
            <i class="fa-solid fa-angle-left fa-xl text-dark"></i>
        </router-link>
    </div>
</template>

<script>
export default {
  props: {
    shelf: {
      type: Object,
      required: true
    }
  }
};
</script>

<style scoped>

.shelf-book-img {
    max-width: 80px;
    max-height: 120px;
}

</style>

