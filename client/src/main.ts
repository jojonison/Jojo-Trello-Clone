import { createApp } from 'vue'
import './assets/css/style.css'
import App from './App.vue'
import { router } from './routes'
import { pinia } from './store'
import axios from "axios";

const api = axios.create({
    baseURL: "http://127.0.0.1:8000/api",
    withCredentials: true,
    withXSRFToken: true,
    headers: {
        Accept: "application/json",
        Authorization: null,
        "Content-Type": "application/json"
    }
});

const init = async () => {
    const app = createApp(App);
    app.use(router);
    app.use(pinia);
    await api.get("/csrf-cookie").catch((e) => e);
    app.provide("api", api);
    app.mount('#app');
}

init();
