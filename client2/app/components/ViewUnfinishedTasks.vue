<script setup lang="ts">
import type {AxiosResponse} from "axios";
import type {Task} from '~/utils/types/models/task';
const {$api} = useNuxtApp();
const unfinishedTasks = ref<Task[]>();
const props = defineProps<{
  selectedProject?: Project
}>()
onMounted(() => {
  loadUnfinishedTasks();
});
function loadUnfinishedTasks() {
  if (props.selectedProject === undefined) return
  $api.get(`/unfinished-tasks`, {params: {project_id: props.selectedProject.id}})
      .then((response: AxiosResponse) => {
        unfinishedTasks.value = response.data;
      })
}
</script>

<template>
  <main class="bg-blue-400 border-[6px] border-b-blue-950 rounded-xl shadow-lg mx-5 my-2 w-full">
    <div v-if="props.selectedProject === undefined">
      Please select a project first
    </div>
    <div v-for="task in unfinishedTasks" :key="task.id" class="flex flex-col bg-blue-200 border-[2px] border-blue-950 p-2 text-blue-950">
      <div><b>Title:</b> {{ task.title }}</div>
      <div><b>Description:</b> {{ task.description }}</div>
      <div><b>Status:</b> {{ task.status }}</div>
    </div>

  </main>
</template>