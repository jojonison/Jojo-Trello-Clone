<template>
  <div class="flex justify-center space-x-12">
    <NuxtLink to="home">Home</NuxtLink>
    <NuxtLink to="about">About</NuxtLink>
    <NuxtLink v-if="!auth.isLoggedIn" to="login">Log In</NuxtLink>
    <NuxtLink v-else to="home" @click="auth.logout()">Log Out</NuxtLink>
    <NuxtLink v-if="auth.isLoggedIn" to="todo">To Do List</NuxtLink>
  </div>
  <NuxtPage/>
</template>
<script setup lang="ts">
import { useAuthStore } from "~/store/auth";
const auth = useAuthStore();
</script>