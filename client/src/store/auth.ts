import { computed, inject, ref } from "vue";
import { defineStore } from "pinia";
import {AxiosInstance} from "axios";

export const useAuthStore = defineStore('auth',  () => {
    const injectedApi = inject<AxiosInstance>("api");
    if (!injectedApi) {
        throw new Error("API instance not provided");
    }
    const api = injectedApi;
    const email = ref<string | null>(null);
    const name = ref<string | null>(null);
    const isLoggedIn = computed(() => {
        return !!email.value;
    })

    function login(emailInput: string, passwordInput: string) {
        return api.post<{ email: string; name: string }>('logIn', {
            email: emailInput,
            password: passwordInput
        })
            .then(response => {
                email.value = response.data.email;
                name.value = response.data.name;
            })
    }

    function logout() {
        return api
            .post('logOut')
            .finally(() => {
                email.value = null;
                name.value = null;
            });
    }
    return {email, name, isLoggedIn, login, logout};
});