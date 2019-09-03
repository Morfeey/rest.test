<template lang="html">
  <div class="post" v-if="post">
    <h1 class="post__title">{{ post.title }}</h1>
    <p class="post__body">{{ post.body }}</p>
    <p class="post__id">{{ post.id }}</p>
  </div>
</template>

<script>
    export default {
        name: "Post",
        props: ['id'],
        data () {
            return {
                post: null
            }
        },
        methods: {
            getPost (id) {
                this.axios.get('https://jsonplaceholder.typicode.com/posts/' + id)
                    .then((response)=> this.post = response.data)
                    .catch((error)=> console.log(error));
            }
        },
        created() {
            this.getPost(this.id);
        },
        watch: {
            '$route' () {
                this.getPost(this.id)
            }
        }
    }
</script>

<style scoped>

</style>
