<template>
    <router-link :to="{name: 'book', params: {id: book.id}}" class="router-links">
        <img class="book-img d-block border mx-auto" :src="book.image" alt="">
        <p class="book-title mx-auto text-center p-1">{{ book.name }}</p>
    </router-link>
</template>

<script>
export default {
  props: {
    book: {
      type: Object,
      required: true
    }
  }
};
</script>

<style scoped>
.book-img {
    width: 110px;
    height: 160px;
}

.book-title {
    direction: rtl;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 120px;
}

.router-links {
    color: black;
    text-decoration: none;
}
</style>
