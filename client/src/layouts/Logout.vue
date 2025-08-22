<script setup lang = "ts">
import { useAuthStore } from '@/store/auth';
import { router } from '@/routes';

const auth = useAuthStore();

function logOut() {
    auth.logout()
    .then(response => {})
    .catch(error => {})
    .finally(() => {
        router.push({name:'homepage'});
    });
}
</script>

<template>
    <div>
        <button 
            @click="logOut" 
            class="w-full mt-6 py-2 px-4 rounded-full border-2 border-yellow-800 bg-yellow-200 hover:bg-yellow-600 text-yellow-900 hover:text-white font-semibold transition-all duration-300">
            Log Out
        </button>
    </div>
</template>