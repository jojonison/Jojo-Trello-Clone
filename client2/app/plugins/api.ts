import { defineNuxtPlugin } from '#app'
import axios from 'axios'

export default defineNuxtPlugin(async () => {
    const api = axios.create({
        baseURL: 'http://127.0.0.1:8000/api',
        withCredentials: true,
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
    })

    try {
        await api.get('/csrf-cookie')
    } catch (e) {
        console.warn('CSRF init failed:', e)
    }

    return {
        provide: {
            api,
        },
    }
})
