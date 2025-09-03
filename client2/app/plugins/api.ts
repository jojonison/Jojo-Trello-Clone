// plugins/api.ts
import { defineNuxtPlugin } from '#app'
import axios from 'axios'

export default defineNuxtPlugin(async () => {
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

    await api.get('/csrf-cookie').catch((e) => e);

    return {
        provide: {
            api,
        },
    }
})
