<script setup lang="ts">
import type {AxiosResponse} from "axios";
import type {Task} from '~/utils/types/models/task';
import type {Project} from '~/utils/types/models/project';
const {$api} = useNuxtApp();
const finishedTasks = ref<Task[]>();
const toast = useToast();
const props = defineProps<{
  selectedProject?: Project
}>();
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

function removeTask(task: Task) {
  $api.delete(`/removeTask/${task.id}`)
      .then(() => {
        finishedTasks.value = finishedTasks.value?.filter(unfinishedTask => unfinishedTask.id !== task.id)
        toast.add({title: 'Removed', description: 'Task Permanently Removed', color:'success'});
      })
}
</script>

<template>
  <main class="bg-blue-400 border-[6px] border-b-blue-950 rounded-xl shadow-lg mx-5 my-2 w-full">
    <span class="font-bold flex justify-center text-4xl">Finished Tasks: </span>
    <div v-if="props.selectedProject === undefined" class="font-bold flex justify-center text-4xl">
      You must first select a project
    </div>
    <div v-else>
      <div v-for="task in finishedTasks" :key="task.id" class="flex flex-col bg-blue-200 border-[2px] border-blue-950 p-2 text-blue-950">
        <div><span class="font-bold">Title:</span> {{ task.title }}</div>
        <div><span class="font-bold">Description:</span> {{ task.description }}</div>
        <div><span class="font-bold">Completed At:</span> {{task.completed_at }}</div>
        <UButton
            color="neutral"
            class="border-[2px] border-blue-950 hover:bg-blue-400 bg-blue-300"
            @click="removeTask(task)"
        >
          👉🏻🗑️ Permanently Delete
        </UButton>
      </div>
    </div>
  </main>
</template>