<template>
    <div class="container">
      <h1 class="text-center">Users</h1>
      <div class="row justify-content-center">
        <table class="table table-hover">
          <thead>
          <tr>
            <td>#</td>
            <td>UserName</td>
            <td>Name</td>
            <td>Email</td>
            <td>Location</td>
          </tr>
          </thead>
          <tbody>
              <tr v-for="(user, index) in users">
                <td>{{index + 1}}</td>
                <td>{{user.username}}</td>
                <td>{{user.name}}</td>
                <td>{{user.email}}</td>
                <td>{{user.address.street}}</td>
              </tr>
          </tbody>
        </table>
      </div>
    </div>
</template>

<script>
    export default {
        name: "Home",
        props: ['url'],
        data(){
            return {
                users: []
            }
        },
        methods: {
            setUsers() {
                this.axios.get(this.url + "/users").then((response)=>{this.users = response.data; console.log(response.data)});
                return this;
            }
        },
        created() {
            this.setUsers();
        }
    }
</script>

<style scoped>

</style>
