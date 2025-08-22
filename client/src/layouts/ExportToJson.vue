<script setup lang="ts">
import { inject } from "vue";

const api = inject("api");

function exportToJson() {
  api.post('/tasks/export').then(res => {
    const id = res.data.export_id;
    const interval = setInterval(() => {
      api.get(`/check-export/${id}`).then(check => {
        if (check.data.ready) {
          clearInterval(interval);
          window.location.href = check.data.download_url;
        }
      });
    }, 2000);
  });
}

</script>

<template>
  <div>
    <button
        @click="exportToJson"
        class="w-full mt-6 py-2 px-4 rounded-full border-2 border-yellow-800 bg-yellow-200 hover:bg-yellow-600 text-yellow-900 hover:text-white font-semibold transition-all duration-300">
      Export To JSON
    </button>
  </div>
</template>