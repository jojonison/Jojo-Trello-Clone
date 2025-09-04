<script setup lang="ts">
import type {AxiosResponse} from "axios";
import type {Task} from '~/utils/types/models/task';
const {$api} = useNuxtApp();
const finishedTasks = ref<Task[]>();
const props = defineProps<{
  selectedProject?: Project
}>()
onMounted(() => {
  loadFinishedTasks();
});
function loadFinishedTasks() {
  if (props.selectedProject === undefined) return
  $api.get(`/finished-tasks`, {params: {project_id: props.selectedProject.id}})
      .then((response: AxiosResponse) => {
        finishedTasks.value = response.data;
      })
}
</script>

<template>
  <main class="bg-blue-400 border-[6px] border-b-blue-950 rounded-xl shadow-lg m-2">
    <div v-if="props.selectedProject === undefined">
      Please select a project first
    </div>
    <div v-for="task in finishedTasks" :key="task.id" class="bg-blue-200 border-[2px] border-blue-950 p-2">
      {{ task.title }}
      {{ task.description }}
    </div>
  </main>
</template>