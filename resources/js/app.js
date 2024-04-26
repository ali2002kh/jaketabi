require('./bootstrap')
import * as Vue from 'vue'
import { createStore } from 'vuex'
import Index from './Index'
import router from './routes'
import axios from "axios";

const actions = {
    
};



const store = createStore({
    
    state () {
      return {
        user: null
      }
    },
    actions: {
        async initState({commit}) {
            await axios.get('/api/user')
            .then((response) => {commit('startState', response.data.data)})
            // .catch((err) => console.log(err))
        }
    },
    getters: {
        user: state => state.user
    },
    
    mutations: {
        startState: (state, payload) => state.user = payload
    }
  })

const app = Vue.createApp({})

app.component('index', Index)

app.use(router).use(store).mount('#app')


