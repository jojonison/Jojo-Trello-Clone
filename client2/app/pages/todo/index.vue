<script setup lang="ts">
import {useAuthStore} from "~/store/auth";

const auth = useAuthStore();
const router = useRouter();
function logout() {
  auth.logout()
      .finally(() => {
        router.push({name:'home'});
      });
}
</script>

<template>
  <main>
    Logged in: {{auth.isLoggedIn}}
    <button class="outline" @click="logout">Log Out</button>
  </main>

</template>