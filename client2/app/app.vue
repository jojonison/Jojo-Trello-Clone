<template>
  <div class="flex justify-center space-x-12 bg-blue-400 border-[6px] border-b-blue-950 rounded-xl shadow-lg p-2 m-2">
    <NuxtLink to="home" class="hover:underline">Home</NuxtLink>
    <NuxtLink to="about" class="hover:underline">About</NuxtLink>
    <NuxtLink v-if="!auth.isLoggedIn" to="login" class="hover:underline">Log In</NuxtLink>
    <NuxtLink v-else to="home" class="hover:underline" @click="auth.logout()">Log Out</NuxtLink>
    <NuxtLink v-if="auth.isLoggedIn" to="todo" class="hover:underline">To Do List</NuxtLink>
  </div>
  <NuxtPage/>
</template>
<script setup lang="ts">
import { useAuthStore } from "~/store/auth";
const auth = useAuthStore();
</script>