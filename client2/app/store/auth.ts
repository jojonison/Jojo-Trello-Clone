import { defineStore } from "pinia";

export const useAuthStore = defineStore('auth',  () => {
    const api = useNuxtApp().$api;
    const email = ref<string | null>(null);
    const name = ref<string | null>(null);
    const isLoggedIn = computed(() => {
        return !!email.value;
    })

    async function login(emailInput: string, passwordInput: string) {
        return api.post<{ email: string; name: string }>('logIn', {
            email: emailInput,
            password: passwordInput
        })
            .then(response => {
                email.value = response.data.email;
                name.value = response.data.name;
            })
    }

    async function logout() {
        return api
            .post('logOut')
            .finally(() => {
                email.value = null;
                name.value = null;
            });
    }
    return {email, name, isLoggedIn, login, logout};
}, {
    persist: {storage: piniaPluginPersistedstate.localStorage()},
});