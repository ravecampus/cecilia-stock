require('./bootstrap');
import {createApp} from 'vue'

import App from './App.vue'
import axios from 'axios'
import router from './router'

window.Title ={'app_name':process.env.MIX_APP_NAME};
axios.defaults.withCredentials = true;
axios.defaults.baseURL = '/';
const app = createApp(App)


app.config.globalProperties.$axios = axios;

app.use(router)
app.mount('#app')
