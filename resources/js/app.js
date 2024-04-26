require('./bootstrap')
import { createApp } from 'vue'
import Index from './Index'
import router from './routes'
import store from './store'

const app = createApp({})

app.component('index', Index)

app.use(router).use(store).mount('#app')


