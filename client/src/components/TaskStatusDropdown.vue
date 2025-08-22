<script setup lang="ts">
import {inject, PropType, ref} from "vue";
import type { Task } from "@/types/models/task";
import {AxiosInstance} from "axios";

const injectedApi = inject<AxiosInstance>("api");
if (!injectedApi) {
  throw new Error("API instance not provided");
}
const api = injectedApi;
const props = defineProps({
  task: {
    type: Object as PropType<Task>,
    required: true
  }
})

const status = ref(props.task.status);

function editStatus(updateStatus: {id?: number, status?: string}) {
  api.put(`/update-status/${updateStatus.id}`, {
    status: updateStatus.status
  }).then(() => {})
}
</script>

<template>
  <main>
    <select
        v-model="status"
        @change="editStatus({id: props.task.id, status: status})"
        class="border-2 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 hover:bg-yellow-200"
    >
      <option value="not started">🛌 Not Started</option>
      <option value="in progress">🏃💨 In Progress</option>
      <option value="on hold">⛔ On Hold</option>
    </select>
  </main>
</template>