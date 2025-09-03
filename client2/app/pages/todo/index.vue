<script setup lang="ts">
import {useAuthStore} from "~/store/auth";
const auth = useAuthStore();

const value = ref({});
const groups = ref([
  {
    id: 'options',
    label: 'Options',
    items: [
      {
        id: 'projects',
        label: 'View Projects',
      },
      {
        id: 'unfinished',
        label: 'View Unfinished Tasks',
      },
      {
        id: 'finished',
        label: 'View Finished Tasks',
      },
      {
        id: 'archived',
        label: 'View Archived Projects',
      }
    ]
  }
])
</script>

<template>
  <main>
    Logged in: {{ auth.isLoggedIn }}
    <UCommandPalette
        v-model="value"
        :groups="groups"
        class="flex bg-yellow-100/90 border-[6px] border-yellow-800 rounded-xl shadow-lg p-6 w-80"
    />

    <div class="mt-6">

    </div>
  </main>
</template>
