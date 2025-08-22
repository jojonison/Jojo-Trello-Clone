<script setup lang="ts">
import { ref } from 'vue';
import { useAuthStore } from '@/store/auth';
import { router } from '@/routes';
import * as z from 'zod';

const schema = z.object({
  email: z.email('invalid email'),
  password: z.string()
})

const auth = useAuthStore();
const email = ref<string>('');
const password = ref<string>('');
const failedLogIn = ref<boolean>(false);

function authenticate() {
  const result = schema.safeParse({email: email.value, password: password.value});
  if (!result.success) {
    alert(result.error.message);
  } else {
    auth.login(email.value, password.value)
        .then(() => {
          router.push({name:'todo'});
        })
        .catch(() => {failedLogIn.value = true;})
        .finally(() => {})
  }

}
</script>

<template>
    <div>
        <main v-if="!auth.isLoggedIn" class="flex items-center justify-center h-screen bg-cover bg-center font-mono">
            <div v-if="failedLogIn">
                <img src="@/assets/sad_ant.jpg" height="200" width="200" alt="User not found"/>
                User not found
            </div>
            <div class="bg-yellow-100/90 border-[6px] border-yellow-800 rounded-xl shadow-lg p-6 w-80">
                <h2 class="text-center text-yellow-900 text-2xl font-bold mb-4 drop-shadow-sm ">Journal</h2>
                <form @submit.prevent="authenticate">
                    <div>
                        <label class="block mb-2 text-yellow-800">Email</label>
                        <input
                        v-model="email"
                        class="w-full border-4 border-yellow-700 rounded-md px-3 py-2 focus:outline-none focus:ring focus:ring-yellow-400 bg-yellow-50 text-yellow-900 placeholder-yellow-700"
                        placeholder="example@gmail.com"
                        type="email"
                        />
                    </div>
                    <div class="mt-4">
                        <label class="block mb-2 text-yellow-800">Password</label>
                        <input
                            v-model="password"
                            type="password"
                            class="w-full border-4 border-yellow-700 rounded-md px-3 py-2 focus:outline-none focus:ring focus:ring-yellow-400 bg-yellow-50 text-yellow-900 placeholder-yellow-700"
                            placeholder="password"
                        />
                    </div>
                    <button
                        type="submit"
                        class="w-full mt-6 py-2 px-4 rounded-full border-2 border-yellow-800 bg-yellow-200 hover:bg-yellow-300 text-yellow-900 hover:text-white font-semibold transition-all duration-300"
                    >
                    Log In
                    </button>
                </form>
            </div>
        </main>
        <div v-else>You are already logged in</div>
    </div>
</template>
