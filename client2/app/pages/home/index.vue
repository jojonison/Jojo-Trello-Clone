<script setup lang="ts">
import {useAuthStore} from "~/store/auth";
const router = useRouter();
const auth = useAuthStore();
function clickedButton() {
  if (auth.isLoggedIn) {
    router.push({name:'todo'})
  } else {
    router.push({name: 'archives'})
  }
}
</script>

<template>
  <main class="flex flex-col justify-center items-center min-h-screen ">
    <h1 class="font-extrabold">Just Another To-Do List App...</h1>
    <h2>And Just Another Home Page</h2>
    <UCalendar class="m-5" color="info"/>
    <h3>In the big <span class="font-extrabold text-9xl">2 5</span></h3>
    <UButton
        color="neutral"
        class="bg-blue-200 border-[6px] border-b-blue-950 rounded-xl shadow-lg m-2 hover:bg-blue-800 hover:text-white"
        @click="clickedButton"
    >
      ➡️ Get Started
    </UButton>
  </main>
</template>
