import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './App.vue'
import axios from 'axios';
import VueAxios from "vue-axios";
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap-vue/dist/bootstrap-vue.css';



import Home from './Components/Home'
import Post from './Components/Post'
import Photo from "./Components/Photo"
import Todo from "./Components/Todo"
import User from "./Components/User";

Vue.use(VueRouter);
Vue.use(BootstrapVue);
Vue.use(VueAxios, axios);


const router = new VueRouter({
    routes: [
      {
        path: '/',
        name: 'home',
        component: Home
      },
      {
        path: 'post/:id',
        name: 'post',
        component: Post,
        props: true
      },
      {
        path: 'photo/:id',
        name: 'photo',
        component: Photo,
        props: true
      },
      {
        path: 'todos/:id',
        name: 'todo',
        component: Todo,
        props: true
      },
      {
        path: 'user/:id',
        name: 'user',
        component: User,
        props: true
      }
    ]
});


new Vue({
  el: '#app',
  render: h => h(App),
  router
})
