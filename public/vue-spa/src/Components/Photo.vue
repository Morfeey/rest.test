<template>
    <div class="photo-container">
      <h1>{{photo.title}}</h1>
      <img v-bind:src="photo.url" v-bind:alt="photo.title">
    </div>
</template>

<script>
    export default {
        name: "Photo",
        props: ['id'],
        data () {
            return {
                photo: null
            }
        },
        methods: {
            getPhoto (id) {
                this.axios.get('https://jsonplaceholder.typicode.com/photos/' + id)
                    .then((response)=> this.photo = response.data)
                    .catch((response)=>console.log(response));
                return this;
            }
        },
        created () {
            this.getPhoto(this.id);
        },
        watch: {
            '$route' () {
                this.getPhoto(this.id);
            }
        }
    }
</script>

<style scoped>

</style>
