<template>
    <div class="todo ">
        {{todo.title}}
    </div>
</template>

<script>
    export default {
        name: "Todo",
        props: ['id'],
        data () {
            return {
                todo: null
            }
        },
        methods: {
            getTodo (id) {
                this.axios.get('https://jsonplaceholder.typicode.com/todos/' + id)
                    .then((response)=>this.todo = response.data)
                    .catch((error) => console.log(error));
                return this;
            }
        },
        created() {
            this.getTodo(this.id);
        },
        watch: {
            '$route' () {
                this.getTodo(this.id);
            }
        }
    }
</script>

<style scoped>

</style>
